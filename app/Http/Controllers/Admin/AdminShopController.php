<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Models\Stores;
use App\Models\StoreServices;
use App\Models\StoreUser;

class AdminShopController extends Controller
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
        $user = Auth::guard('admin_user')->user(); //ユーザー情報
        $stores = Stores::select()->paginate(50); //stores情報

        return view('admin.shop.index', compact('user', 'stores'));
    }

    public function detail(Request $request)
    {
        $user = Auth::guard('store_user')->user(); //ユーザー情報

        if(!isset($request['si'])) {
            abort(404);
        }

        $store_id = $request['si'];
        $store_data = Stores::select()->where('id', $store_id)->first(); //stores情報

        if(!$store_data) {
            abort(404);
        }

        return view('admin.shop.detail', compact('user', 'store_data'));
    }

    public function account()
    {
        $user = Auth::guard('admin_user')->user(); //ユーザー情報
        $store_users = StoreUser::select()->paginate(50); //storeuser
        return view('admin.shop.account', compact('user', 'store_users'));
    }

    public function cource()
    {
        $user = Auth::guard('admin_user')->user(); //ユーザー情報
        $stores = Stores::select()->paginate(50); //stores情報
        
        return view('admin.shop.cource', compact('user'));
    }

}
