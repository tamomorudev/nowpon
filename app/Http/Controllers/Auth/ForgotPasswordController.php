<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Middleware\SetJapaneseLocale;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | パスワード再設定メールの送信
    |--------------------------------------------------------------------------
    |
    | 入力検証、トークンの発行、同一メールアドレスへの再送制限は
    | Laravel標準のトレイトとパスワードブローカーに委ねる。
    |
    */

    use SendsPasswordResetEmails;

    public function __construct()
    {
        // 標準処理が返す入力エラーと送信結果を、この画面では日本語にする。
        $this->middleware(SetJapaneseLocale::class);

        // 同一IPからの連続送信を制限する。入力画面の表示は制限しない。
        $this->middleware('throttle:password-reset')->only('sendResetLinkEmail');
    }
}
