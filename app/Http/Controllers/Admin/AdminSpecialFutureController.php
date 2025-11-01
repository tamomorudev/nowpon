<?php

namespace App\Http\Controllers\Admin;
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
use App\Models\SpecialFutures;

class AdminSpecialFutureController extends Controller
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
        $user = Auth::guard('admin_user')->user(); //ユーザー情報
        $special_futures = SpecialFutures::select()->where('delete_flg', 0)->orderBy('created_at', 'DESC')->get(); //特集情報
        $stores = Stores::select()->get(); //stores情報
        return view('admin.special_future.index', compact('user', 'stores', 'special_futures'));
    }

    public function create(Request $request)
    {
        $user = Auth::guard('admin_user')->user(); //ユーザー情報
        $stores = Stores::select()->get(); //stores情報
        $request = $request->all();

        if (isset($request['name'])) {
            $validated_data = Validator::make($request, [
                'name' => 'required|max:190',
                'outline' => 'required|max:190',
                'detail' => 'required',
                'start_date' => 'required',
                'end_date' => 'required'
            ]);

            if ($validated_data->fails()) {
                return redirect()->route('admin.special_future.create')
                    ->withErrors($validated_data)
                    ->withInput();
            }

            //date covert
            $start_date = str_replace('T', ' ', $request['start_date']).':00';
            $end_date = str_replace('T', ' ', $request['end_date']).':59';
            if (isset($request['coupon_date_start']) && isset($request['coupon_date_end'])) {
                $coupon_date_start = str_replace('T', ' ', $request['coupon_date_start']).':00';
                $coupon_date_end = str_replace('T', ' ', $request['coupon_date_end']).':59';
            } else {
                $coupon_date_start = null;
                $coupon_date_end = null;
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
                    $img_path = $img->store('special_future_image','pub_images');
                } else {
                    $img_path = '';
                }
            } else {
                $img_path = '';
            }

            //クーポン登録
            DB::beginTransaction();
            try {
                $create_special_future_array = array();
                $create_special_future_array['name'] = $request['name'];
                $create_special_future_array['outline'] = $request['outline'];
                $create_special_future_array['detail'] = $request['detail'];
                $create_special_future_array['start_date'] = $start_date;
                $create_special_future_array['end_date'] = $end_date;
                $create_special_future_array['image'] = $img_path;
                $create_special_future_array['coupon_date_start'] = $coupon_date_start;
                $create_special_future_array['coupon_date_end'] = $coupon_date_end;
                if ($request['sex'] != null) {
                    $create_special_future_array['sex'] = $request['sex'];
                } else {
                    $create_special_future_array['sex'] = 99; //all
                }
                if (isset($request['discount_rate']) && $request['discount_rate'] > 0) {
                    $create_special_future_array['discount_rate'] = $request['discount_rate'];
                }
                if ($request['genre'] != null) {
                    $create_special_future_array['genre'] = json_encode([$request['genre']]);
                } else {
                    $create_special_future_array['genre'] = json_encode([99]); //all
                }
                if (isset($request['day_of_week']) && $request['day_of_week']) {
                    $create_special_future_array['coupon_date_end'] = json_encode($request['day_of_week']);
                }
                $create_special_future_array['created_by'] = $user->id;
                $create_special_future_array['updated_by'] = $user->id;

                SpecialFutures::firstOrCreate($create_special_future_array);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                echo $e;
                exit;
            }

            return redirect('/admin/special_future');
        }

        return view('admin.special_future.create', compact('user', 'stores'));
    }

    public function edit(Request $request)
    {
        $user = Auth::guard('admin_user')->user(); //ユーザー情報
        $stores = Stores::select()->where('company_id', $user->company_id)->get(); //stores情報
        $request = $request->all();

        if(!isset($request['id'])) {
            abort(404);
        }

        $special_future_id = $request['id'];
        $special_future_data = SpecialFutures::where('id', $special_future_id)->first();

        if(!$special_future_data) {
            abort(404);
        }

        $special_future_data->start_date = date('Y-m-d H:i', strtotime($special_future_data->start_date));
        $special_future_data->end_date = date('Y-m-d H:i', strtotime($special_future_data->end_date));

        if (isset($request['name']) && isset($request['p_type']) && $request['p_type'] == 'edit') {
            $validated_data = Validator::make($request, [
                'name' => 'required|max:190',
                'detail' => 'required',
                'start_date' => 'required',
                'end_date' => 'required'
            ]);

            if ($validated_data->fails()) {
                return redirect()->back()
                    ->withErrors($validated_data)
                    ->withInput();
            }

            //date covert
            $start_date = str_replace('T', ' ', $request['start_date']).':00';
            $end_date = str_replace('T', ' ', $request['end_date']).':59';
            if (isset($request['coupon_date_start']) && isset($request['coupon_date_end'])) {
                $coupon_date_start = str_replace('T', ' ', $request['coupon_date_start']).':00';
                $coupon_date_end = str_replace('T', ' ', $request['coupon_date_end']).':59';
            } else {
                $coupon_date_start = null;
                $coupon_date_end = null;
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
                    $img_path = $img->store('special_future_image','pub_images');
                } else {
                    $img_path = $special_future_data->image;
                }
            } else {
                $img_path = $special_future_data->image;
            }

            //編集
            DB::beginTransaction();
            try {
                $create_special_future_array = array();
                $create_special_future_array['name'] = $request['name'];
                $create_special_future_array['outline'] = $request['outline'];
                $create_special_future_array['detail'] = $request['detail'];
                $create_special_future_array['start_date'] = $start_date;
                $create_special_future_array['end_date'] = $end_date;
                $create_special_future_array['coupon_date_start'] = $coupon_date_start;
                $create_special_future_array['coupon_date_end'] = $coupon_date_end;
                $create_special_future_array['image'] = $img_path;
                if ($request['sex'] != null) {
                    $create_special_future_array['sex'] = $request['sex'];
                } else {
                    $create_special_future_array['sex'] = 99; //all
                }
                if (isset($request['discount_rate']) && $request['discount_rate'] > 0) {
                    $create_special_future_array['discount_rate'] = $request['discount_rate'];
                }
                if ($request['genre'] != null) {
                    $create_special_future_array['genre'] = json_encode([$request['genre']]);
                } else {
                    $create_special_future_array['genre'] = json_encode([99]); //all
                }
                if (isset($request['day_of_week']) && $request['day_of_week']) {
                    $create_special_future_array['coupon_date_end'] = json_encode($request['day_of_week']);
                }
                $create_special_future_array['updated_by'] = $user->id;

                SpecialFutures::where('id', $special_future_data->id)->update($create_special_future_array);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                echo $e;
                exit;
            }

            return redirect('/admin/special_future');
        }

        return view('admin.special_future.edit', compact('user', 'stores', 'special_future_data'));
    }

    public function delete(Request $request)
    {
        $user = Auth::guard('admin_user')->user(); //ユーザー情報
        $request = $request->all();

        $special_future_id = $request['id'];
        $special_future_data = SpecialFutures::where('id', $special_future_id)->first(); //クーポン情報

        if ($special_future_data) {
            //一旦物理削除
            SpecialFutures::where('id', $special_future_id)->delete();

            if ($special_future_data->image && File::exists('assets/images/'. $special_future_data->image)) {
                File::delete('assets/images/'. $special_future_data->image);
            }
        }

        return redirect('/admin/special_future');
    }
}
