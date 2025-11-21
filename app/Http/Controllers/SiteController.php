<?php

namespace App\Http\Controllers;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;
use Illuminate\View\View;
use App\Models\Coupons;
use App\Models\Stores;
use App\Models\StoreServices;
use App\Models\SpecialFutures;
use Carbon\Carbon;

class SiteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //まだログイン不要
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function list(Request $request)
    {
        $category = 'hair';
        return view('site.list', compact('category'));
    }

    public function cart(Request $request)
    {
        $category = 'hair';
        return view('site.cart', compact('category'));
    }

    public function checkout(Request $request)
    {
        $category = 'hair';
        return view('site.checkout', compact('category'));
    }

    public function couponlist(Request $request)
    {
        $category = 'hair';
        return view('site.couponlist', compact('category'));
    }

    public function coupondetail(Request $request)
    {
        $date = date('Y-m-d H:i:s');
        $user = Auth::guard('web')->user(); //ユーザー情報

        //クーポン情報
        $coupon_code = $request->cid;

        //一旦idなしは404
        if (!$coupon_code) {
            abort(404);
        } else {
            $coupon = Coupons::join('stores', 'coupons.store_id', '=', 'stores.id')
                ->where('coupon_code', $coupon_code)
                ->where('expire_start_date', '<=', $date)
                ->where('expire_end_date', '>=', $date)
                ->first();
            if (!$coupon) {
                abort(404);
            }
        }

        //残り分数
        $end_date = Carbon::parse($coupon->expire_end_date, 'Asia/Tokyo');
        $now = Carbon::now('Asia/Tokyo');
        $remaining_minutes = $now->diffInMinutes($end_date, false);

        if ($remaining_minutes > 0) {
            //〇時間 or 〇分の形
            $remaining_hours = floor($remaining_minutes / 60);
            $remaining_minutes = $remaining_minutes % 60;

            if ($remaining_hours > 0) {
                $coupon->remaining_minute = '残り'.$remaining_hours.'時間';
            } else {
                $coupon->remaining_minute = '残り'.$remaining_minutes.'分';
            }
        } else {
            $coupon->remaining_minute = '終了しました';
        }

        //割引率
        $discount_rate = 0;
        if ($coupon->discount_price) {
            if ($coupon->discount_type == 1) {
                $coupon->discount_rate = $coupon->discount_price;
            } else {
                $discount_rate = ($coupon->discount_price / $coupon->price) * 100;
                $coupon->discount_rate = round($discount_rate); // 四捨五入
            }
        }

        //コース開始時間
        $cource_start_time = $coupon->cource_start;
        $coupon->format_cource_start = Carbon::parse($cource_start_time)->format('Y年n月j日 G時i分～');

        //画像
        $coupon_images[] = $coupon->img_url;
        $coupon_images[] = $coupon->img_url_2;
        $coupon_images[] = $coupon->img_url_3;
        $coupon_images[] = $coupon->img_url_4;
        $coupon_images[] = $coupon->img_url_5;

        $coupon->coupon_images = $coupon_images;

        return view('site.coupondetail', compact('user', 'coupon'));
    }

    public function terms(Request $request)
    {
        $category = 'hair';
        return view('site.terms', compact('category'));
    }

    public function privacypolicy(Request $request)
    {
        $category = 'hair';
        return view('site.privacypolicy', compact('category'));
    }

    public function contact(Request $request)
    {
        $category = 'hair';
        return view('site.contact', compact('category'));
    }
}
