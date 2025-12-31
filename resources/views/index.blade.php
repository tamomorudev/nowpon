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

        /* ── お知らせバー ───────────────────────── */
        .information-bar{
            width:100%;
            max-width:100%;
            min-width:0;
            background:#fff8f0;
            border-radius:8px;
            padding-block:16px;
            padding-inline:clamp(12px, 4vw, 24px);
            box-sizing:border-box;
            margin:8px 0 16px;
            color:#6b4e3d;
        }

        .information-bar__head{
            display:flex;
            align-items:center;
            justify-content:space-between;
            margin-bottom:12px;
        }

        .information-bar .information-bar__head h2 {
            display: flex;
            align-items: center;
            font-size: 20px;
            margin: 0;
            font-weight: 700;
            color: #6b4e3d !important;
        }
        .information-bar .information-bar__head h2 span {
            color: inherit;
            margin-right: 8px;
        }

        .information-list{
            margin:0;
            padding:0;
            list-style:none;
            display:flex;
            flex-direction:column;
            gap:8px;
        }
        .information-item{
            display:flex;
            align-items:center;
            gap:8px;
        }
        .information-item::before{
            content:'';
            width:6px; height:6px;
            background:#b08968;
            border-radius:50%;
            flex-shrink:0;
        }
        .information-item a{
            color:#6b4e3d;
            text-decoration:none;
            font-size:14px;
            max-width:100%;
            overflow:hidden; text-overflow:ellipsis; white-space:nowrap;
        }
        .information-item a:hover{ text-decoration:underline; }

        .information-bar a,
        .information-bar a:link,
        .information-bar a:visited {
            color: #6b4e3d;
            text-decoration: none;
        }

        .information-bar a:hover {
            color: #6b4e3d;
            text-decoration: underline;
        }

        .information-bar a:active,
        .information-bar a:focus {
            color: #6b4e3d;
            outline: 2px solid #c29663;
            outline-offset: 2px;
        }

        @media (max-width:767px){
            .information-bar{
                padding-block:12px;
                padding-inline:16px;
                margin:8px 0 12px;
            }
        }

        /* カルーセル ここから */
        .carousel-wrapper {
            padding: 20px 0;
        }
        .swiper-container {
            position: relative;
            overflow: hidden;
        }
        .swiper-button-next,
        .swiper-button-prev {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
        }
        .swiper-button-prev {
            left: 10px;
        }
        .swiper-button-next {
            right: 10px;
        }
        .swiper-wrapper {
            padding: 0;
        }
        .swiper-slide {
            display: flex;
            justify-content: center;
            padding-left: 5px;
            padding-right: 5px;
        }
        .card {
            width: 100%;
            max-width: 320px;
            background: #fff;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 12px;
            position: relative;
        }
        .card-link {
            display: block;
            text-decoration: none;
            color: inherit;
        }
        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 6px;
        }
        .discount-image {
            position: absolute;
            top: 80px;
            left: 0;
            right: 0;
            text-align: center;
        }
        .pr-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background-color: rgba(255, 0, 0, 0.9);
            color: white;
            font-size: 12px;
            font-weight: bold;
            padding: 4px 6px;
            border-radius: 4px;
            z-index: 10;
            pointer-events: none;
        }
        /* カルーセル ここまで */

        /* 「お気に入り」「エリア」「割引率」「ジャンル」ボタン ここから */
        .filter-buttons {
            display: flex;
            justify-content: flex-start;
            gap: 12px;
            margin: 30px 0;
        }
        .filter-buttons button {
            padding: 12px 24px;
            border-radius: 9999px;
            border: 2px solid #b08968;
            background: white;
            color: #6b4e3d;
            font-weight: bold;
            font-size: 14px;
            min-width: 120px;
            text-align: center;
            cursor: pointer;
            transition: background 0.3s, color 0.3s;
        }
        .filter-buttons button:hover,
        .filter-buttons button:focus {
            background: #d2a679;
            color: white;
            outline: none;
        }
        .filter-buttons button.active {
            background: #d2a679;
            color: white;
        }
        @media (max-width: 767px) {
            .filter-buttons {
                flex-wrap: wrap;
                justify-content: center;
                gap: 8px;
            }

            .filter-buttons button {
                min-width: unset;
                padding: 12px 16px;
                font-size: 14px;
                word-break: keep-all;
                white-space: nowrap;
                flex: 1 1 auto;
            }
        }
        /* 「お気に入り」「エリア」「割引率」「ジャンル」ボタン ここまで */

        /* カテゴリ検索ここから */
        .category-search-section {
            margin: 50px 0;
            padding: 0 5px;
        }
        .category-list {
            display: flex;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            gap: 12px;
            padding-top: 5px;
            padding-bottom: 5px;
            background-color: white;
        }
        .category-list::-webkit-scrollbar {
            height: 6px;
        }
        .category-list::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.2);
            border-radius: 3px;
        }
        .category-item {
            flex: 0 0 auto;
            width: 140px;
            text-align: center;
            font-size: 12px;
            color: #333;
            text-decoration: none;
        }
        .category-item img {
            display: block;
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            margin: 0 auto 4px;
        }
        .category-item span {
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        /* カテゴリ検索ここまで */

        /* 特集 ここから */
        .feature-section {
            margin-top: 50px;
        }
        .feature-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        @media (max-width: 767px) {
            .feature-grid {
                grid-template-columns: 1fr;
            }
        }
        .feature-card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            display: flex;
            gap: 16px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            align-items: center;
        }
        .feature-card img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }
        .feature-card p {
            font-size: 14px;
            margin: 0;
            line-height: 1.6;
        }
        /* 特集 ここまで */

        /* 「まずは会員登録」「ログイン」ここから */
        .bottom-buttons {
            margin-top: 40px;
            background: #e5e7eb;
            padding: 24px 0;
            border-radius: 20px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .bottom-buttons .btn-register {
            background: #d2a679;
            color: white;
            font-weight: bold;
            padding: 14px 40px;
            border-radius: 9999px;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
        }
        .bottom-buttons .btn-login {
            background: white;
            color: #6b7280;
            font-weight: bold;
            padding: 14px 40px;
            border-radius: 9999px;
            font-size: 16px;
            box-shadow: 0 0 0 1px #ccc inset;
            text-decoration: none;
            display: inline-block;
        }
        /* 「まずは会員登録」「ログイン」ここまで */

        /* クーポンリストここから */
        .coupon-list {
            border: 2px solid #d4a373;
            padding: 16px;
            border-radius: 12px;
            margin: 30px 0;
            background: #fff8f0;
        }
        .coupon-link {
            display: block;
            text-decoration: none;
            color: inherit;
            border-bottom: 1px solid #000;
            padding-bottom: 8px;
        }
        .coupon-item {
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
            background-color: #ff2e00;
            color: white;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 999px;
            display: inline-block;
            line-height: 1;
        }

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
    </style>
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
                                    <div class="discount-image">
                                        <img src="/images/40off.png" alt="40% OFF" style="width: 100px" />
                                    </div>
                                    <div style="text-align: center; margin-top: 10px">
                                        <p>
                                            @if ($new_coupon->discount_rate > 0)
                                                <span class="price-before">{{ number_format($new_coupon->price + $new_coupon->original_service_price) }}円</span>
                                                <span style="color: #ef4444; font-weight: bold">⇒ {{ number_format(round($new_coupon->store_pay_price) + $new_coupon->service_price) }}円</span>
                                            @else
                                                <span style="color: #ef4444; font-weight: bold">{{ number_format($new_coupon->price + $new_coupon->original_service_price) }}円</span>
                                            @endif
                                        </p>
                                        <p>{{ config('commons.genre')[$new_coupon->genre] }}ー{{ $new_coupon->store_name }}</p>
                                        <p style="font-size: 12px; color: #6b7280">{{ $new_coupon->station }}駅 {{ config('commons.transportation')[$new_coupon->transportation] }}{{ $new_coupon->time }}分</p>
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

    <div class="filter-buttons">
        <button class="active" onclick="location.href='/site/couponlist'">新着</button>
        <button onclick="location.href='/site/couponlist'">お気に入り</button>
        <button onclick="location.href='/site/couponlist?search=area'">マイエリア</button>
        <button onclick="location.href='/site/couponlist'">お得なクーポン</button>
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

    @php
        $categoryNames = [
            'リラク',
            '飲食店',
            '歯医者',
            '薬局',
            '接骨・鍼灸',
            'おでかけスポット',
            'ヘアサロン',
            '動物病院・トリミング',
            'クリニック・病院',
            'テイクアウト',
        ];
    @endphp
    <div class="category-search-section">
        <h2 style="display: flex; align-items: center; font-size: 20px;">
            <span style="font-size: 20px; margin-right: 8px;">🔖</span>
            カテゴリ検索
        </h2>
        <div class="category-list">
            @foreach ($categoryNames as $index => $name)
                <a href="/site/couponlist" class="category-item">
                    <img src="https://picsum.photos/seed/{{ rawurlencode($name) }}/64/64" alt="{{ $name }}">
                    <span>{{ $name }}</span>
                </a>
            @endforeach
        </div>
    </div>

    <!-- 検索 -->
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
            <h2 style="display: flex; align-items: center; font-size: 20px;">
                <span style="font-size: 20px; margin-right: 8px;">📰</span>
                特集
            </h2>
            <a href="#" style="color: #3b82f6; font-size: 14px;">もっと見る ＞</a>
        </div>
        <div class="feature-grid">
            @if($special_futures)
                @foreach($special_futures as $special_future)
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const swiper = new Swiper('.swiper-container', {
            slidesPerView: 8,
            spaceBetween: 20,
            centeredSlides: false,
            loop: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev'
            },
            breakpoints: {
                0: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                480: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                768: { slidesPerView: 2.5 },
                1024: { slidesPerView: 3.5 },
                1280: { slidesPerView: 4 },
                1440: { slidesPerView: 5 },
            }
        });
    });

    document.querySelectorAll('.filter-buttons button').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.filter-buttons button').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        });
    });
</script>
</body>
@include('layouts.footer')
</html>
