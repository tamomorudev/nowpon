<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class AdminRegisterController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('guest:store_user');
        $this->middleware('guest:admin_user');
    }
    
    // 登録画面
    public function create(): View
    {
        return view('admin.auth.register');
    }

    // 登録実行
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            //'email' => ['required', 'string', 'email', 'max:255', 'unique:'.Admin::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $admin_user = AdminUser::create([
            'name' => $request->name,
            'email' => '',
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($admin_user));

        Auth::guard('admin_user')->login($admin_user);

        //return redirect(RouteServiceProvider::HOME);
        return redirect('/admin/home');
    }
}