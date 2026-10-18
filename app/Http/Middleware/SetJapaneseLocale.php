<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * パスワード再設定の処理中だけ、標準の通知・入力検証を日本語で実行する。
 */
class SetJapaneseLocale
{
    public function handle(Request $request, Closure $next)
    {
        $previousLocale = app()->getLocale();
        app()->setLocale('ja');

        try {
            return $next($request);
        } finally {
            // 例外が発生した場合も元に戻し、後続の処理へ言語設定を持ち越さない。
            app()->setLocale($previousLocale);
        }
    }
}
