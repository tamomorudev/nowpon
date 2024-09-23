<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\StoreUser;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class StoreRegisterController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('guest:store_user');
    }
    
    // 登録画面
    public function create(): View
    {
        return view('store.auth.register');
    }

    // 登録実行
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            //'email' => ['required', 'string', 'email', 'max:255', 'unique:'.Admin::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        //company_id生成
        $company_id = str_pad(random_int(0,99999),5,0, STR_PAD_LEFT);

        $store_user = StoreUser::create([
            'name' => $request->name,
            'email' => '',
            'password' => Hash::make($request->password),
            'company_id' => (int)$company_id,
        ]);

        event(new Registered($store_user));

        Auth::guard('store_user')->login($store_user);

        //return redirect(RouteServiceProvider::HOME);
        return redirect('/store/home');
    }
}