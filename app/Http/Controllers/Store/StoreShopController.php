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
use App\Services\ImageService;

class StoreShopController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ImageService $imageService) { 
        $this->imageService = $imageService;
    }

    /**
     * Shop pages.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::guard('store_user')->user(); //ユーザー情報
        $stores = Stores::select()->where('company_id', $user->company_id)->paginate(50); //stores情報

        return view('store.shop.index', compact('user', 'stores'));
    }

    public function create(Request $request, ImageService $imageService)
    {
        $user = Auth::guard('store_user')->user(); //ユーザー情報

        if ($request->isMethod('post')) {
            //$request = $request->all();
            $validated_data = Validator::make($request->all(), [
                'store_name'     => 'required',
                'email'          => 'required|email|max:190',
                'postal_code'    => 'required|digits:7',
                'address1'       => 'required',
                'address2'       => 'required',
                'address3'       => 'required',
                'phone_number'   => 'required|digits_between:10,11',
                'genre'          => 'required',
                'station_line'   => 'required',
                'station'        => 'required',
                'transportation' => 'required',
                'time'           => 'required',
            ], [
                'store_name.required'     => '店舗名を入力してください。',
                'email.required'          => 'メールアドレスを入力してください。',
                'email.email'             => '正しいメールアドレス形式で入力してください。',
                'email.max'               => 'メールアドレスは190文字以内で入力してください。',
                'postal_code.required'    => '郵便番号を入力してください。',
                'postal_code.digits'      => '郵便番号は7桁で入力してください。',
                'address1.required'       => '都道府県を入力してください。',
                'address2.required'       => '市区町村を入力してください。',
                'address3.required'       => '住所を入力してください。',
                'phone_number.required'   => '電話番号を入力してください。',
                'phone_number.digits_between' => '電話番号は10桁または11桁で入力してください。',
                'genre.required'          => 'ジャンルを入力してください。',
                'station_line.required'   => '路線を選択してください。',
                'station.required'        => '駅を選択してください。',
                'transportation.required' => '交通手段を選択してください。',
                'time.required'           => '時間を入力してください。',
            ]);

            if ($validated_data->fails()) {
                return redirect()->route('store.shop.create')
                    ->withErrors($validated_data)
                    ->withInput();
            }

            //画像登録
            $result = $this->imageService->upload($request, 'store_image', 'images');
            if (isset($result['error']) && $result['error']) { 
                return redirect()->route('store.shop.create')->withErrors($result['error'])->withInput(); 
            }
            $img_path = $result['path'];
            /*
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
            */
            

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
        $user = Auth::guard('store_user')->user(); //ユーザー情報

        if(!isset($request['si'])) {
            abort(404);
        }

        $store_id = $request['si'];
        $store_data = Stores::select()->where('id', $store_id)->where('company_id', $user->company_id)->first(); //stores情報

        if(!$store_data) {
            abort(404);
        }

        if ($request->isMethod('post') && isset($request['p_type']) && $request['p_type'] == 'edit') {
            //$request = $request->all();
            $validated_data = Validator::make($request->all(), [
                'store_name'     => 'required',
                'email'          => 'required|email|max:190',
                'postal_code'    => 'required|digits:7',
                'address1'       => 'required',
                'address2'       => 'required',
                'address3'       => 'required',
                'phone_number'   => 'required|digits_between:10,11',
                'genre'          => 'required',
                'station_line'   => 'required',
                'station'        => 'required',
                'transportation' => 'required',
                'time'           => 'required',
            ], [
                'store_name.required'     => '店舗名を入力してください。',
                'email.required'          => 'メールアドレスを入力してください。',
                'email.email'             => '正しいメールアドレス形式で入力してください。',
                'email.max'               => 'メールアドレスは190文字以内で入力してください。',
                'postal_code.required'    => '郵便番号を入力してください。',
                'postal_code.digits'      => '郵便番号は7桁で入力してください。',
                'address1.required'       => '都道府県を入力してください。',
                'address2.required'       => '市区町村を入力してください。',
                'address3.required'       => '住所を入力してください。',
                'phone_number.required'   => '電話番号を入力してください。',
                'phone_number.digits_between' => '電話番号は10桁または11桁で入力してください。',
                'genre.required'          => 'ジャンルを入力してください。',
                'station_line.required'   => '路線を選択してください。',
                'station.required'        => '駅を選択してください。',
                'transportation.required' => '交通手段を選択してください。',
                'time.required'           => '時間を入力してください。',
            ]);

            if ($validated_data->fails()) {
                return redirect()->back()
                    ->withErrors($validated_data)
                    ->withInput();
            }

            $result = $this->imageService->upload($request, 'store_image', 'images');
            if ($result['error']) { 
                return redirect()->route('store.shop.edit')->withErrors($result['error'])->withInput(); 
            }
            $img_path = $result['path'];
            /*
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
            */

            if (isset($request['station_line_2']) && $request['station_line_2']) {
                if (!isset($request['station_2']) || !$request['station_2'] || !isset($request['time_2']) || !$request['time_2']) {
                    return redirect()->route('store.shop.edit')
                    ->withErrors(['station_error' => 1])
                    ->withInput();
                }
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

            Stores::where('id', $store_data->id)->update($create_shop_array);

            return redirect('/store/shop');
        }

        return view('store.shop.edit', compact('user', 'store_data'));
    }

    public function detail(Request $request)
    {
        $user = Auth::guard('store_user')->user(); //ユーザー情報

        if(!isset($request['si'])) {
            abort(404);
        }

        $store_id = $request['si'];
        $store_data = Stores::select()->where('id', $store_id)->where('company_id', $user->company_id)->first(); //stores情報

        if(!$store_data) {
            abort(404);
        }

        return view('store.shop.detail', compact('user', 'store_data'));
    }

    public function account()
    {
        $user = Auth::guard('store_user')->user(); //ユーザー情報
        $store_users = StoreUser::select()->where('company_id', $user->company_id)->paginate(50); //storeuser
        return view('store.shop.account', compact('user', 'store_users'));
    }

    public function accountCreate(Request $request)
    {
        $user = Auth::guard('store_user')->user();  //ユーザー情報

        if ($_POST) {
            if ($request->isMethod('post')) {
                $validated_data = Validator::make($request->all(), [
                    'name' => 'required|min:10|max:100:',
                    'password' => 'required|min:10|max:100',
                ], [
                    'name.required'     => 'ユーザー名を入力してください。',
                    'name.min'          => 'ユーザー名は10文字以上で入力してください',
                    'name.max'          => 'ユーザー名は100文字以内で入力してください',
                    'password.required'             => 'パスワードを入力してください。',
                    'password.min'               => 'パスワードは10文字以上で入力してください。',
                    'password.max'               => 'パスワードは100文字以下で入力してください。',
                ]);

                if ($validated_data->fails()) {
                    return redirect()->route('store.account.create')
                        ->withErrors($validated_data)
                        ->withInput();
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
        $user = Auth::guard('store_user')->user(); //ユーザー情報
        $stores = Stores::select()->where('company_id', $user->company_id)->get(); //stores情報

        if ($_POST) {
            if ($request->isMethod('post')) {
                $validated_data = Validator::make($request->all(), [
                    'store_name'  => 'required',
                    'cource_name' => 'required|min:10|max:190',
                    'price'       => 'required|integer|min:1',
                    'detail'      => 'required|min:10',
                ], [
                    'store_name.required'   => '店舗名を入力してください。',
                    'cource_name.required'  => 'メニュー名を入力してください。',
                    'cource_name.min'       => 'メニュー名は10文字以上で入力してください。',
                    'cource_name.max'       => 'メニュー名は190文字以内で入力してください。',
                    'price.required'        => '定価を入力してください。',
                    'price.integer'         => '定価は整数で入力してください。',
                    'price.min'             => '定価は1以上で入力してください。',
                    'detail.required'       => '説明を入力してください。',
                    'detail.min'            => '説明は10文字以上で入力してください。',
                ]);

                if ($validated_data->fails()) {
                    return redirect()->route('store.shop.cource')
                        ->withErrors($validated_data)
                        ->withInput();
                } else {
                    $store_user = StoreServices::create([
                        'company_id' => (int)$user->company_id,
                        'store_id' => $request['store_name'],
                        'service_name' => $request['cource_name'],
                        'price' => $request['price'],
                        'detail' => $request['detail'],
                    ]);

                    return redirect('/store/cource');
                }

                return redirect('/store/cource');
            }
        }

        return view('store.shop.cource_create', compact('user','stores'));
    }

    public function checkStation(Request $request)
    {
        $user = Auth::guard('store_user')->user(); //ユーザー情報

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
