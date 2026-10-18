<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    //エラーページ制御
    public function render($request, Throwable $e)
    {
        // メール送信フォームの期限切れは、新しいCSRFトークンで再入力できる画面へ戻す。
        // 送信自体は実行せず、JSONと他のフォームは既存の例外処理に委ねる。
        if ($e instanceof TokenMismatchException
            && $request->isMethod('post')
            && $request->routeIs('password.email')
            && ! $request->expectsJson()) {
            return redirect()->route('password.request')
                // パスワードなどの不要な入力値をセッションに保存しない。
                ->withInput($request->only('email'))
                ->withErrors(['email' => trans('passwords.session_expired', [], 'ja')]);
        }

        if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException || $e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {

        $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 404;

        if ($request->is('store') || $request->is('store/*')) {
            $view = 'errors.store.' . $statusCode;
            if (view()->exists($view)) {
                return response()->view($view, [], $statusCode);
            }
        }

        if ($request->is('admin') || $request->is('admin/*')) {
            $view = 'errors.admin.' . $statusCode;
            if (view()->exists($view)) {
                return response()->view($view, [], $statusCode);
            }
        }
    }

        return parent::render($request, $e);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $exception->getMessage()], 401);
        }
        if ($request->is('store') || $request->is('store/*')) {
            return redirect()->guest('/store/login');
        }
        if ($request->is('admin') || $request->is('admin/*')) {
            return redirect()->guest('/admin/login');
        }

        return redirect()->guest($exception->redirectTo() ?? route('login'));
    }
}
