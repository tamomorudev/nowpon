<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * ログイン時と現在のパスワードを比較し、変更前の一般会員セッションを無効にする。
 * 標準のAuthenticateSessionはセッション全体を消去するため、独立した店舗・管理者の
 * 認証を維持するこのアプリでは、一般会員の認証情報だけを解除する。
 */
class AuthenticateMemberSession
{
    // Laravel標準と同じキーを使い、ログイン成功時のハッシュを保存する。
    public const PASSWORD_HASH_KEY = 'password_hash_web';

    public function handle(Request $request, Closure $next)
    {
        // 店舗・管理者の画面では、それぞれのガードに認証を委ねる。
        if (! $request->hasSession() || $request->is('store', 'store/*', 'admin', 'admin/*')) {
            return $next($request);
        }

        $guard = Auth::guard('web');
        $user = $guard->user();

        if (! $user) {
            return $next($request);
        }

        // 比較用の値はログイン成功時だけ記録する。ここで更新すると旧端末も有効になる。
        $passwordHash = $request->session()->get(self::PASSWORD_HASH_KEY);
        $currentHash = $user->getAuthPassword();
        $valid = is_string($passwordHash) && hash_equals($currentHash, $passwordHash);

        if ($guard->viaRemember()) {
            // ログイン状態の保持で復帰した場合は、Cookie内のハッシュも照合する。
            $recaller = explode('|', (string) $request->cookie($guard->getRecallerName()));
            $valid = $valid && isset($recaller[2]) && hash_equals($currentHash, $recaller[2]);
        }

        if ($valid) {
            return $next($request);
        }

        // 導入前のセッションには比較用ハッシュがないため、一度だけ再ログインを求める。
        // 一般会員の認証情報だけを解除し、店舗・管理者のセッションと保持用トークンを残す。
        $guard->logoutCurrentDevice();
        $request->session()->forget([
            self::PASSWORD_HASH_KEY,
            'auth.password_confirmed_at',
        ]);
        $request->session()->migrate(true);
        $request->session()->regenerateToken();
        $message = trans('passwords.login_required', [], 'ja');

        if ($request->expectsJson()) {
            // 認証が失効したリクエストを後続の処理へ渡さない。
            return response()->json(['message' => $message], 401);
        }

        return redirect()->route('login')->with('status', $message);
    }
}
