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
use App\Models\Zipcodes;
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
        $date = date('Y-m-d H:i:s');
        $list_coupons = array();
        $special_futures = array();

        // 検索経由かどうか（prefecture or keyword）
        $isSearch = $request->filled('prefecture') || $request->filled('keyword');

        //マイエリアクーポン
        if (isset($request['search']) && $request['search'] == 'area') {
            $user = Auth::guard('web')->user();
            if(!isset($user)) {
                return redirect('/login');
            }
            $user_zipcode = Zipcodes::select()->where('zipcode', $user->postal_code)->first(); //ユーザー市町村情報
            if ($user_zipcode) {
                //ユーザー市町村と一致するものだけ
                $list_coupons = Coupons::select('coupons.*', 'stores.store_name', 'stores.genre', 'stores.station', 'stores.transportation', 'stores.time', 'zipcodes.city')
                    ->join('stores', 'coupons.store_id', '=', 'stores.id')
                    ->join('zipcodes', 'stores.postal_code', '=', 'zipcodes.zipcode')
                    ->where('expire_start_date', '<=', $date)
                    ->where('expire_end_date', '>=', $date)
                    ->where('zipcodes.city', '=', $user_zipcode->city)
                    ->orderBy('created_at', 'DESC')
                    ->get();
            }
        } elseif ($isSearch) {
            // ▼ 詳細検索（都道府県・路線・駅・キーワード）
            $query = Coupons::select('coupons.*', 'stores.store_name', 'stores.genre', 'stores.station', 'stores.transportation', 'stores.time')
                ->join('stores', 'coupons.store_id', '=', 'stores.id')
                ->join('stations', 'stores.postal_code', '=', 'postal')
                ->where('expire_start_date', '<=', $date)
                ->where('expire_end_date', '>=', $date);
            // 都道府県
            if ($request->filled('prefecture')) {
                $query->where('stations.prefecture', config('commons.prefectures')[$request->input('prefecture')]);
            }
            // 路線
            if ($request->filled('station_line')) {
                $query->where('stations.line', $request->input('station_line'));
            }
            // 駅
            if ($request->filled('station')) {
                $query->where('stations.name', $request->input('station'));
            }
            // キーワード（クーポン名 or 店舗名あたりを対象）
            if ($request->filled('keyword')) {
                $keyword = $request->input('keyword');
                $query->where(function ($q) use ($keyword) {
                    $q->where('coupons.coupon_name', 'LIKE', '%' . $keyword . '%')->orWhere('stores.store_name', 'LIKE', '%' . $keyword . '%');
                });
            }
            $list_coupons = $query->distinct('coupons.id')->orderBy('coupons.created_at', 'DESC')->get();
        } else if (isset($request['search']) && $request['search'] == 'category') {
            if (isset($request['gid'])) {
                $list_coupons = Coupons::select(
                        'coupons.*',
                        'stores.store_name',
                        'stores.genre',
                        'stores.station',
                        'stores.transportation',
                        'stores.time',
                    )
                    ->join('stores', 'coupons.store_id', '=', 'stores.id')
                    ->where('expire_start_date', '<=', $date)
                    ->where('expire_end_date', '>=', $date)
                    ->where('stores.genre', '=', $request['gid'])
                    ->orderBy('created_at', 'DESC')
                    ->get();
            }
        } else if (isset($request['search']) && $request['search'] == 'special_futures') {
            // 特集検索の場合
            if (isset($request['id'])) {
                // まず、特集の情報を取得
                $special_futures = SpecialFutures::select()->where('id', '=', $request['id'])->first();
                // その後、クーポンとユーザ情報に合致する条件で検索
                $q = Coupons::select(
                    'coupons.*',
                    'stores.store_name',
                    'stores.genre',
                    'stores.station',
                    'stores.transportation',
                    'stores.time',
                )
                ->join('stores', 'coupons.store_id', '=', 'stores.id')
                ->where('coupons.expire_start_date', '<=', $date)
                ->where('coupons.expire_end_date', '>=', $date);

                // 特集に曜日の指定がある場合
                $weekdays = json_decode($special_futures->day_of_the_weeks, true);
                if (!empty($weekdays)) {
                    $q->whereIn('coupons.day_of_the_week', $weekdays);
                }
                // 特集にカテゴリ（ジャンル）の指定がある場合
                $genres = json_decode($special_futures->genre, true);
                if (!empty($genres)) {
                    $q->whereIn('stores.genre', $genres);
                }
                // 特集に割引率の指定がある場合
                if ($special_futures->discount_rate > 0) {
                    $q->where('coupons.discount_rate', '>=', $special_futures->discount_rate);
                }
                // 特集に開始日時の指定がある場合
                if ($special_futures->coupon_date_start > 0) {
                    $q->where('coupons.cource_time', '<=', $date);
                }
                // 特集に終了日付の指定がある場合
                if ($special_futures->coupon_date_start > 0) {
                    $q->where('coupons.cource_time', '>=', $date);
                }
                // 特集に性別の指定がある場合
                if (!empty($special_futures->sex)) {
                    // ユーザー情報をみる（仕様が決定してから）▼
                    //特集側で性別が指定されいた場合、未ログイン時に取得する特集の条件は性別がすべてのもののみ？？
                    //トップページでそもそもフィルタしているのであれば、検索側で性別を見る必要なくない？？
                }
                $list_coupons = $q->orderBy('coupons.created_at', 'DESC')->get();
            }
        }

        // 検索結果格納
        if (!empty($list_coupons)) {
            foreach ($list_coupons as $coupons) {
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
            }
        }

        return view('site.couponlist', [
            'list_coupons'      => $list_coupons,
            'special_futures'   => $special_futures,
            'searchPrefecture'  => $isSearch ? $request->input('prefecture')    : null,
            'searchStationLine' => $isSearch ? $request->input('station_line')  : null,
            'searchStation'     => $isSearch ? $request->input('station')       : null,
            'searchKeyword'     => $isSearch ? $request->input('keyword')       : null,
        ]);
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
            $coupon = Coupons::select('coupons.*', 'stores.store_name', 'stores.genre', 'stores.station', 'stores.transportation', 'stores.time', 'zipcodes.city')
                ->join('stores', 'coupons.store_id', '=', 'stores.id')
                ->join('zipcodes', 'stores.postal_code', '=', 'zipcodes.zipcode')
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


        //同じエリアクーポン
        $coupon_city = $coupon->city;
        
        $same_area_coupons = Coupons::select('coupons.*', 'stores.store_name', 'stores.genre', 'stores.station', 'stores.transportation', 'stores.time', 'zipcodes.city')
            ->join('stores', 'coupons.store_id', '=', 'stores.id')
            ->join('zipcodes', 'stores.postal_code', '=', 'zipcodes.zipcode')
            ->where('expire_start_date', '<=', $date)
            ->where('expire_end_date', '>=', $date)
            ->where('zipcodes.city', '=', $coupon_city)
            ->where('coupons.id', '!=', $coupon->id)
            ->inRandomOrder()
            ->limit(4)->get();

        foreach ($same_area_coupons as $same_area_coupon) {
            $same_area_coupon->format_cource_start = Carbon::parse($same_area_coupon->cource_start_time)->format('Y年n月j日 G時i分～');
        }

        return view('site.coupondetail', compact('user', 'coupon', 'same_area_coupons'));
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
