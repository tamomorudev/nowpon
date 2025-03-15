<?php

namespace App\Http\Controllers\Store;
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
                'phone_number' => 'required|max:11',
                'genre' => 'required',
            ]);

            if ($validated_data->fails()) {
                return redirect()->route('store.shop.create')
                    ->withErrors($validated_data)
                    ->withInput();
            }

            if (isset($request['images']) && $request['images']) {
                //画像チェック
                $fileSize = $request['images']->getSize();
                $maxSize = 1 * 1024 * 1024; // 一旦1MB制限
                if ($fileSize > $maxSize) {
                    return redirect()->route('store.coupon.create')
                    ->withErrors("ファイルサイズが1MBを超えています。")
                    ->withInput();
                } 
                $img = $request['images'];
                if (strpos($request['images']->getMimeType(), 'image') !== false) {
                    $img_path = $img->store('store_image','pub_images');
                } else {
                    $img_path = '';
                }
            } else {
                $img_path = '';
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
            $create_shop_array['url'] = $request['url'];
            $create_shop_array['genre'] = $request['genre'];
            $create_shop_array['image'] = $img_path;
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
        $store_users = StoreUser::select()->where('company_id', $user->company_id)->get(); //storeuser
        return view('store.shop.account', compact('user', 'store_users'));
    }

    public function accountCreate(Request $request)
    {
        $user = Auth::user(); //ユーザー情報
        $request = $request->all();

        if ($_POST) {
            if (isset($request['name']) || isset($request['password'])) {
                $validated_data = Validator::make($request, [
                    'name' => 'required|max:190:',
                    'password' => 'required',
                ]);

                if ($validated_data->fails()) {
                    $error = true;
                    return view('store.shop.account_create', compact('error', 'user'));
                } else {
                    $store_user = StoreUser::create([
                        'name' => $request['name'],
                        'email' => '',
                        'password' => Hash::make($request['password']),
                        'company_id' => (int)$user->company_id,
                        'parent_user_id' => $user->id,
                    ]);

                    return redirect('/store/account');
                }
            } else {
                $error = true;
                return view('store.shop.account_create', compact('error', 'user'));
            }
        }

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

        if ($_POST) {
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
                } else {
                    $store_user = StoreUser::create([
                        'name' => $request['name'],
                        'email' => '',
                        'password' => Hash::make($request['password']),
                        'company_id' => (int)$user->company_id,
                        'parent_user_id' => $user->id,
                    ]);

                    return redirect('/store/cource');
                }

                return redirect('/store/cource');
            }
        }

        return view('store.shop.cource_create', compact('stores'));
    }
}
