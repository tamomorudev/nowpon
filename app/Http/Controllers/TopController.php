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

class TopController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $date = date('Y-m-d H:i:s');
        $user = Auth::guard('web')->user(); //ユーザー情報

        //ログイン専用処理
        if ($user) {

        } else {

        }

        //クーポン情報 (新着5件)
        $new_coupons = Coupons::select
            (
                'coupons.*',
                'stores.store_name',
                'stores.genre',
                'stores.station',
                'stores.transportation',
                'stores.time'
            )
            ->join('stores', 'coupons.store_id', '=', 'stores.id')
            ->where('expire_start_date', '<=', $date)
            ->where('expire_end_date', '>=', $date)
            ->orderBy('created_at', 'DESC')
            ->limit(6)
            ->get();
        foreach ($new_coupons as $coupons) {
            //残り分数
            $end_date = Carbon::parse($coupons->expire_end_date, 'Asia/Tokyo');
            $now = Carbon::now('Asia/Tokyo');
            $remaining_minutes = $now->diffInMinutes($end_date, false);

            if ($remaining_minutes > 0) {
                //〇時間 or 〇分の形
                $remaining_hours = floor($remaining_minutes / 60);
                $remaining_minutes = $remaining_minutes % 60;

                if ($remaining_hours > 0) {
                    $coupons->remaining_minute = '残り'.$remaining_hours.'時間';
                } else {
                    $coupons->remaining_minute = '残り'.$remaining_minutes.'分';
                }
                //$coupons->remaining_minute = $remaining_minutes;
            } else {
                $coupons->remaining_minute = '終了しました';
            }

            //割引率
            $discount_rate = 0;
            if ($coupons->discount_price) {
                if ($coupons->discount_type == 1) {
                    $coupons->discount_rate = $coupons->discount_price;
                } else {
                    $discount_rate = ($coupons->discount_price / $coupons->price) * 100;
                    $coupons->discount_rate = round($discount_rate); // 四捨五入
                }
            }
        }

        //特集
        $special_futures = SpecialFutures::select()->where('start_date', '<=', $date)->where('end_date', '>=', $date)->get();
        
        return view('index', compact('user', 'new_coupons', 'special_futures'));
    }
}
