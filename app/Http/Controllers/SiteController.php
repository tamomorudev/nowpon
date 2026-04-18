<?php

namespace App\Http\Controllers;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
use App\Models\PurchaseCoupos;
use App\Models\StripeLogs;
use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\PaymentIntent;

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
                ->where('coupons.status', 0)
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

        return view('site.checkout', compact('user', 'coupon'));
    }

    public function charge(Request $request)
    {
        $user = Auth::guard('web')->user();
        $coupon_code = $request->cid;

        if (!$coupon_code) {
            return response()->json(['success' => false, 'message' => 'クーポンが見つかりません'], 422);
        }

        $date = date('Y-m-d H:i:s');
        $coupon = Coupons::select('coupons.*', 'stores.store_name')
            ->join('stores', 'coupons.store_id', '=', 'stores.id')
            ->where('coupon_code', $coupon_code)
            ->where('expire_start_date', '<=', $date)
            ->where('expire_end_date', '>=', $date)
            ->where('coupons.status', 0)
            ->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'このクーポンは購入することができません'], 422);
        }

        //支払い金額
        if ($coupon->discount_rate > 0) {
            $amount = round($coupon->store_pay_price) + $coupon->service_price;
        } else {
            $amount = $coupon->price + $coupon->original_service_price;
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        //決済ログ初期値
        $log_data = [
            'coupon_code'       => $coupon_code,
            'user_id'           => $user->id,
            'payment_method_id' => $request->payment_method_id,
            'amount'            => $amount,
            'currency'          => 'jpy',
            'status'            => 'failed', //一旦failed
            'error_code'        => null,
            'error_message'     => null,
            'decline_code'      => null,
            'stripe_response'   => null,
        ];

        try {
            if ($request->payment_intent_id) {
                // 3DS認証済み
                $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);
                //$paymentIntent = $paymentIntent->confirm();
                //$paymentIntent = PaymentIntent::retrieve($paymentIntent->id);
            } else {
                /*
                //新規PaymentIntentを作成
                $paymentIntent = PaymentIntent::create([
                    'amount'              => $amount,
                    'currency'            => 'jpy',
                    'payment_method'      => $request->payment_method_id,
                    'confirmation_method' => 'manual',
                    'confirm'             => true,
                    'return_url'          => route('checkout') . '?cid=' . $coupon_code . '&3ds_error=1',
                ]);*/

                //新規PaymentIntentを作成
                $paymentIntent = PaymentIntent::create([
                    'amount'              => $amount,
                    'currency'            => 'jpy',
                    'payment_method'      => $request->payment_method_id,
                    'confirmation_method' => 'manual',
                    'confirm'             => true,
                    'return_url'          => route('checkout.3ds_callback') . '?cid=' . $coupon_code,
                ]);
            }

            //レスポンスログ
            $log_data['payment_intent_id'] = $paymentIntent->id;
            $log_data['status']            = $paymentIntent->status;

            $intentArray = $paymentIntent->toArray();
            $log_data['stripe_response']   = json_encode([
                'card_brand' => $intentArray['charges']['data'][0]['payment_method_details']['card']['brand'] ?? null,
                'card_last4' => $intentArray['charges']['data'][0]['payment_method_details']['card']['last4'] ?? null,
            ]);

            //3Dセキュア認証
            if ($paymentIntent->status === 'requires_action') {
                StripeLogs::create($log_data);
                return response()->json([
                    'success'        => false,
                    'requires_action' => true,
                    'payment_intent_id'         => $paymentIntent->id,
                    'client_secret'  => $paymentIntent->client_secret,
                ]);
            }

            //成功
            if ($paymentIntent->status === 'succeeded') {
                StripeLogs::create($log_data);
                return response()->json([
                    'success'    => true,
                    'payment_id' => $paymentIntent->id,
                    'redirect'   => route('checkout.complete') . '?cid=' . $coupon_code . '&payment_id=' . $paymentIntent->id,
                ]);
            }

            $log_data['error_message'] = 'exeption error: ' . $paymentIntent->status;
            StripeLogs::create($log_data);
            return response()->json(['success' => false, 'message' => '決済処理に失敗しました'], 500);

        } catch (\Stripe\Exception\CardException $e) {
            //カードエラー
            $log_data['error_code']    = $e->getStripeCode();
            $log_data['error_message'] = $e->getMessage();
            $log_data['decline_code']  = $e->getDeclineCode();

            //$log_data['stripe_response'] = json_encode($e->getJsonBody()) ?? null;
            $log_data['stripe_response'] = null;
            StripeLogs::create($log_data);

            return response()->json([
                'success' => false,
                'message' => $this->getCardErrorMessage($e->getDeclineCode()), //エラーメッセージ変換
            ], 422);

        } catch (\Exception $e) {
            // その他エラー
            $log_data['error_message'] = $e->getMessage();
            StripeLogs::create($log_data);

            //return response()->json(['success' => false, 'message' => '決済処理に失敗しました。時間をおいて再度お試しください。'], 500);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function getCardErrorMessage(?string $declineCode): string
    {
        $messages = [
            'insufficient_funds'      => '残高不足です。別のカードをお試しください。',
            'card_declined'           => 'カードが拒否されました。別のカードをお試しください。',
            'expired_card'            => 'カードの有効期限が切れています。',
            'incorrect_cvc'           => 'セキュリティコードが正しくありません。',
            'incorrect_number'        => 'カード番号が正しくありません。',
            'stolen_card'             => 'このカードは使用できません。',
            'lost_card'               => 'このカードは使用できません。',
            'fraudulent'              => 'このカードは使用できません。',
            'do_not_honor'            => 'カードが拒否されました。カード会社にお問い合わせください。',
        ];

        return $messages[$declineCode] ?? 'カードが拒否されました。別のカードをお試しください。';
    }

    public function threeDsCallback(Request $request)
    {
        $coupon_code       = $request->cid;
        $payment_intent_id = $request->payment_intent;

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $paymentIntent = \Stripe\PaymentIntent::retrieve($payment_intent_id);

            //requires_confirmationの場合は確定させる
            if ($paymentIntent->status === 'requires_confirmation') {
                $paymentIntent->confirm([
                    'return_url' => route('checkout.3ds_callback') . '?cid=' . $coupon_code,
                ]);
                $paymentIntent = \Stripe\PaymentIntent::retrieve($payment_intent_id);
            }

            if ($paymentIntent->status === 'succeeded') {
                return redirect()->route('checkout.complete', [
                    'cid'        => $coupon_code,
                    'payment_id' => $payment_intent_id,
                ]);
            } else {
                return redirect()->route('checkout', [
                    'cid'       => $coupon_code,
                    '3ds_error' => 1,
                ]);
            }

        } catch (\Exception $e) {
            Log::error($coupon_code. ': 3ds_callback error');
            Log::error($e->getMessage());

            return redirect()->route('checkout', [
                'cid'       => $coupon_code,
                '3ds_error' => 1,
            ]);
        }
    }

    public function checkoutComplete(Request $request)
    {
        $date = date('Y-m-d H:i:s');
        //ユーザー情報
        $user = Auth::guard('web')->user();

        //payment_idチェック
        if (!$request->payment_id) {
            Log::error($user->id.' : credit complete not paymentid error');
            abort(500);
        }
        Stripe::setApiKey(config('services.stripe.secret'));
        try {
            $paymentIntent = \Stripe\PaymentIntent::retrieve($request->payment_id);
            if ($paymentIntent->status !== 'succeeded') {
                Log::error($user->id.' : credit complete not success error');
                abort(500);
            }
        } catch (\Exception $e) {
            Log::error('credit complete stripe error:'.$user->id);
            Log::error($e->getMessage());
            abort(500);
        }

        //クーポン情報
        $coupon_code = !empty($request->input('cid')) ? $request->input('cid') : "";

        if (!$coupon_code) {
            Log::error($user->id. ':credit complete not coupon code error');
            abort(500);
        } else {
            $coupon = Coupons::select(
                    'coupons.*',
                    'stores.store_name', 'stores.phone_number', 'stores.genre', 'stores.url',
                    'stores.line', 'stores.station', 'stores.transportation', 'stores.time',
                    'stores.line_2', 'stores.station_2', 'stores.transportation_2', 'stores.time_2',
                    'zipcodes.city')
                ->join('stores', 'coupons.store_id', '=', 'stores.id')
                ->join('zipcodes', 'stores.postal_code', '=', 'zipcodes.zipcode')
                ->where('coupon_code', $coupon_code)
                ->where('expire_start_date', '<=', $date)
                ->where('expire_end_date', '>=', $date)
                ->first();

            if (!$coupon) {
                Log::error($user->id. ':credit complete not coupon data error');
                abort(500);
            }

            //クーポンのステータスが購入可能でなかった場合エラー(直前でバッティングなど)
            if ($coupon->status != 0) {
                Log::error($user->id. ':credit complete coupon status error');
                abort(500);
            }

            //購入金額
            if ($coupon->discount_rate > 0) {
                $payment_amount = round($coupon->store_pay_price) + $coupon->service_price;
            } else {
                $payment_amount = $coupon->price + $coupon->original_service_price;
            }

            //購入
            DB::beginTransaction();
            try {
                //購入コード生成
                $purchase_code = 'PU'.rand(1000000, 9999999);

                $purchase_coupon_array = array();
                $purchase_coupon_array['purchase_code'] = $purchase_code;
                $purchase_coupon_array['coupon_id'] = $coupon->id;
                $purchase_coupon_array['coupon_code'] = $coupon->coupon_code;
                $purchase_coupon_array['company_id'] = $coupon->company_id;
                $purchase_coupon_array['store_id'] = $coupon->store_id;
                $purchase_coupon_array['purchase_user_id'] = $user->id;
                $purchase_coupon_array['purchase_date'] = date('Y-m-d H:i:s');
                $purchase_coupon_array['status'] = 0;
                //$purchase_coupon_array['payment_id'] = '';
                $purchase_coupon_array['payment_id'] = $request->payment_id ?? '';
                $purchase_coupon_array['payment_amount'] = $payment_amount;
                $purchase_coupon_array['cancel_flg'] = 0;
                $purchase_coupon_array['created_by'] = $user->id;
                $purchase_coupon_array['updated_by'] = $user->id;

                $purchase_id = PurchaseCoupos::create($purchase_coupon_array);

                Coupons::where('id', $coupon->id)->update([
                    'status' => 1 //購入済み
                ]);

                DB::commit();
            } catch (\Exception $e) {
                Log::error($user->id. ':credit complete registation error');
                DB::rollback();
                abort(500);
            }
        }
        return view('site.checkout_complete', compact('user', 'coupon'));
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
                    ->where('coupons.status', 0)
                    ->orderBy('created_at', 'DESC')
                    ->get();
            }
        } elseif ($isSearch) {
            // ▼ 詳細検索（都道府県・路線・駅・キーワード）
            $query = Coupons::select('coupons.*', 'stores.store_name', 'stores.genre', 'stores.station', 'stores.transportation', 'stores.time')
                ->join('stores', 'coupons.store_id', '=', 'stores.id')
                ->join('stations', 'stores.postal_code', '=', 'postal')
                ->where('coupons.status', 0)
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
                    ->where('coupons.status', 0)
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
                ->where('coupons.status', 0)
                ->where('coupons.expire_start_date', '<=', $date)
                ->where('coupons.expire_end_date', '>=', $date);

                // 特集に曜日の指定がある場合
                $weekdays = json_decode($special_futures->day_of_the_weeks, true);
                if (!empty($weekdays)) {
                    $q->whereIn('coupons.day_of_the_week', $weekdays);
                }

                // 特集にカテゴリ（ジャンル）の指定がある場合
                $rawGenre = $special_futures->genre;
                $genres = json_decode($rawGenre, true);
                if (is_array($genres)) {
                    $genreList = $genres;
                } elseif ($rawGenre !== null && $rawGenre !== '') {
                    $genreList = [$rawGenre];
                } else {
                    $genreList = [];
                }
                $genreList = array_values(array_filter($genreList, function ($genre) {return $genre !== null && $genre !== '' && $genre != 99;}));
                if ($genreList) {
                    $q->whereIn('stores.genre', $genreList);
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

        //idなしは404
        if (!$coupon_code) {
            abort(404);
        } else {
            $coupon = Coupons::select('coupons.*', 'stores.store_name', 'stores.genre', 'stores.station', 'stores.transportation', 'stores.time', 'zipcodes.city')
                ->join('stores', 'coupons.store_id', '=', 'stores.id')
                ->join('zipcodes', 'stores.postal_code', '=', 'zipcodes.zipcode')
                ->where('coupon_code', $coupon_code)
                ->where('expire_start_date', '<=', $date)
                ->where('expire_end_date', '>=', $date)
                ->where('coupons.status', 0)
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
            ->where('coupons.status', 0)
            ->inRandomOrder()
            ->limit(4)->get();

        foreach ($same_area_coupons as $same_area_coupon) {
            $same_area_coupon->format_cource_start = Carbon::parse($same_area_coupon->cource_start_time)->format('Y年n月j日 G時i分～');
        }

        return view('site.coupondetail', compact('user', 'coupon', 'same_area_coupons'));
    }


    public function purchaseHistory(Request $request)
    {
        $category = 'hair';
        return view('site.purchase_history', compact('category'));
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
