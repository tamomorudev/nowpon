<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordResetNotification extends ResetPassword
{
    /**
     * Laravel標準の再設定通知を継承し、件名・本文・テンプレートを日本語にする。
     */
    protected function buildMailMessage($url)
    {
        // 案内する期限をブローカーの設定に合わせ、実際の有効期限とのずれを防ぐ。
        $minutes = config(
            'auth.passwords.'.config('auth.defaults.passwords').'.expire',
            60
        );

        return (new MailMessage)
            ->markdown('emails.password-reset')
            ->subject('【NowPon】パスワード再設定のご案内')
            ->greeting('パスワード再設定のご依頼を受け付けました。')
            ->line('以下のボタンから、新しいパスワードを設定してください。')
            ->action('パスワードを再設定する', $url)
            ->line("このリンクの有効期限は{$minutes}分です。期限が切れた場合は、再度メールを送信してください。")
            ->line('この操作に心当たりがない場合は、このメールを破棄してください。パスワードは変更されません。')
            ->salutation('NowPon運営事務局');
    }

    /**
     * リクエストのHostに依存せず、設定済みのアプリURLから再設定リンクを作る。
     */
    protected function resetUrl($notifiable)
    {
        // パスとクエリの生成は名前付きルートに委ね、メールアドレスも適切に符号化する。
        return rtrim(config('app.url'), '/').route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false);
    }
}
