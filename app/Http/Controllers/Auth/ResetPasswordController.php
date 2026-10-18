<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Middleware\AuthenticateMemberSession;
use App\Http\Middleware\SetJapaneseLocale;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | パスワードの再設定
    |--------------------------------------------------------------------------
    |
    | 入力検証、トークンの照合・有効期限・使用後の削除は標準処理を使う。
    | 再設定に成功した後の認証処理だけ、通常のログインを求める形に変更する。
    |
    */

    use ResetsPasswords {
        // セッションの更新後も、HTML・JSONの成功応答は標準処理を再利用する。
        sendResetResponse as protected passwordResetResponse;
    }

    public function __construct()
    {
        $this->middleware(SetJapaneseLocale::class);
    }

    /**
     * 再設定後はログイン画面へ戻し、新しいパスワードの入力を求める。
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * 標準のパスワード更新処理を維持し、自動ログインだけを省く。
     */
    protected function resetPassword($user, $password)
    {
        $this->setUserPassword($user, $password);

        // ログイン状態の保持に使うトークンを更新し、以前のCookieを無効にする。
        $user->setRememberToken(Str::random(60));
        $user->save();

        event(new PasswordReset($user));
    }

    /**
     * このブラウザの一般会員の認証情報を解除してから、成功応答を返す。
     */
    protected function sendResetResponse(Request $request, $response)
    {
        // 店舗・管理者のセッションは保持するため、セッション全体を破棄しない。
        // logoutCurrentDeviceは、別端末で新たに発行された保持用トークンも変更しない。
        $this->guard()->logoutCurrentDevice();
        $request->session()->forget([
            AuthenticateMemberSession::PASSWORD_HASH_KEY,
            'auth.password_confirmed_at',
        ]);

        // 認証状態の変更に合わせ、以前のセッションIDとCSRFトークンを使えなくする。
        $request->session()->migrate(true);
        $request->session()->regenerateToken();

        return $this->passwordResetResponse($request, $response);
    }

    protected function guard()
    {
        // 店舗・管理者の画面で既定ガードが切り替わっても、対象は一般会員に固定する。
        return Auth::guard('web');
    }
}
