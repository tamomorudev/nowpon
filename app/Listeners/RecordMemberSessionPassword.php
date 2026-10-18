<?php

namespace App\Listeners;

use App\Http\Middleware\AuthenticateMemberSession;
use Illuminate\Auth\Events\Login;

/**
 * 通常ログイン・会員登録・ログイン状態の保持による復帰を、Loginイベントで扱う。
 */
class RecordMemberSessionPassword
{
    public function handle(Login $event): void
    {
        // 店舗・管理者には適用せず、認証に成功した一般会員の比較用ハッシュだけを記録する。
        if ($event->guard === 'web' && request()->hasSession()) {
            request()->session()->put(
                AuthenticateMemberSession::PASSWORD_HASH_KEY,
                $event->user->getAuthPassword()
            );
        }
    }
}
