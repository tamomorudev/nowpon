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
use App\Models\User;

class AdminUserController extends Controller
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
        $users = User::select()->paginate(50);

        return view('admin.user.index', compact('user', 'users'));
    }

    public function detail(Request $request)
    {
        $user = Auth::guard('admin_user')->user(); //ユーザー情報
        $users = User::select()->paginate(50);

        if(!isset($request['id'])) {
            abort(404);
        }

        $user_id = $request['id'];
        $user_data = User::where('id', $user_id)->first();

        if(!$user_data) {
            abort(404);
        }

        return view('admin.user.detail', compact('user', 'users', 'user_data'));
    }

}
