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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use App\Models\Stores;
use App\Models\StoreServices;
use App\Models\StoreUser;
use App\Models\Coupons;

class AdminMasterController extends Controller
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
    public function images()
    {
        $user = Auth::user(); //ユーザー情報
        $stores = Stores::where('image', '!=', '')->where('image', '!=', null)->select()->get(); //stores情報
        $coupons = Coupons::where('img_url', '!=', '')->where('img_url', '!=', null)->select()->get();

        return view('admin.master.images', compact('user', 'stores', 'coupons'));
    }

    public function images_delete(Request $request)
    {
        $user = Auth::user(); //ユーザー情報
        $stores = Stores::select()->get(); //stores情報
        $coupons = Coupons::select()->get();
        $request = $request->all();

        if (isset($request['d_type']) && $request['d_type'] == 'store') {
            $store_data = Stores::where('id', $request['id'])->first();

            if ($store_data) {
                $update['image'] = '';
                $update['updated_by'] = $user->id;
                Stores::where('id', $store_data->id)->update($update);
    
                if ($store_data->image && File::exists('assets/images/'. $store_data->image)) {
                    File::delete('assets/images/'. $store_data->image);
                }
            }
        }
        if (isset($request['d_type']) && $request['d_type'] == 'coupon') {
            $coupon_data = Coupons::where('id', $request['id'])->first();

            if ($coupon_data) {
                $update['img_url'] = '';
                $update['updated_by'] = $user->id;
                Coupons::where('id', $coupon_data->id)->update($update);
    
                if ($coupon_data->img_url && File::exists('assets/images/'. $coupon_data->img_url)) {
                    File::delete('assets/images/'. $coupon_data->img_url);
                }
            }
        }

        return redirect('/admin/images');

    }

}
