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
        $user = Auth::user(); //ユーザー情報
        $coupons = Coupons::select('coupons.*','stores.store_name')->join('stores', 'coupons.store_id', '=', 'stores.id')->where('coupons.company_id', $user->company_id)->get(); //クーポン情報
        $stores = Stores::select()->where('company_id', $user->company_id)->get(); //stores情報
        return view('store.coupon.index', compact('user', 'stores', 'coupons'));
    }

    public function create(Request $request)
    {
        $user = Auth::user(); //ユーザー情報
        $stores = Stores::select()->where('company_id', $user->company_id)->get(); //stores情報
        $request = $request->all();

        if (isset($request['store_name'])) {
            $validated_data = Validator::make($request, [
                'store_name' => 'required',
                'coupon_name' => 'required|max:190',
                'price' => 'required',
                'discount_price' => 'required',
                'detail' => 'required',
                'start_date' => 'required',
                'end_date' => 'required'
            ]);
            
            if ($validated_data->fails()) {
                $error = true;
                return view('store.coupon.create', compact('error', 'stores'));
            }

            //date covert
            $start_date = str_replace('T', ' ', $request['start_date']).':00';
            $end_date = str_replace('T', ' ', $request['end_date']).':59';

            //クーポン登録
            DB::beginTransaction();
            try {
                $create_coupon_array = array();
                $create_coupon_array['coupon_name'] = $request['coupon_name'];
                $create_coupon_array['company_id'] = $user->company_id;
                $create_coupon_array['store_id'] = $request['store_name'];
                $create_coupon_array['price'] = $request['price'];
                $create_coupon_array['discount_price'] = $request['discount_price'];
                $create_coupon_array['discount_type'] = $request['discount_type'];
                $create_coupon_array['detail'] = $request['detail'];
                $create_coupon_array['expire_start_date'] = $start_date;
                $create_coupon_array['expire_end_date'] = $end_date;
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
}
