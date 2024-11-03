<?php

namespace App\Http\Controllers\Store;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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

    public function create(Request $request)
    {
        $user = Auth::user(); //ユーザー情報
        $request = $request->all();

        if (isset($request['address1'])) {
            $validated_data = Validator::make($request, [
                'store_name' => 'required',
                'email' => 'required|max:190',
                'postal_code' => 'required',
                'address1' => 'required',
                'address2' => 'required',
                'address3' => 'required',
                'phone_number' => 'required|max:11'
            ]);
            
            if ($validated_data->fails()) {
                $error = true;
                return view('store.shop.create', compact('error'));
            }

            //店舗登録
            $create_shop_array = array();
            $create_shop_array['company_id'] = $user->company_id;
            $create_shop_array['store_name'] = $request['store_name'];
            $create_shop_array['email'] = $request['email'];
            $create_shop_array['postal_code'] = $request['postal_code'];
            $create_shop_array['address1'] = $request['address1'];
            $create_shop_array['address2'] = $request['address2'];
            $create_shop_array['address3'] = $request['address3'];
            $create_shop_array['phone_number'] = $request['phone_number'];
            $create_shop_array['created_by'] = $user->id;
            $create_shop_array['updated_by'] = $user->id;

            Stores::create($create_shop_array);

            return redirect('/store/shop');
        }

        return view('store.shop.create', compact('user'));
    }

    public function account()
    {
        $user = Auth::user(); //ユーザー情報
        return view('store.shop.account', compact('user'));
    }

    public function accountCreate(Request $request)
    {
        $user = Auth::user(); //ユーザー情報
        $request = $request->all();

        return view('store.shop.account_create', compact('user'));
    }

    public function cource()
    {
        $user = Auth::user(); //ユーザー情報
        $stores = Stores::select()->where('company_id', $user->company_id)->get(); //stores情報
        return view('store.shop.cource', compact('user'));
    }

    public function courceCreate(Request $request)
    {
        $user = Auth::user(); //ユーザー情報
        $stores = Stores::select()->where('company_id', $user->company_id)->get(); //stores情報

        $request = $request->all();

        if (isset($request['store_name'])) {
            $validated_data = Validator::make($request, [
                'store_name' => 'required',
                'cource_name' => 'required|max:190:',
                'price' => 'required|regex:/^[1-9][0-9]*/|not_in:0',
                'detail' => 'required'
            ]);
            
            if ($validated_data->fails()) {
                $error = true;
                return view('store.shop.cource_create', compact('error', 'stores'));
            }

            return redirect('/store/cource');
        }

        return view('store.shop.cource_create', compact('stores'));
    }
}
