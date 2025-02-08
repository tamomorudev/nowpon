<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
        return 'name';
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
        $username = $request->input($this->username());
        $password = $request->input('password');
        // usernameがemail形式かを判定
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            // email形式の場合は連想配列のkey=emailに値を渡す
            return  ['email' => $username, 'password' => $password];
        } else {
            // email形式でない場合は連想配列のkey=nameに値を渡す
            return [$this->username() => $username, 'password' => $password];
        }
    }
}
