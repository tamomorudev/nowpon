<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StoreLoginController extends Controller
{

    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:store_user')->except('logout');
        $this->middleware('guest:admin_user')->except('logout');
    }

    public function username()
    {
        // もし、DBに別の名前でユーザー名のカラムを作っていたらここを変える。
        return 'name';
    }

    // ログイン画面呼び出し
    public function showLoginPage(): View
    {
        return view('store.auth.login');
    }

    // ログイン実行
    public function login(Request $request)
    {
        $credentials = $request->only(['name', 'password']);

        // フォームからの値を取得
        $username = $request->input($this->username());
        $password = $request->input('password');
        // usernameがemail形式かを判定
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            // email形式の場合は連想配列のkey=emailに値を渡す
            $credential = ['email' => $username, 'password' => $password];
        } else {
            // email形式でない場合は連想配列のkey=nameに値を渡す
            $credential = [$this->username() => $username, 'password' => $password];
        }

        if (Auth::guard('store_user')->attempt($credentials)) {
            return redirect()->route('store.home')->with([
                'login_msg' => 'ログインしました。',
            ]);
        }

        return back()->withErrors([
            'login' => ['ログインに失敗しました'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('store_user')->logout(); //ストア側のguardのみ
        $request->session()->regenerateToken();

        return redirect('/store');
    }
}