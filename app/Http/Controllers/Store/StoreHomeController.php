<?php

namespace App\Http\Controllers\Store;
use App\Http\Controllers\Controller;
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
use Illuminate\View\View;
use App\Models\Coupons;
use App\Models\Stores;
use App\Models\StoreServices;
use App\Models\PurchaseCoupos;
use App\Models\CouponCategories;
use App\Services\ImageService;
use Carbon\Carbon;

class StoreHomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest')->except('logout');
        //$this->middleware('guest:store_user')->except('logout');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = Auth::guard('store_user')->user(); //ユーザー情報
        //$coupons = Coupons::select('coupons.*','stores.store_name')->join('stores', 'coupons.store_id', '=', 'stores.id')->where('coupons.company_id', $user->company_id)->orderBy('created_at', 'DESC')->paginate(50); //クーポン情報
        $stores = Stores::select()->where('company_id', $user->company_id)->get(); //stores情報
        $categories = CouponCategories::where('company_id', $user->company_id)->get(); //分類情報

        $period = $request->get('period', 'today');

        //期間の開始・終了を決定(customは最大1ヶ月)
        [$rangeStart, $rangeEnd] = $this->resolvePeriodRange($period, $request);

        $current_date = date('Y-m-d H:i:s');
        $today = date('Y-m-d');
        $todayStart = date('Y-m-d 00:00:00');
        $todayEnd = date('Y-m-d 23:59:59');
        $monthStart = date('Y-m-01 00:00:00');
        $monthEnd = date('Y-m-t 23:59:59');

        //発行中クーポン
        $coupon = Coupons::select('coupons.*', 'stores.store_name', 'coupon_categories.category_name')
            ->join('stores', 'coupons.store_id', '=', 'stores.id')
            ->leftjoin('coupon_categories', 'coupons.category_id', '=', 'coupon_categories.id')
            ->where('coupons.company_id', $user->company_id)
            ->where('expire_start_date', '<=', $current_date)
            ->where('expire_end_date', '>=', $current_date)
            ->where('coupons.status', 0)
            ->get();

        //category_name集計
        $sell_coupons = $coupon->countBy(function ($item) {
            return $item->category_name ?? '未分類';
        })->toArray();

        //本日の売上
        $todaySalesAmount = Coupons::where('company_id', $user->company_id)
            ->where('status', 1)
            ->whereBetween('purchase_date', [$todayStart, $todayEnd])
            ->sum('store_pay_price');

        //昨日の売上
        $yesterdayStart = date('Y-m-d 00:00:00', strtotime('-1 day'));
        $yesterdayEnd = date('Y-m-d 23:59:59', strtotime('-1 day'));

        $yesterdaySalesAmount = Coupons::where('company_id', $user->company_id)
            ->where('status', 1)
            ->whereBetween('purchase_date', [$yesterdayStart, $yesterdayEnd])
            ->sum('store_pay_price');

        //前日比(%)
        if ($yesterdaySalesAmount > 0) {
            $salesChangeRate = round((($todaySalesAmount - $yesterdaySalesAmount) / $yesterdaySalesAmount) * 100, 1);
        } elseif ($todaySalesAmount > 0) {
            $salesChangeRate = null; //前日データなし
        } else {
            $salesChangeRate = 0;
        }

        //累計売上
        $allSalesAmount = Coupons::where('company_id', $user->company_id)
            ->where('status', 1)
            ->sum('store_pay_price');

        $allSalesAemount = Coupons::where('company_id', $user->company_id)
            ->where('status', 1)
            ->get();

        //今月の累計売上
        $monthSalesAmount = Coupons::where('company_id', $user->company_id)
            ->where('status', 1)
            ->whereBetween('purchase_date', [$monthStart, $monthEnd])
            ->sum('store_pay_price');

        //本日の販売数(今日登録された全件)
        $todayCreatedCount = Coupons::where('company_id', $user->company_id)
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->count();

        //本日登録分のうち購入済み件数
        $todayPurchasedCount = Coupons::where('company_id', $user->company_id)
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->where('status', 1)
            ->count();

        //消化率
        $digestionRate = $todayCreatedCount > 0 ? round($todayPurchasedCount / $todayCreatedCount * 100, 1) : 0;

        //グラフ
        [$chartLabels, $chartSales, $chartUsage] = $this->buildChartData($user->company_id, $period, $rangeStart, $rangeEnd);
        $chartType = $period === 'today' ? 'bar' : 'line';

        //クーポン別実績(グラフと同期間で集計)
        $periodCoupons = Coupons::select('coupons.*', 'coupon_categories.category_name')
            ->leftJoin('coupon_categories', 'coupons.category_id', '=', 'coupon_categories.id')
            ->where('coupons.company_id', $user->company_id)
            ->whereBetween('coupons.created_at', [$rangeStart, $rangeEnd])
            ->get();

        $categoryStats = $periodCoupons->groupBy(function ($item) {
            return $item->category_name ?? '未分類';
        })->map(function ($items, $categoryName) use ($current_date) { // ★ $current_date を使用
            //発行数（期間内に登録された全件）
            $totalCount = $items->count();

            //販売数（status = 0）
            $sellCount = $items->where('status', 0)->count();

            //利用数（status = 1）
            $useCount = $items->where('status', 1)->count();

            //期限切れ数
            $expiredCount = $items->filter(function ($item) use ($current_date) {
                return  $item->status == 0 && !empty($item->expire_end_date) && $item->expire_end_date < $current_date;
            })->count();

            //有効枚数（未利用 かつ 期限内のもの）
            $remainingCount = $items->filter(function ($item) use ($current_date) {
                return $item->status == 0 && (empty($item->expire_end_date) || $item->expire_end_date >= $current_date);
            })->count();

            //売上合計
            $salesAmount = $items->where('status', 1)->sum('store_pay_price');

            //消化率（%）
            $digestionRate = $totalCount > 0 ? round(($useCount / $totalCount) * 100, 1) : 0;

            return [
                'category_name'   => $categoryName,
                'total_count'     => $totalCount,     //発行数
                'sell_count'      => $sellCount,      //販売数 (status=0 全体)
                'use_count'       => $useCount,       //利用数 (status=1)
                'expired_count'   => $expiredCount,   //期限切れ数
                'remaining_count' => $remainingCount, //残り枚数 (未利用かつ期限内)
                'sales_amount'    => $salesAmount,    //売上合計
                'digestion_rate'  => $digestionRate,  //消化率（%）
            ];
        })->toArray();

        return view('store.home', compact(
            'user', 
            'stores', 
            'categories', 
            'period', 
            'rangeStart',
            'rangeEnd',
            'sell_coupons',
            'todaySalesAmount',
            'salesChangeRate',
            'allSalesAmount',
            'monthSalesAmount',
            'todayCreatedCount',
            'todayPurchasedCount',
            'digestionRate',
            'chartLabels',
            'chartSales',
            'chartUsage',
            'chartType',
            'categoryStats'
        ));
    }

    //グラフ計測期間
    private function resolvePeriodRange(string $period, Request $request): array
    {
        switch ($period) {
            case '7days':
                $start = Carbon::today()->subDays(6)->startOfDay();
                $end = Carbon::today()->endOfDay();
                break;

            case '30days':
                $start = Carbon::today()->subDays(29)->startOfDay();
                $end = Carbon::today()->endOfDay();
                break;

            case 'custom':
                $start = $request->filled('start')
                    ? Carbon::parse($request->get('start'))->startOfDay()
                    : Carbon::today()->subDays(6)->startOfDay();
                $end = $request->filled('end')
                    ? Carbon::parse($request->get('end'))->endOfDay()
                    : Carbon::today()->endOfDay();

                if ($start->greaterThan($end)) {
                    [$start, $end] = [$end->copy()->startOfDay(), $start->copy()->endOfDay()];
                }

                //最大30日間(約1ヶ月)
                if ($start->diffInDays($end) > 30) {
                    $end = $start->copy()->addDays(30)->endOfDay();
                }
                break;

            case 'today':
            default:
                $start = Carbon::today()->startOfDay();
                $end = Carbon::today()->endOfDay();
                break;
        }

        return [$start, $end];
    }

    //グラフ用の配列作成
    private function buildChartData($companyId, string $period, Carbon $start, Carbon $end): array
    {
        if ($period === 'today') {
            //return $this->buildHourlyChartData($companyId, $start, $end);
            return $this->buildTodayChartData($companyId, $start, $end);
        }

        return $this->buildDailyChartData($companyId, $start, $end);
    }

    //グラフ(7日間・30日間・期間指定)
    private function buildDailyChartData($companyId, Carbon $start, Carbon $end): array
    {
        $rows = Coupons::where('company_id', $companyId)
            ->where('status', 1)
            ->whereBetween('purchase_date', [$start, $end])
            ->selectRaw('DATE(purchase_date) as ymd, SUM(store_pay_price) as sales, COUNT(*) as usage_count')
            ->groupBy('ymd')
            ->orderBy('ymd')
            ->get()
            ->keyBy('ymd');

        $labels = [];
        $sales = [];
        $usage = [];

        $cursor = $start->copy();
        while ($cursor->lte($end)) {
            $key = $cursor->format('Y-m-d');
            $labels[] = $cursor->format('n/j');
            $sales[]  = (int) ($rows[$key]->sales ?? 0);
            $usage[]  = (int) ($rows[$key]->usage_count ?? 0);
            $cursor->addDay();
        }

        return [$labels, $sales, $usage];
    }

    //グラフ(今日)←1つ分
    private function buildTodayChartData($companyId, Carbon $start, Carbon $end): array
    {
        $row = Coupons::where('company_id', $companyId)
            ->where('status', 1)
            ->whereBetween('purchase_date', [$start, $end])
            ->selectRaw('SUM(store_pay_price) as sales, COUNT(*) as usage_count')
            ->first();

        $labels = ['本日'];
        $sales = [(int) ($row->sales ?? 0)];
        $usage = [(int) ($row->usage_count ?? 0)];

        return [$labels, $sales, $usage];
    }

    //グラフ(今日)←時間別
    private function buildHourlyChartData($companyId, Carbon $start, Carbon $end): array
    {
        $rows = Coupons::where('company_id', $companyId)
            ->where('status', 1)
            ->whereBetween('purchase_date', [$start, $end])
            ->selectRaw('HOUR(purchase_date) as hour, SUM(store_pay_price) as sales, COUNT(*) as usage_count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->keyBy('hour');

        $labels = [];
        $sales = [];
        $usage = [];

        for ($h = 0; $h < 24; $h++) {
            $labels[] = sprintf('%02d:00', $h);
            $sales[]  = (int) ($rows[$h]->sales ?? 0);
            $usage[]  = (int) ($rows[$h]->usage_count ?? 0);
        }

        return [$labels, $sales, $usage];
    }
}
