<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/site/common.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="{{ asset('css/site/coupon-detail.css') }}?v={{ filemtime(public_path('css/site/coupon-detail.css')) }}">
    <title>{{ $coupon->coupon_name }} | ナウポン</title>
</head>
<body>
<div class="container">
    @include('layouts.header')

    @php
        $validCouponImages = collect($coupon->coupon_images ?? [])->filter(function ($path) {
            return $path && file_exists(public_path('assets/images/'.$path));
        })->values();
        $hasMultipleImages = $validCouponImages->count() > 1;
        $hasStoreImage = $coupon->store_image && file_exists(public_path('assets/images/'.$coupon->store_image));
        $hasStoreMap = !empty($coupon->google_map_embed_url);
        $hasStoreMedia = $hasStoreImage || $hasStoreMap;
        $storeWebsite = filter_var($coupon->url, FILTER_VALIDATE_URL)
            && in_array(strtolower(parse_url($coupon->url, PHP_URL_SCHEME) ?? ''), ['http', 'https'])
            ? $coupon->url : null;
    @endphp

    <main class="coupon-detail">
        <div class="coupon-overview">
            <div class="coupon-gallery" aria-label="クーポンの写真">
                <div class="swiper swiper-main">
                    <div class="swiper-wrapper">
                        @forelse ($validCouponImages as $image)
                            <div class="swiper-slide">
                                <img src="{{ asset('assets/images/'.$image) }}" alt="{{ $coupon->coupon_name }}の写真 {{ $loop->iteration }}">
                            </div>
                        @empty
                            <div class="swiper-slide">
                                <div class="coupon-detail-placeholder coupon-detail-placeholder--main">
                                    <span class="coupon-detail-placeholder__title">Nowpon</span>
                                    <span class="coupon-detail-placeholder__text">画像準備中</span>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    @if ($hasMultipleImages)
                        <button type="button" class="swiper-button-prev" aria-label="前の写真"></button>
                        <button type="button" class="swiper-button-next" aria-label="次の写真"></button>
                        <div class="swiper-pagination"></div>
                    @endif
                </div>
                @if ($hasMultipleImages)
                    <div class="swiper swiper-thumbs" aria-label="写真を選択">
                        <div class="swiper-wrapper">
                            @foreach ($validCouponImages as $image)
                                <div class="swiper-slide">
                                    <button type="button" class="coupon-gallery-thumb" aria-label="画像 {{ $loop->iteration }} を表示" aria-pressed="{{ $loop->first ? 'true' : 'false' }}">
                                        <img src="{{ asset('assets/images/'.$image) }}" alt="">
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <section class="coupon-purchase" aria-labelledby="coupon-title">
                <div class="coupon-ticket-details">
                    <header class="coupon-heading">
                        <h1 id="coupon-title">{{ $coupon->coupon_name }}</h1>
                        <p>{{ config('commons.genre')[$coupon->genre] ?? '' }}<span aria-hidden="true"> / </span><a href="#store-info">{{ $coupon->store_name }}</a></p>
                    </header>
                    @if (trim((string) $coupon->detail) !== '')
                        <section class="coupon-ticket-description" aria-labelledby="coupon-description-title">
                            <h2 id="coupon-description-title">クーポンの内容</h2>
                            <div class="coupon-description-text">{{ $coupon->detail }}</div>
                        </section>
                    @endif
                </div>
                <div class="coupon-ticket-perforation" aria-hidden="true"></div>
                <div class="coupon-ticket-purchase">
                    <p class="coupon-price-label">ご利用料金</p>
                    @if ($coupon->discount_rate > 0)
                        <div class="coupon-price-comparison">
                            <span>通常 <s>{{ number_format($coupon->price + $coupon->original_service_price) }}円</s></span>
                            <span class="coupon-discount">{{ $coupon->discount_rate }}%OFF</span>
                        </div>
                    @endif
                    <p class="coupon-price">{{ number_format($coupon->discount_rate > 0 ? round($coupon->store_pay_price) + $coupon->service_price : $coupon->price + $coupon->original_service_price) }}<span>円</span></p>
                    <dl class="coupon-booking">
                        <div><dt>予約日時</dt><dd>{{ $coupon->format_cource_start }}</dd></div>
                        <div><dt>所要時間</dt><dd>{{ $coupon->cource_time }}分</dd></div>
                    </dl>
                    <a href="/site/checkout?cid={{ $coupon->coupon_code }}" class="coupon-detail-buy-button">このクーポンを購入する</a>
                </div>
            </section>
        </div>

        <section class="coupon-store" id="store-info" aria-labelledby="store-info-title">
            <h2 id="store-info-title">店舗情報</h2>
            <div class="coupon-store-layout{{ $hasStoreMedia ? '' : ' coupon-store-layout--text-only' }}">
                <div class="coupon-store-details">
                    <h3>{{ $coupon->store_name }}</h3>
                    <dl class="store-facts">
                        @if ($coupon->address1 || $coupon->address2 || $coupon->address3)
                            <div>
                                <dt>住所</dt>
                                <dd>
                                    @if ($coupon->postal_code)<span class="store-postal">〒{{ $coupon->postal_code }}</span>@endif
                                    {{ config('commons.prefectures')[$coupon->address1] ?? '' }}{{ $coupon->address2 }}{{ $coupon->address3 }}
                                </dd>
                            </div>
                        @endif
                        @if ($coupon->station)
                            <div>
                                <dt>アクセス</dt>
                                <dd>{{ $coupon->line }} {{ $coupon->station }}駅<br>{{ config('commons.transportation')[$coupon->transportation] ?? '' }}{{ $coupon->time }}分
                                    @if ($coupon->station_2)
                                        <span class="store-access-secondary">{{ $coupon->line_2 }} {{ $coupon->station_2 }}駅<br>{{ config('commons.transportation')[$coupon->transportation_2] ?? '' }}{{ $coupon->time_2 }}分</span>
                                    @endif
                                </dd>
                            </div>
                        @endif
                        @if ($coupon->phone_number)
                            <div><dt>電話番号</dt><dd><a href="tel:{{ preg_replace('/[^0-9+]/', '', $coupon->phone_number) }}">{{ $coupon->phone_number }}</a></dd></div>
                        @endif
                        @if ($storeWebsite)
                            <div><dt>Webサイト</dt><dd><a href="{{ $storeWebsite }}" target="_blank" rel="noopener noreferrer">店舗の公式サイト</a></dd></div>
                        @endif
                    </dl>
                </div>
                @if ($hasStoreMedia)
                    <div class="coupon-store-media">
                        @if ($hasStoreImage)
                            <img class="store-image" src="{{ asset('assets/images/'.$coupon->store_image) }}" alt="{{ $coupon->store_name }}の店舗写真" loading="lazy">
                        @else
                            <div class="coupon-detail-placeholder coupon-detail-placeholder--store"><span class="coupon-detail-placeholder__text">店舗画像準備中</span></div>
                        @endif
                        @if ($hasStoreMap)
                            <div class="location-map">
                                <iframe src="{{ $coupon->google_map_embed_url }}" title="{{ $coupon->store_name }}の地図" loading="lazy" allowfullscreen referrerpolicy="strict-origin-when-cross-origin"></iframe>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </section>

        <section class="recommend-section" aria-labelledby="recommend-title">
            <h2 id="recommend-title">同じエリアでのクーポン</h2>
            <div class="recommend-grid">
                @forelse ($same_area_coupons as $same_area_coupon)
                    <a href="/site/coupondetail?cid={{ $same_area_coupon->coupon_code }}" class="recommend-item">
                        <div class="card-wrapper">
                            @if ($same_area_coupon->img_url && file_exists(public_path('assets/images/'.$same_area_coupon->img_url)))
                                <img src="{{ asset('assets/images/'.$same_area_coupon->img_url) }}" alt="{{ $same_area_coupon->coupon_name }}" loading="lazy">
                            @else
                                <div class="coupon-detail-placeholder coupon-detail-placeholder--recommend"><span class="coupon-detail-placeholder__text">画像準備中</span></div>
                            @endif
                        </div>
                        <div class="recommend-info">
                            <p class="shop-name">{{ config('commons.genre')[$same_area_coupon->genre] ?? '' }} / {{ $same_area_coupon->store_name }}</p>
                            <p class="course">{{ $same_area_coupon->coupon_name }}</p>
                            <p class="price">¥{{ number_format(round($same_area_coupon->store_pay_price) + $same_area_coupon->service_price) }}@if ($same_area_coupon->discount_rate > 0)<span>（{{ $same_area_coupon->discount_rate }}%OFF）</span>@endif</p>
                            <p class="date">{{ $same_area_coupon->format_cource_start }}</p>
                            <p class="shop-access">{{ $same_area_coupon->station }}駅 {{ config('commons.transportation')[$same_area_coupon->transportation] ?? '' }}{{ $same_area_coupon->time }}分</p>
                        </div>
                    </a>
                @empty
                    <p class="coupon-empty-message">現在クーポンがありません。</p>
                @endforelse
            </div>
        </section>
    </main>
</div>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="{{ asset('js/site/coupon-detail.js') }}?v={{ filemtime(public_path('js/site/coupon-detail.js')) }}" defer></script>
@include('layouts.footer')
</body>
</html>
