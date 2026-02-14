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
        $coupons = Coupons::select('coupons.*','stores.store_name')->join('stores', 'coupons.store_id', '=', 'stores.id')->orderBy('created_at', 'DESC')->paginate(50); //クーポン情報
        $stores = Stores::select()->get(); //stores情報
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

        return view('admin.coupon.detail', compact('user', 'stores', 'coupon_data'));
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
