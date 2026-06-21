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
use App\Models\PurchaseCoupos;

class AdminCouponController extends Controller
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
    public function index(Request $request)
    {
        $user = Auth::guard('admin_user')->user(); //ユーザー情報
        //$coupons = Coupons::select('coupons.*','stores.store_name')->join('stores', 'coupons.store_id', '=', 'stores.id')->orderBy('created_at', 'DESC')->paginate(50); //クーポン情報
        $stores = Stores::select()->get(); //stores情報

        $coupon_name = $request->coupon_name;
        $coupon_code = $request->coupon_code;
        $status = $request->status;

        $date = date('Y-m-d H:i:s');

        $coupons = Coupons::select('coupons.*','stores.store_name')->join('stores', 'coupons.store_id', '=', 'stores.id');

        if ($coupon_name) { $coupons->where('coupon_name', 'like', "%{$coupon_name}%"); }
        if ($coupon_code) { $coupons->where('coupon_code', 'like', "%{$coupon_code}%"); }
        if ($status) {
            if ($status === 'prepare') { 
                $coupons->where('expire_start_date', '>', $date);
            } else if ($status === 'active') {
                $coupons->where('expire_start_date', '<=', $date)->where('expire_end_date', '>=', $date);
            } else if ($status === 'expired') { 
                $coupons->where('expire_end_date', '<', $date);
            } else if ($status === 'selled') { 
                $coupons->where('coupons.status', 1);
            } else if ($status === 'cancelled') { 
                $coupons->where('coupons.status', 2);
            }
        }

        $coupons = $coupons->orderBy('coupons.created_at', 'DESC')->paginate(50);


        return view('admin.coupon.index', compact('user', 'stores', 'coupons'));
    }

    public function detail(Request $request)
    {
        $user = Auth::guard('admin_user')->user(); //ユーザー情報
        $stores = Stores::select()->get(); //stores情報

        if(!isset($request['ci'])) {
            abort(404);
        }

        $coupon_id = $request['ci'];
        $coupon_data = Coupons::select('coupons.*', 'stores.store_name')->join('stores', 'coupons.store_id', '=', 'stores.id')->where('coupons.id', $coupon_id)->first(); //クーポン情報

        if(!$coupon_data) {
            abort(404);
        }

        //購入データ
        $purchase_coupon_data = PurchaseCoupos::select()
            ->join('users', 'purchase_coupons.purchase_user_id', '=', 'users.id')
            ->where('coupon_id', $coupon_data->id)
            ->orderBy('purchase_coupons.created_at', 'ASC')
            ->get();

        return view('admin.coupon.detail', compact('user', 'stores', 'coupon_data', 'purchase_coupon_data'));
    }

    public function delete(Request $request)
    {
        $user = Auth::guard('admin_user')->user(); //ユーザー情報
        $request = $request->all();

        $coupon_id = $request['ci'];
        $coupon_data = Coupons::where('id', $coupon_id)->first(); //クーポン情報

        if ($coupon_data) {
            //一旦物理削除
            Coupons::where('id', $coupon_id)->delete();

            if ($coupon_data->img_url && File::exists('assets/images/'. $coupon_data->img_url)) {
                File::delete('assets/images/'. $coupon_data->img_url);
            }
            if ($coupon_data->img_url && File::exists('assets/images/'. $coupon_data->img_url_2)) {
                File::delete('assets/images/'. $coupon_data->img_url_2);
            }
            if ($coupon_data->img_url && File::exists('assets/images/'. $coupon_data->img_url_3)) {
                File::delete('assets/images/'. $coupon_data->img_url_3);
            }
            if ($coupon_data->img_url && File::exists('assets/images/'. $coupon_data->img_url_4)) {
                File::delete('assets/images/'. $coupon_data->img_url_4);
            }
            if ($coupon_data->img_url && File::exists('assets/images/'. $coupon_data->img_url_5)) {
                File::delete('assets/images/'. $coupon_data->img_url_5);
            }
        }

        return redirect('/admin/coupon');
    }
}
