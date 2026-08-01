<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/site/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/site/coupon-detail.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <title>{{ $coupon->coupon_name }} | ナウポン</title>
</head>

<body>
<div class="container">
    @include('layouts.header')

    <!-- クーポン詳細 -->
    <div class="coupon-detail">
        <h2>商品に関する情報</h2>

        <div class="detail-body">
            <!-- 左：カルーセル -->
            <div class="carousel-wrapper">
                <!-- メインスライダー -->
                <div class="swiper swiper-main">
                    <div class="swiper-wrapper">
                        @php
                            $validCouponImages = collect($coupon->coupon_images ?? [])->filter(function ($coupon_image) {
                                return $coupon_image && file_exists(public_path('assets/images/'.$coupon_image));
                            });
                        @endphp
                        @if ($validCouponImages->isEmpty())
                            <div class="swiper-slide">
                                <div class="coupon-detail-placeholder coupon-detail-placeholder--main" aria-label="クーポン画像未設定">
                                    <span class="coupon-detail-placeholder__title">Nowpon</span>
                                    <span class="coupon-detail-placeholder__text">画像準備中</span>
                                </div>
                            </div>
                        @endif
                        @foreach ($coupon->coupon_images as $image_key => $coupon_image)
                            @if ($coupon_image && file_exists(public_path('assets/images/'.$coupon_image)))
                            <div class="swiper-slide">
                                <img src="{{ asset('/assets/images/'. $coupon_image) }}" alt="クーポン画像{{$image_key}}">
                            </div>
                            @endif
                        @endforeach
                        <?php /*
                        @foreach (range(1,5) as $i)
                            <div class="swiper-slide">
                                @if($coupon->img_url && $i == 1)
                                    <img src="{{ asset('/assets/images/'. $coupon->img_url) }}" alt="クーポン画像">
                                @else
                                    <img src="https://picsum.photos/320/200?random={{ $i }}" alt="クーポン画像" />
                                @endif
                            </div>
                        @endforeach
                        */ ?>
                    </div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-pagination"></div>
                </div>
                <!-- サムネイルスライダー -->
                <div class="swiper swiper-thumbs">
                    <div class="swiper-wrapper">
                        @foreach ($coupon->coupon_images as $image_key => $coupon_image)
                            @if ($coupon_image && file_exists(public_path('assets/images/'.$coupon_image)))
                            <div class="swiper-slide">
                                <img src="{{ asset('/assets/images/'. $coupon_image) }}" alt="クーポン画像{{$image_key}}">
                            </div>
                            @endif
                        @endforeach
                        @if ($validCouponImages->isEmpty())
                            <div class="swiper-slide">
                                <div class="coupon-detail-placeholder coupon-detail-placeholder--thumb" aria-label="クーポン画像未設定">
                                    <span class="coupon-detail-placeholder__text">画像なし</span>
                                </div>
                            </div>
                        @endif
                        <?php /*
                        @foreach (range(1,6) as $i)
                            <div class="swiper-slide">
                                @if($coupon->img_url && $i == 1)
                                    <img src="{{ asset('/assets/images/'. $coupon->img_url) }}" alt="クーポン画像">
                                @else
                                    <img src="https://picsum.photos/100/80?random={{ $i }}" alt="サムネ{{ $i }}">
                                @endif
                            </div>
                        @endforeach
                        */ ?>
                    </div>
                </div>
            </div>

            <!-- 右：商品情報パネル -->
            <div class="info-panel">
                <div class="tab">商品内容</div>
                <div class="info">
                    <p><strong>{{ config('commons.genre')[$coupon->genre] }}ー{{ $coupon->store_name }}</strong></p>
                    @if($coupon->store_image && file_exists(public_path('assets/images/'.$coupon->store_image)))
                        <div class="store-image">
                            <img src="{{ asset('/assets/images/'. $coupon->store_image) }}" alt="{{ $coupon->store_name }}">
                        </div>
                    @else
                        <div class="store-image">
                            <div class="coupon-detail-placeholder coupon-detail-placeholder--store" aria-label="店舗画像未設定">
                                <span class="coupon-detail-placeholder__text">店舗画像準備中</span>
                            </div>
                        </div>
                    @endif
                    <p>最寄り駅：{{ $coupon->station }}駅 {{ config('commons.transportation')[$coupon->transportation] }}{{ $coupon->time }}分
                    @if ($coupon->station_2 && $coupon->time_2)
                        <br>最寄り駅：{{ $coupon->station_2 }}駅 {{ config('commons.transportation')[$coupon->transportation_2] }}{{ $coupon->time_2 }}分
                    @endif
                    </p>
                    <p>
                        @if ($coupon->discount_rate > 0)
                            <span class="price-before">通常{{ number_format($coupon->price + $coupon->original_service_price) }}円</span>
                            <span class="price-after">→ {{ number_format(round($coupon->store_pay_price) + $coupon->service_price) }}円</span>
                            ({{ $coupon->discount_rate }}%OFF)
                        @else
                            {{ number_format($coupon->price + $coupon->original_service_price) }}円
                        @endif
                    </p>
                    <p>予約日時：{{ $coupon->format_cource_start }}<br>予定所要時間：{{ $coupon->cource_time }}分</p>
                    <p>{{ $coupon->coupon_name }}</p>
                </div>
            </div>
            <?php /*元ソース
            <div class="info-panel">
                <div class="tab">商品内容</div>
                <div class="info">
                    <p><strong>ジャンルー店舗名</strong></p>
                    <p>〇〇駅 北口徒歩何分</p>
                    <p>¥3,000（40%off）</p>
                    <p>予約日時：2025年3月1日 16時〜</p>
                    <p>コース名</p>
                </div>
            </div>
            */ ?>
        </div>

        <div class="btn-group">
            <a href="/site/checkout?cid={{$coupon->coupon_code}}" class="coupon-detail-buy-button">このクーポンを購入する</a>
        </div>


        <?php /*
        <!-- こちらもおすすめ -->
        <div class="recommend-section">
            <hr class="recommend-separator">
            <h3 class="recommend-title">こちらもおすすめ</h3>
            <div class="recommend-grid">
                @foreach (range(1,4) as $i)
                    <a href="/site/coupondetail" class="recommend-item">
                        <div class="card-wrapper">
                            <img src="https://picsum.photos/320/200?random={{ $i }}" alt="おすすめ{{ $i }}">
                        </div>
                        <div class="recommend-info">
                            <p class="shop-name">ジャンルー店舗名</p>
                            <p class="shop-access">〇〇駅 北口徒歩何分</p>
                            <p class="price">¥3,000（40%off）</p>
                            <p class="date">予約日時：2025年3月1日 16時〜</p>
                            <p class="course">コース名</p>
                        </div>
                        <div class="recommend-footer">
                            <div class="icons">
                                <button class="icon-btn heart">♡</button>
                                <button aria-label="共有" class="icon-btn share">
                                    <svg width="18" height="18" viewBox="0 0 24 24" preserveAspectRatio="xMidYMax meet" shape-rendering="crispEdges" fill="currentColor">
                                        <path d="M18 16.08c-.76 0-1.44.3-1.97.8l-7.12-4.18c.05-.23.09-.47.09-.7s-.04-.47-.09-.7l7.12-4.18c.53.5 1.21.8 1.97.8 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7l-7.12 4.18c-.5-.5-1.18-.8-1.91-.8C5.33 9.08 4 10.42 4 12s1.33 2.92 2.99 2.92c.74 0 1.42-.3 1.91-.8l7.12 4.18c-.05.23-.09.47-.09.7 0 1.66 1.34 3 3 3s3-1.34 3-3-1.34-3-3-3z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        */ ?>
        <!-- 同じエリアでのクーポン -->
        <div class="recommend-section">
            <hr class="recommend-separator">
            <h3 class="recommend-title">同じエリアでのクーポン </h3>
            <div class="recommend-grid">
                @if (count($same_area_coupons) >= 1)
                    @foreach ($same_area_coupons as $area_key => $same_area_coupon)
                        <a href="/site/coupondetail?cid={{$same_area_coupon->coupon_code}}" class="recommend-item">
                            <div class="card-wrapper">
                                @if ($same_area_coupon->img_url && file_exists(public_path('assets/images/'.$same_area_coupon->img_url)))
                                    <img src="{{ asset('/assets/images/'. $same_area_coupon->img_url) }}" alt="area_{{$area_key}}">
                                @else
                                    <div class="coupon-detail-placeholder coupon-detail-placeholder--recommend" aria-label="クーポン画像未設定">
                                        <span class="coupon-detail-placeholder__title">Nowpon</span>
                                        <span class="coupon-detail-placeholder__text">画像準備中</span>
                                    </div>
                                @endif
                            </div>
                            <div class="recommend-info">
                                <p class="shop-name">{{ config('commons.genre')[$same_area_coupon->genre] }}ー{{ $same_area_coupon->store_name }}</p>
                                <p class="shop-access">{{ $same_area_coupon->station }}駅 {{ config('commons.transportation')[$same_area_coupon->transportation] }}{{ $same_area_coupon->time }}分</p>
                                @if ($same_area_coupon->discount_rate > 0)
                                    <p class="price">¥{{ number_format(round($same_area_coupon->store_pay_price) + $same_area_coupon->service_price) }}（{{$same_area_coupon->discount_rate}}%off）</p>
                                @else
                                    <p class="price">¥{{ number_format(round($same_area_coupon->store_pay_price) + $same_area_coupon->service_price) }}</p>
                                @endif
                                <p class="date">予約日時：{{ $same_area_coupon->format_cource_start }}</p>
                                <p class="course">{{ $same_area_coupon->coupon_name }}</p>
                            </div>
                            <?php /* 一旦アイコンなし
                            <div class="recommend-footer">
                                <div class="icons">
                                    <button class="icon-btn heart">♡</button>
                                    <button aria-label="共有" class="icon-btn share">
                                        <svg width="18" height="18" viewBox="0 0 24 24" preserveAspectRatio="xMidYMax meet" shape-rendering="crispEdges" fill="currentColor">
                                            <path d="M18 16.08c-.76 0-1.44.3-1.97.8l-7.12-4.18c.05-.23.09-.47.09-.7s-.04-.47-.09-.7l7.12-4.18c.53.5 1.21.8 1.97.8 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7l-7.12 4.18c-.5-.5-1.18-.8-1.91-.8C5.33 9.08 4 10.42 4 12s1.33 2.92 2.99 2.92c.74 0 1.42-.3 1.91-.8l7.12 4.18c-.05.23-.09.47-.09.7 0 1.66 1.34 3 3 3s3-1.34 3-3-1.34-3-3-3z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            */ ?>
                        </a>
                    @endforeach
                @else
                    現在クーポンがありません。
                @endif
            </div>
        </div>


    </div>
</div>

    <!-- Swiper JS 読み込み -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="{{ asset('js/site/coupon-detail.js') }}" defer></script>

@include('layouts.footer')
</body>
</html>
