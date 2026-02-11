<!DOCTYPE html>
<html lang="ja">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- ▼ 詳細検索パーツ ▼ --}}
    <link rel="stylesheet" href="{{ asset('css/nowpon-search.css') }}">
    <script src="{{ asset('js/nowpon-search.js') }}" defer></script>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ナウポンTOP</title>
    <style>
        /* クーポンリストここから */
        .coupon-list {
            border: 2px solid #d4a373;
            padding: 16px;
            border-radius: 12px;
            margin: 30px 0;
            background: #fff8f0;
        }
        .coupon-item {
            display: block;
            text-decoration: none;
            color: inherit;

            padding: 12px 0;
            border-bottom: 1px solid #666666;
        }
        .coupon-item:last-child {
            border-bottom: none;
        }
        .coupon-title {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin-bottom: 6px;
            line-height: 1.6;
        }
        .coupon-price {
            font-size: 14px;
            color: #555;
        }
        .discount-rate {
            color: #e63946;
            font-weight: bold;
            margin-right: 8px;
        }
        .price-before {
            text-decoration: line-through;
            color: #999;
            margin-right: 8px;
        }
        .price-after {
            color: #e63946;
            font-weight: bold;
        }
        .new-badge {
            background-color: #ff2e00; /* 明るめの赤 */
            color: white;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 999px; /* 丸くする */
            display: inline-block;
            line-height: 1;
        }
        /* 残り時間のフェードインフェードアウト */
        .fading-text {
            animation: fadePulse 2s ease-in-out infinite;
            font-weight: bold;
        }
        @keyframes fadePulse {
            0%   { opacity: 1; }
            50%  { opacity: 0.1; }
            100% { opacity: 1; }
        }
        /* クーポンリストここまで */


        .coupon-content {
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }

        .coupon-thumb {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }

        .coupon-text {
            flex: 1;
        }

        /* ===== 特集表示 ===== */
        .special-future-detail {
            margin: 20px 0;
            padding: 16px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .special-future-header {
            margin-bottom: 12px;
        }

        .special-future-title {
            display: flex;
            align-items: center;
            font-size: 20px;
            margin: 0 0 6px;
        }

        .special-future-icon {
            font-size: 20px;
            margin-right: 8px;
        }

        .special-future-outline {
            margin: 0;
            font-size: 14px;
            color: #555;
            line-height: 1.6;
        }

        .special-future-image {
            width: 100%;
            max-width: 520px;
            height: auto;
            border-radius: 12px;
            display: block;
        }

        .special-future-image {
            width: 100%;
            max-width: 520px;
            height: auto;
            border-radius: 12px;
            display: inline-block;
        }

        .special-future-body {
            font-size: 14px;
            line-height: 1.8;
            color: #333;
        }
    </style>
</head>
<body>
<div class="container">
    @include('layouts.header')

    @if (!empty($special_futures))
        <!-- 特集検索 -->
        <div class="special-future-detail">

            <div class="special-future-header">
                <h2 class="special-future-title">
                    <span class="special-future-icon">✨</span>
                    {{ $special_futures->name }}
                </h2>

                <p class="special-future-outline">
                    {{ $special_futures->outline }}
                </p>
            </div>

            @if ($special_futures->image)
                <div class="special-future-image-wrap">
                    <img
                        src="{{ asset('/assets/images/' . $special_futures->image) }}"
                        alt="特集画像"
                        class="special-future-image"
                    >
                </div>
            @endif

            <div class="special-future-body">
                {!! $special_futures->detail !!}
            </div>

        </div>
    @else
        <!-- 通常検索 -->
        <div class="detailed-search-section">
            <div>
                <h2 style="display: flex; align-items: center; font-size: 20px;">
                    <span style="font-size: 20px; margin-right: 8px;">🔍</span>
                    検索
                </h2>
            </div>

            <form action="{{ route('couponlist') }}" method="POST">
            @csrf   <!-- ★POSTなので必須 -->
                <div class="search-panel">
                    <!-- 上段：都道府県・路線・駅 -->
                    <div class="search-tags">

                        <div class="search-select-box">
                            <label for="search_prefecture">都道府県</label>
                            <select id="search_prefecture" name="prefecture">
                                <option value="">選択してください</option>
                                @foreach(config('commons.prefectures') as $key => $prefecture)
                                    <option value="{{ $key }}"
                                            @if(isset($searchPrefecture) && (string)$searchPrefecture === (string)$key) selected @endif>
                                        {{ $prefecture }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="search-select-box">
                            <label for="search_station_line">路線</label>
                            <select id="search_station_line"
                                    name="station_line"
                                    data-initial-line="{{ $searchStationLine ?? '' }}">
                                <option value="">都道府県を選択してください</option>
                            </select>
                        </div>

                        <div class="search-select-box">
                            <label for="search_station">駅</label>
                            <select id="search_station"
                                    name="station"
                                    data-initial-station="{{ $searchStation ?? '' }}">
                                <option value="">路線を選択してください</option>
                            </select>
                        </div>

                    </div>

                    <!-- 下段：キーワード + 検索ボタン -->
                    <div class="search-keyword-row">
                        <div class="search-keyword-box">
                            <input type="text"
                                   name="keyword"
                                   placeholder="キーワードを入力"
                                   value="{{ $searchKeyword ?? '' }}" />
                        </div>
                        <button type="submit" class="search-icon-box keyword-search-button">
                            検索
                        </button>
                    </div>
                </div>
            </form>
        </div>


    @endif


    <!-- クーポンリスト -->
    <div class="coupon-list">
        @if (count($list_coupons))
            @foreach ($list_coupons as $list_coupon)
                <a href="/site/coupondetail?cid={{ urlencode($list_coupon->coupon_code) }}" class="coupon-item">
                    <div class="coupon-content">
                        @if($list_coupon->img_url)
                            <img src="{{ asset('/assets/images/'. $list_coupon->img_url) }}" alt="クーポン画像" width="80" height="80">
                        @else
                            <!--<img src="https://picsum.photos/80/80?random=1" alt="店舗画像" class="coupon-thumb" />-->
                        @endif
                        <div class="coupon-text">
                            <div class="coupon-title">
                                <span class="new-badge">NEW!</span>
                                <span class="fading-text">{{ $list_coupon->remaining_minute }}</span>｜{{ $list_coupon->coupon_name }}｜{{ $list_coupon->store_name }}｜{{ $list_coupon->station }} {{ config('commons.transportation')[$list_coupon->transportation] }}{{ $list_coupon->time }}分
                            </div>
                            <div class="coupon-price">
                                @if ($list_coupon->discount_rate > 0)
                                    <span class="discount-rate">{{ $list_coupon->discount_rate }}%OFF</span>
                                    <span class="price-before">通常{{ number_format($list_coupon->price + $list_coupon->original_service_price) }}円</span>
                                    <span class="price-after">→ {{ number_format(round($list_coupon->store_pay_price) + $list_coupon->service_price) }}円</span>
                                @else
                                    <span class="price-after">{{ number_format($list_coupon->price + $list_coupon->original_service_price) }}円</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        @else
            <p>現在、発行中のクーポンはありません</p>
        @endif
    </div>
</div>

</body>
@include('layouts.footer')
</html>
