<?php

namespace App\Http\Controllers\Store;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Stores;
use App\Models\StoreServices;

class StoreShopController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Shop pages.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user(); //ユーザー情報
        $stores = Stores::select()->where('company_id', $user->company_id)->get(); //stores情報
        return view('store.shop.index', compact('user', 'stores'));
    }

    public function create()
    {
        $user = Auth::user(); //ユーザー情報
        return view('store.shop.create', compact('user'));
    }

    public function menuCreate()
    {
        $user = Auth::user(); //ユーザー情報
        return view('store.shop.menu_create');
    }
}
