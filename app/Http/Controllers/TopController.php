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
use App\Models\Information;
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
            ->where('coupons.status', 0)
            ->orderBy('created_at', 'DESC')
            ->limit(6)
            ->get();

        foreach ($new_coupons as $coupons) {
            $coupons->remaining_minute = $this->formatRemainingTime($coupons->expire_end_date);
        }

        //特集
        $special_futures = SpecialFutures::select()->where('start_date', '<=', $date)->where('end_date', '>=', $date)->get();

        // お知らせ…最新3件
        $inforamtion = Information::query()
            ->where('delete_flg', 0)            // 削除されていない
            ->where('start_date', '<=', $date)  // 公開開始日時が過去
            ->where('end_date', '>=', $date)    // 公開終了日時が未来
            ->orderBy('start_date', 'DESC')     // 新しい順
            ->limit(3)
            ->get();

        return view('index', compact('user', 'new_coupons', 'special_futures', 'inforamtion'));
    }

    /**
     * クーポン期限までの残り時間を「残り〇日〇時間〇分」形式にする。
     */
    private function formatRemainingTime($expireEndDate)
    {
        $endDate = Carbon::parse($expireEndDate, 'Asia/Tokyo');
        $now = Carbon::now('Asia/Tokyo');
        $remainingMinutes = $now->diffInMinutes($endDate, false);

        if ($remainingMinutes <= 0) {
            return '終了しました';
        }

        $remainingDays = floor($remainingMinutes / 1440);
        $remainingHours = floor(($remainingMinutes % 1440) / 60);
        $minutes = $remainingMinutes % 60;

        if ($remainingDays > 0) {
            return '残り'.$remainingDays.'日'.$remainingHours.'時間'.$minutes.'分';
        }

        if ($remainingHours > 0) {
            return '残り'.$remainingHours.'時間'.$minutes.'分';
        }

        return '残り'.$minutes.'分';
    }
}
