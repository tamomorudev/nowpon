<!DOCTYPE html>
<html lang="ja">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- ▼ 詳細検索パーツ ▼ --}}
    <link rel="stylesheet" href="{{ asset('css/site/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/site/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/site/search.css') }}">
    <script src="{{ asset('js/site/search.js') }}" defer></script>
    <script src="{{ asset('js/site/home.js') }}" defer></script>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ナウポンTOP</title>
</head>
<body>
<div class="container">
    @include('layouts.header')

    {{-- ▼ inforamtion（おしらせ）バー：最大3件 ▼ --}}
    @if(isset($inforamtion) && $inforamtion->count())
        <nav class="information-bar" aria-label="inforamtion">
            <div class="information-bar__head">
                <h2>
                    <span>📢</span>
                    お知らせ
                </h2>
            </div>

            <ul class="information-list" role="list">
                @foreach($inforamtion as $info)
                    <li class="information-item">
                        <a href="{{ url('/inforamtion/'.$info->id) }}" title="{{ $info->name }}">
                            {{ $info->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
    @endif
    {{-- ▲ inforamtion バー ▲ --}}

    <div class="carousel-wrapper">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                @if (count($new_coupons))
                    @foreach ($new_coupons as $i => $new_coupon)
                        <div class="swiper-slide">
                            <a href="/site/coupondetail?cid={{ urlencode($new_coupon->coupon_code) }}" class="card-link">
                                <div class="card">
                                    @if($new_coupon->img_url)
                                        <img src="{{ asset('/assets/images/'. $new_coupon->img_url) }}" alt="クーポン画像">
                                    @else
                                        <img src="https://picsum.photos/320/200?random={{ $i }}" alt="クーポン画像" />
                                    @endif
                                    <div class="pr-badge">PR</div>
                                    @if ($new_coupon->discount_rate > 0)
                                        <div class="discount-card-label">
                                            {{ $new_coupon->discount_rate }}%OFF
                                            <span>｜</span>
                                            {{ number_format(($new_coupon->price + $new_coupon->original_service_price) - (round($new_coupon->store_pay_price) + $new_coupon->service_price)) }}円お得
                                        </div>
                                    @endif
                                    <div class="coupon-card-body">
                                        <p>
                                            @if ($new_coupon->discount_rate > 0)
                                                <span class="price-before">{{ number_format($new_coupon->price + $new_coupon->original_service_price) }}円</span>
                                                <span class="price-after">⇒ {{ number_format(round($new_coupon->store_pay_price) + $new_coupon->service_price) }}円</span>
                                            @else
                                                <span class="price-after">{{ number_format($new_coupon->price + $new_coupon->original_service_price) }}円</span>
                                            @endif
                                        </p>
                                        <p>{{ config('commons.genre')[$new_coupon->genre] }}ー{{ $new_coupon->store_name }}</p>
                                        <p class="coupon-card-access">{{ $new_coupon->station }}駅 {{ config('commons.transportation')[$new_coupon->transportation] }}{{ $new_coupon->time }}分</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @else
                    <p>現在、クーポンはありません</p>
                @endif
            </div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>

    <section class="home-coupon-list-section" aria-labelledby="homeCouponListTitle">
        <div class="home-coupon-list-head">
            <h2 class="home-coupon-list-title" id="homeCouponListTitle">クーポン一覧</h2>
            <nav class="home-coupon-list-nav" aria-label="クーポン一覧の表示">
                <a class="home-coupon-list-nav__link" href="/site/couponlist">新着</a>
                <a class="home-coupon-list-nav__link" href="/site/couponlist?search=area">マイエリア</a>
            </nav>
        </div>

        <!-- クーポンリスト -->
        <div class="coupon-list">
            @if (count($new_coupons))
                @foreach ($new_coupons as $new_coupon)
                    <a href="/site/coupondetail?cid={{ urlencode($new_coupon->coupon_code) }}" class="coupon-link coupon-item">
                        <div class="coupon-title">
                            <span class="new-badge">NEW!</span>
                            <span class="fading-text">{{ $new_coupon->remaining_minute }}</span>｜{{ $new_coupon->coupon_name }}｜{{ $new_coupon->store_name }}｜{{ $new_coupon->station }} {{ config('commons.transportation')[$new_coupon->transportation] }}{{ $new_coupon->time }}分
                        </div>
                        <div class="coupon-price">
                            @if ($new_coupon->discount_rate > 0)
                                <span class="discount-rate">{{ $new_coupon->discount_rate }}%OFF</span>
                                <span class="price-before">通常{{ number_format($new_coupon->price + $new_coupon->original_service_price) }}円</span>
                                <span class="price-after">→ {{ number_format(round($new_coupon->store_pay_price) + $new_coupon->service_price) }}円</span>
                            @else
                                <span class="price-after">{{ number_format($new_coupon->price + $new_coupon->original_service_price) }}円</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            @else
                <p>現在、新着クーポンはありません</p>
            @endif
        </div>
    </section>

    <div class="category-search-section">
        <h2 class="site-section-title">
            <span class="site-section-icon">🔖</span>
            カテゴリ検索
        </h2>
        <div class="category-list">
            @foreach(config('commons.genre') as $gkey => $genre)
                <a href="/site/couponlist?search=category&gid={{$gkey}}" class="category-item">
                    <img src="{{ asset('assets/images/material/category' . $gkey . '.png') }}" alt="{{ $genre }}">
                    <!--<img src="https://picsum.photos/seed/{{ rawurlencode($genre) }}/64/64" alt="{{ $genre }}">-->
                    <span>{{ $genre }}</span>
                </a>
            @endforeach
        </div>
    </div>


    <!-- 検索 -->
    <div class="detailed-search-section">
        <div>
            <h2 class="site-section-title">
                <span class="site-section-icon">🔍</span>
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
                                <option value="{{ $key }}">{{ $prefecture }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="search-select-box">
                        <label for="search_station_line">路線</label>
                        <select id="search_station_line" name="station_line">
                            <option value="">都道府県を選択してください</option>
                        </select>
                    </div>

                    <div class="search-select-box">
                        <label for="search_station">駅</label>
                        <select id="search_station" name="station">
                            <option value="">路線を選択してください</option>
                        </select>
                    </div>

                </div>

                <!-- 下段：キーワード + 検索ボタン -->
                <div class="search-keyword-row">
                    <div class="search-keyword-box">
                        <input type="text" name="keyword" placeholder="キーワードを入力" />
                    </div>
                    <button type="submit" class="search-icon-box keyword-search-button">
                        検索
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="feature-section">
        <div class="feature-header">
            <h2 class="site-section-title">
                <span class="site-section-icon">📰</span>
                特集
            </h2>
        </div>
        <div class="feature-grid">
            @if($special_futures)
                @foreach($special_futures as $special_future)
                    <a href="{{ url('/site/couponlist?search=special_futures&id=' . $special_future->id) }}" class="card-link">
                    <div class="feature-card">
                        @if($special_future->image)
                            <img src="{{ asset('/assets/images/' . $special_future->image) }}" alt="画像" class="feature-image">
                        @else
                            <img src="https://picsum.photos/seed/winter/200/200" alt="未設定画像" class="feature-image">
                        @endif

                        <div class="feature-text ms-3">
                            <p class="mb-1">{{ $special_future->name }}</p>
                            <p class="mb-0">{{ $special_future->outline }}</p>
                        </div>
                    </div>
                    </a>
                @endforeach
            @else
                現在開催されている特集はありません。
            @endif
        </div>
        @if (!Auth::user())
            <div class="bottom-buttons">
                <a href="/register" class="btn-register">まずは会員登録</a>
                <a href="/login" class="btn-login">ログイン</a>
            </div>
        @endif
    </div>
</div>
</body>
@include('layouts.footer')
</html>
