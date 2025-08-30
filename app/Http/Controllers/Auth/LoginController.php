<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:store_user')->except('logout');
        $this->middleware('guest:admin_user')->except('logout');
    }

    /**
     * Get the login username to be used by the controller.
     * AuthenticatesUsersのusernameをオーバーライド
     *
     * @return string
     */
    public function username()
    {
        // もし、DBに別の名前でユーザー名のカラムを作っていたらここを変える。
        return 'email';
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ], [
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => 'メールアドレス形式ではありません。',
            'password.required' => 'パスワードは8文字以上で入力してください。',
            'password.min' => 'パスワードは8文字以上で入力してください。',
        ]);
    }

    /**
     * Get the needed authorization credentials from the request.
     * AuthenticatesUsersのcredentialsをオーバーライド
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        // フォームからの値を取得
        $email = $request->input($this->username());
        $password = $request->input('password');
        // email認証
        return  ['email' => $email, 'password' => $password];
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        $credentials = $this->credentials($request);

        if (Auth::guard('web')->attempt($credentials, $request->filled('remember'))) {
            return redirect('/');
        }

        return back()->withErrors([
            'login_error' => 'ログイン情報に誤りがあります。',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout(); //ユーザー側のguardのみ
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
