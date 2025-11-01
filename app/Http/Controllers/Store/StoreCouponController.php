<?php

namespace App\Http\Controllers\Store;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use App\Models\Coupons;
use App\Models\Stores;
use App\Models\StoreServices;

class StoreCouponController extends Controller
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
     * coupon pages.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::guard('store_user')->user(); //ユーザー情報
        $coupons = Coupons::select('coupons.*','stores.store_name')->join('stores', 'coupons.store_id', '=', 'stores.id')->where('coupons.company_id', $user->company_id)->orderBy('created_at', 'DESC')->get(); //クーポン情報
        $stores = Stores::select()->where('company_id', $user->company_id)->get(); //stores情報
        return view('store.coupon.index', compact('user', 'stores', 'coupons'));
    }

    public function create(Request $request)
    {
        $user = Auth::guard('store_user')->user(); //ユーザー情報
        $stores = Stores::select()->where('company_id', $user->company_id)->get(); //stores情報
        $request = $request->all();

        if (isset($request['store_name'])) {
            $validated_data = Validator::make($request, [
                'store_name' => 'required',
                'coupon_name' => 'required|max:190',
                'price' => 'required|numeric|min:1',
                'store_pay_price' => 'required|numeric|min:1',
                'cource_time' => 'required',
                'cource_start' => 'required',
                'detail' => 'required',
                'start_date' => 'required',
                'end_date' => 'required'
            ], [
                'store_name.required' => '店舗を選択してください。',
                'coupon_name.required' => 'クーポン名を入力してください。',
                'price.required' => 'クーポン定価を入力してください。',
                'price.numeric' => 'クーポン定価は数値で入力してください。',
                'price.min' => 'クーポン定価は1円以上にしてください。',
                'store_pay_price.required' => '店舗支払金額は0円以上にしてください。',
                'store_pay_price.numeric' => '店舗支払金額は数値で入力してください。',
                'store_pay_price.min' => '店舗支払金額は0円以上にしてください。',
                'cource_time.required' => 'コース時間を入力してください。',
                'cource_start.required' => 'コース開始時間を入力してください。',
                'detail.required' => '説明を入力してください。',
                'start_date.required' => '有効期限を入力してください。',
                'end_date.required' => '有効期限を入力してください。',
            ]);

            if ($validated_data->fails()) {
                return redirect()->route('store.coupon.create')
                    ->withErrors($validated_data)
                    ->withInput();
            }

            //date covert
            $start_date = str_replace('T', ' ', $request['start_date']).':00';
            $end_date = str_replace('T', ' ', $request['end_date']).':59';
            $cource_start = str_replace('T', ' ', $request['cource_start']).':00';

            $date = new \DateTime($cource_start);
            $day_of_the_week = $date->format('w');

            //画像
            $image_keys = ['images', 'images_2', 'images_3', 'images_4', 'images_5'];
            $img_paths = [];
            $img_index = 0;
            $coupon_image_array = array();

            foreach ($image_keys as $key) {
                if (isset($request[$key]) && $request[$key]) {
                    $file = $request[$key];
                    $fileSize = $file->getSize();
                    $maxSize = 1000 * 1024 * 1024; // 一旦1GB制限

                    if ($fileSize > $maxSize) {
                        return redirect()->route('store.coupon.create')
                            ->withErrors(["{$key}" => "ファイルサイズが1MBを超えています。"])
                            ->withInput();
                    }

                    if (strpos($file->getMimeType(), 'image') !== false) {
                        $img_path = $file->store('coupon_image', 'pub_images');

                        // 画像がある順に詰める
                        if ($img_index == 0) {
                            $field_name = 'img_url';
                        } else {
                            $field_name = 'img_url_' . ($img_index + 1);
                        }
                        $coupon_image_array[$field_name] = $img_path;
                        $img_index++;
                    }
                }
            }

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
                    $img_path = $img->store('coupon_image','pub_images');
                } else {
                    $img_path = '';
                }
            } else {
                $img_path = '';
            }
            */

            //サービス料算出
            $service_price = round($request['store_pay_price'] * 0.15);
            $original_service_price = round($request['price'] * 0.15);

            //割引率計算
            if ($request['store_pay_price'] < $request['price']) {
                $discount_rate = round((1 - ($request['store_pay_price'] / $request['price'])) * 100);
            } else {
                $discount_rate = 0;
            }

            //クーポン登録
            DB::beginTransaction();
            try {
                $create_coupon_array = array();
                $create_coupon_array['coupon_name'] = $request['coupon_name'];
                $create_coupon_array['company_id'] = $user->company_id;
                $create_coupon_array['store_id'] = $request['store_name'];
                $create_coupon_array['price'] = $request['price'];
                //$create_coupon_array['discount_price'] = $request['discount_price'];
                //$create_coupon_array['discount_type'] = $request['discount_type'];
                $create_coupon_array['service_price'] = $service_price;
                $create_coupon_array['original_service_price'] = $original_service_price;
                $create_coupon_array['store_pay_price'] = $request['store_pay_price'];
                $create_coupon_array['discount_rate'] = $discount_rate;
                $create_coupon_array['cource_time'] = $request['cource_time'];
                $create_coupon_array['cource_start'] = $cource_start;
                $create_coupon_array['day_of_the_week'] = $day_of_the_week;
                $create_coupon_array['detail'] = $request['detail'];
                $create_coupon_array['expire_start_date'] = $start_date;
                $create_coupon_array['expire_end_date'] = $end_date;
                //$create_coupon_array['img_url'] = $img_path;
                foreach ($coupon_image_array as $field_name => $coupon_image_path) {
                    $create_coupon_array[$field_name] = $coupon_image_path;
                }
                $create_coupon_array['created_by'] = $user->id;
                $create_coupon_array['updated_by'] = $user->id;

                $coupon_id = Coupons::firstOrCreate($create_coupon_array);

                //クーポンコード生成
                $coupon_code = 'CP'.rand(100000, 999999);

                Coupons::where('id', $coupon_id->id)->update([
                    'coupon_code' => $coupon_code
                ]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                echo $e;
                exit;
            }

            return redirect('/store/coupon');
        }

        return view('store.coupon.create', compact('user', 'stores'));
    }

    public function edit(Request $request)
    {
        $user = Auth::guard('store_user')->user(); //ユーザー情報
        $stores = Stores::select()->where('company_id', $user->company_id)->get(); //stores情報
        $request = $request->all();

        if(!isset($request['ci'])) {
            abort(404);
        }

        $coupon_id = $request['ci'];
        $coupon_data = Coupons::where('id', $coupon_id)->where('company_id', $user->company_id)->first(); //クーポン情報

        if(!$coupon_data) {
            abort(404);
        }

        $coupon_data->expire_start_date = date('Y-m-d H:i', strtotime($coupon_data->expire_start_date));
        $coupon_data->expire_end_date = date('Y-m-d H:i', strtotime($coupon_data->expire_end_date));

        $old_img_paths = [
            $coupon_data->img_url,
            $coupon_data->img_url_2,
            $coupon_data->img_url_3,
            $coupon_data->img_url_4,
            $coupon_data->img_url_5,
        ];

        if (isset($request['store_name']) && isset($request['p_type']) && $request['p_type'] == 'edit') {
            $validated_data = Validator::make($request, [
                'store_name' => 'required',
                'coupon_name' => 'required|max:190',
                'price' => 'required|numeric|min:1',
                'store_pay_price' => 'required|numeric|min:1',
                'cource_time' => 'required',
                'cource_start' => 'required',
                'detail' => 'required',
                'start_date' => 'required',
                'end_date' => 'required'
            ], [
                'store_name.required' => '店舗を選択してください。',
                'coupon_name.required' => 'クーポン名を入力してください。',
                'price.required' => 'クーポン金額を入力してください。',
                'price.numeric' => 'クーポン定価は数値で入力してください。',
                'price.min' => 'クーポン定価は1円以上にしてください。',
                'store_pay_price.required' => '店舗支払金額は0円以上にしてください。',
                'store_pay_price.numeric' => '店舗支払金額は数値で入力してください。',
                'store_pay_price.min' => '店舗支払金額は0円以上にしてください。',
                'cource_time.required' => 'コース時間を入力してください。',
                'cource_start.required' => 'コース開始時間を入力してください。',
                'detail.required' => '説明を入力してください。',
                'start_date.required' => '有効期限を入力してください。',
                'end_date.required' => '有効期限を入力してください。',
            ]);

            if ($validated_data->fails()) {
                return redirect()->back()
                    ->withErrors($validated_data)
                    ->withInput();
            }

            //date covert
            $start_date = str_replace('T', ' ', $request['start_date']).':00';
            $end_date = str_replace('T', ' ', $request['end_date']).':59';
            $cource_start = str_replace('T', ' ', $request['cource_start']).':00';

            $date = new \DateTime($cource_start);
            $day_of_the_week = $date->format('w');

            //画像
            $image_keys = ['images', 'images_2', 'images_3', 'images_4', 'images_5'];
            $img_paths = [];
            $img_index = 0;
            $coupon_image_array = array();

            foreach ($image_keys as $key) {
                if (isset($request[$key]) && $request[$key]) {
                    $file = $request[$key];
                    $fileSize = $file->getSize();
                    $maxSize = 1000 * 1024 * 1024; // 一旦1GB制限

                    if ($fileSize > $maxSize) {
                        return redirect()->route('store.coupon.create')
                            ->withErrors(["{$key}" => "ファイルサイズが1MBを超えています。"])
                            ->withInput();
                    }

                    if (strpos($file->getMimeType(), 'image') !== false) {
                        $img_path = $file->store('coupon_image', 'pub_images');

                        // 画像がある順に詰める
                        if ($img_index == 0) {
                            $field_name = 'img_url';
                        } else {
                            $field_name = 'img_url_' . ($img_index + 1);
                        }
                        $coupon_image_array[$field_name] = $img_path;
                        $img_index++;
                    }
                }
            }

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
                    $img_path = $img->store('coupon_image','pub_images');
                } else {
                    $img_path = $coupon_data->img_url;
                }
            } else {
                $img_path = $coupon_data->img_url;
            }
            */

            //サービス料算出
            $service_price = round($request['store_pay_price'] * 0.15);
            $original_service_price = round($request['price'] * 0.15);

            //割引率計算
            if ($request['store_pay_price'] < $request['price']) {
                $discount_rate = round((1 - ($request['store_pay_price'] / $request['price'])) * 100);
            } else {
                $discount_rate = 0;
            }

            //クーポン編集
            DB::beginTransaction();
            try {
                $create_coupon_array = array();

                //更新画像が1枚でもセットされていた場合画像リセット
                if (count($coupon_image_array) > 0) {
                    $create_coupon_array['img_url'] = null;
                    $create_coupon_array['img_url_2'] = null;
                    $create_coupon_array['img_url_3'] = null;
                    $create_coupon_array['img_url_4'] = null;
                    $create_coupon_array['img_url_5'] = null;

                    foreach ($old_img_paths as $path) {
                        if ($path && Storage::disk('pub_images')->exists($path)) {
                            Storage::disk('pub_images')->delete($path);
                        }
                    }
                }

                $create_coupon_array['coupon_name'] = $request['coupon_name'];
                $create_coupon_array['company_id'] = $user->company_id;
                $create_coupon_array['store_id'] = $request['store_name'];
                $create_coupon_array['price'] = $request['price'];
                //$create_coupon_array['discount_price'] = $request['discount_price'];
                //$create_coupon_array['discount_type'] = $request['discount_type'];
                $create_coupon_array['service_price'] = $service_price;
                $create_coupon_array['original_service_price'] = $original_service_price;
                $create_coupon_array['store_pay_price'] = $request['store_pay_price'];
                $create_coupon_array['discount_rate'] = $discount_rate;
                $create_coupon_array['cource_time'] = $request['cource_time'];
                $create_coupon_array['cource_start'] = $cource_start;
                $create_coupon_array['day_of_the_week'] = $day_of_the_week;
                $create_coupon_array['detail'] = $request['detail'];
                $create_coupon_array['expire_start_date'] = $start_date;
                $create_coupon_array['expire_end_date'] = $end_date;
                foreach ($coupon_image_array as $field_name => $coupon_image_path) {
                    $create_coupon_array[$field_name] = $coupon_image_path;
                }
                $create_coupon_array['created_by'] = $user->id;
                $create_coupon_array['updated_by'] = $user->id;

                Coupons::where('id', $coupon_data->id)->update($create_coupon_array);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                echo $e;
                exit;
            }

            return redirect('/store/coupon');
        }

        return view('store.coupon.edit', compact('user', 'stores', 'coupon_data'));
    }

    public function delete(Request $request)
    {
        $user = Auth::guard('store_user')->user(); //ユーザー情報
        $coupons = Coupons::select('coupons.*','stores.store_name')->join('stores', 'coupons.store_id', '=', 'stores.id')->where('coupons.company_id', $user->company_id)->get(); //クーポン情報
        $stores = Stores::select()->where('company_id', $user->company_id)->get(); //stores情報
        $request = $request->all();

        $coupon_id = $request['ci'];
        $coupon_data = Coupons::where('id', $coupon_id)->first(); //クーポン情報

        if ($coupon_data) {
            //一旦物理削除
            Coupons::where('id', $coupon_id)->delete();

            if ($coupon_data->img_url && File::exists('assets/images/'. $coupon_data->img_url)) {
                File::delete('assets/images/'. $coupon_data->img_url);
            }
        }

        return redirect('/store/coupon');
    }
}
