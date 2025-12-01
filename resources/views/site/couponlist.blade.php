<!DOCTYPE html>
<html lang="ja">
<head>
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

    </style>
</head>
<body>
<div class="container">
    @include('layouts.header')

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
            <?php /*元ソース
            <a href="/site/coupondetail" class="coupon-item">
                <div class="coupon-content">
                    <img src="https://picsum.photos/80/80?random=1" alt="店舗画像" class="coupon-thumb" />
                    <div class="coupon-text">
                        <div class="coupon-title">
                            <span class="new-badge">NEW!</span>
                            <span class="fading-text">残り120分</span>｜骨盤矯正（初回限定）｜渋谷整体サロン｜渋谷駅 徒歩3分
                        </div>
                        <div class="coupon-price">
                            <span class="discount-rate">50%OFF</span>
                            <span class="price-before">通常6,000円</span>
                            <span class="price-after">→ 3,000円</span>
                        </div>
                    </div>
                </div>
            </a>
            <a href="/site/coupondetail" class="coupon-item">
                <div class="coupon-content">
                    <img src="https://picsum.photos/80/80?random=2" alt="店舗画像" class="coupon-thumb" />
                    <div class="coupon-text">
                        <div class="coupon-title">
                            <span class="new-badge">NEW!</span>
                            <span class="fading-text">残り120分</span>｜骨盤矯正（初回限定）｜渋谷整体サロン｜渋谷駅 徒歩3分
                        </div>
                        <div class="coupon-price">
                            <span class="discount-rate">50%OFF</span>
                            <span class="price-before">通常6,000円</span>
                            <span class="price-after">→ 3,000円</span>
                        </div>
                    </div>
                </div>
            </a>
            <a href="/site/coupondetail" class="coupon-item">
                <div class="coupon-content">
                    <img src="https://picsum.photos/80/80?random=3" alt="店舗画像" class="coupon-thumb" />
                    <div class="coupon-text">
                        <div class="coupon-title">
                            <span class="fading-text">残り120分</span>｜骨盤矯正（初回限定）｜渋谷整体サロン｜渋谷駅 徒歩3分
                        </div>
                        <div class="coupon-price">
                            <span class="discount-rate">50%OFF</span>
                            <span class="price-before">通常6,000円</span>
                            <span class="price-after">→ 3,000円</span>
                        </div>
                    </div>
                </div>
            </a>
            */ ?>
        @else
            <p>現在、発行中のクーポンはありません</p>
        @endif
    </div>
</div>

</body>
@include('layouts.footer')
</html>
