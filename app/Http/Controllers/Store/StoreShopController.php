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
use App\Models\Stations;

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
                'station_line' => 'required',
                'station' => 'required',
                'transportation' => 'required',
                'time' => 'required',
            ]);

            if ($validated_data->fails()) {
                return redirect()->route('store.shop.create')
                    ->withErrors($validated_data)
                    ->withInput();
            }

            if (isset($request['images']) && $request['images']) {
                //画像チェック
                $fileSize = $request['images']->getSize();
                $maxSize = 1000 * 1024 * 1024; // 一旦1MB制限
                if ($fileSize > $maxSize) {
                    return redirect()->route('store.coupon.create')
                    ->withErrors("ファイルサイズが1GBを超えています。")
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

            if (isset($request['station_line_2']) && $request['station_line_2']) {
                if (!isset($request['station_2']) || !$request['station_2'] || !isset($request['time_2']) || !$request['time_2']) {
                    return redirect()->route('store.shop.create')
                    ->withErrors(['station_error' => 1])
                    ->withInput();
                }
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
            $create_shop_array['line'] = $request['station_line'];
            $create_shop_array['station'] = $request['station'];
            $create_shop_array['transportation'] = $request['transportation'];
            $create_shop_array['time'] = $request['time'];
            if (isset($request['station_line_2']) && $request['station_line_2']) {
                $create_shop_array['line_2'] = $request['station_line_2'];
                $create_shop_array['station_2'] = $request['station_2'];
                $create_shop_array['transportation_2'] = $request['transportation_2'];
                $create_shop_array['time_2'] = $request['time_2'];
            }
            $create_shop_array['image'] = $img_path;
            $create_shop_array['phone_number'] = $request['phone_number'];
            $create_shop_array['created_by'] = $user->id;
            $create_shop_array['updated_by'] = $user->id;

            Stores::create($create_shop_array);

            return redirect('/store/shop');
        }

        return view('store.shop.create', compact('user'));
    }

    public function edit(Request $request)
    {
        $user = Auth::user(); //ユーザー情報
        $request = $request->all();

        if(!isset($request['si'])) {
            abort(404);
        }

        $store_id = $request['si'];
        $store_data = Stores::select()->where('id', $store_id)->where('company_id', $user->company_id)->first(); //stores情報

        if(!$store_data) {
            abort(404);
        }

        if (isset($request['address1']) && isset($request['p_type']) && $request['p_type'] == 'edit') {
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
                return redirect()->back()
                    ->withErrors($validated_data)
                    ->withInput();
            }

            if (isset($request['images']) && $request['images']) {
                //画像チェック
                $fileSize = $request['images']->getSize();
                $maxSize = 1000 * 1024 * 1024; // 一旦1MB制限
                if ($fileSize > $maxSize) {
                    return redirect()->back()
                    ->withErrors("ファイルサイズが1GBを超えています。")
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

            //店舗編集
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

            Stores::where('id', $store_data->id)->update($create_shop_array);

            return redirect('/store/shop');
        }

        return view('store.shop.edit', compact('user', 'store_data'));
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

    public function checkStation(Request $request)
    {
        $user = Auth::user(); //ユーザー情報

        $request = $request->all();

        if ($_POST) {
            //ajax 路線
            if (isset($request['type']) && isset($request['prefecture']) && $request['type'] == 1) {
                $prefecture = config('commons.prefectures')[$request['prefecture']];
                $lines = Stations::select('line')->where('prefecture', $prefecture)->groupBy('line')->get()->toArray();
                $lines = json_decode(json_encode($lines), true);

                return response()->json(['lines' => $lines]);
            }
            //ajax 駅
            if (isset($request['type']) && isset($request['line']) && $request['type'] == 2) {
                $stations = Stations::select('name')->where('line', $request['line'])->groupBy('name')->get()->toArray();
                $stations = json_decode(json_encode($stations), true);

                return response()->json(['stations' => $stations]);
            }
        }

        return response()->json(['status' => 1]);
    }
}
