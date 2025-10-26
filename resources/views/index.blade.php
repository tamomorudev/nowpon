<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ナウポンTOP</title>
     <style>

         /* ── お知らせバー ───────────────────────── */
         .information-bar{
             width:100%;
             max-width:100%;
             min-width:0;                 /* Flex 子要素の伸縮で必須になることあり */
             background:#fff8f0;
             border-radius:8px;
             padding-block:16px;
             /* 画面幅に応じて左右パディングを自動調整（12px〜24px） */
             padding-inline:clamp(12px, 4vw, 24px);
             box-sizing:border-box;
             margin:8px 0 16px;           /* ← 左右の margin を撤廃（詰まりの原因） */
             color:#6b4e3d;
         }

         /* ヘッダー行 */
         .information-bar__head{
             display:flex;
             align-items:center;
             justify-content:space-between;
             margin-bottom:12px;
         }

         /* タイトル（カテゴリ検索と同サイズ） */
         /* タイトル（カテゴリ検索と同じ色・サイズにする、確実に適用） */
         .information-bar .information-bar__head h2 {
             display: flex;
             align-items: center;
             font-size: 20px;
             margin: 0;
             font-weight: 700;
             color: #6b4e3d !important; /* ← 念のため確実に適用 */
         }
         .information-bar .information-bar__head h2 span {
             color: inherit;
             margin-right: 8px;
         }

         /* リスト（●とテキストを中央揃えに） */
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

         /* information 内のリンク色を統一（青くしない） */
         .information-bar a,
         .information-bar a:link,
         .information-bar a:visited {
             color: #6b4e3d;           /* サイトの本文色に合わせる */
             text-decoration: none;
         }

         .information-bar a:hover {
             color: #6b4e3d;           /* ここで色を変えない */
             text-decoration: underline; /* hover 時は下線だけでリンク感を出す */
         }

         .information-bar a:active,
         .information-bar a:focus {
             color: #6b4e3d;
             outline: 2px solid #c29663;  /* アクセシビリティ用フォーカス */
             outline-offset: 2px;
         }

         /* SP時も左右 margin は 0 のまま。padding だけ少しだけ小さく */
         @media (max-width:767px){
             .information-bar{
                 padding-block:12px;
                 padding-inline:16px;   /* ← 左右は margin ではなく padding で調整 */
                 margin:8px 0 12px;     /* ← 左右に余白を作らない */
             }
         }

         /* ── お知らせバー ここまで───────────────────────── */

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
            position: absolute;    /* ここで絶対配置 */
            top: 50%;              /* カルーセルの上下中央 */
            transform: translateY(-50%);
            z-index: 10;           /* 画像やスライドの前面に */
        }

        /* 3) 左右の位置を微調整 */
        .swiper-button-prev {
            left: 10px;            /* 左端からの距離 */
        }
        .swiper-button-next {
            right: 10px;           /* 右端からの距離 */
        }
        .swiper-wrapper {
            padding: 0; /* padding削除 */
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
            position: absolute;    /* .card を基準に配置 */
            top: 12px;             /* お好みで微調整 */
            right: 12px;           /* 右上に寄せる */
            background-color: rgba(255, 0, 0, 0.9); /* 半透明の赤背景 */
            color: white;
            font-size: 12px;
            font-weight: bold;
            padding: 4px 6px;
            border-radius: 4px;
            z-index: 10;           /* 画像や他の要素より前面に */
            pointer-events: none;  /* クリックを透過させたい場合 */
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
                flex-wrap: wrap; /* ← 折り返し許可 */
                justify-content: center; /* ← 全体を中央寄せ */
                gap: 8px;
            }

            .filter-buttons button {
                min-width: unset; /* ← 固定幅を外す */
                padding: 12px 16px;
                font-size: 14px;
                word-break: keep-all;
                white-space: nowrap; /* ← 文字の折り返し防止 */
                flex: 1 1 auto; /* ← フレキシブルな横幅で伸縮 */
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
            overflow-x: auto; /* 横スクロール有効化 */
            -webkit-overflow-scrolling: touch;/* iOS の慣性スクロール */
            gap: 12px;
            padding-top: 5px;
            padding-bottom: 5px;
            background-color: white;
        }
        .category-list::-webkit-scrollbar {
            height: 6px; /* スクロールバーの高さ */
        }
        .category-list::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.2);
            border-radius: 3px;
        }
        .category-item {
            flex: 0 0 auto;/* 折り返しさせずに横幅固定 */
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

        /* 詳細検索 ここから */
        .detailed-search-section {
            margin-top: 60px;
        }
        .search-panel {
            background: #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
        }
        .search-tags {
            display: flex;
            gap: 12px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }
        .search-tag-box {
            background-color: white;
            border-radius: 10px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.08);
            border: 1px solid #eee;
            white-space: nowrap;
        }
        .search-icon-box {
            background: #b08968;
            color: white;
            font-weight: bold;
            height: 48px;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            border: none;
            font-size: 16px;
        }
        .search-keyword-row {
            display: flex;
            gap: 16px;
            align-items: flex-start;
        }
        .search-keyword-box {
            flex: 2;
            background: white;
            border-radius: 9999px;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            font-size: 14px;
        }
        .search-keyword-box input {
            border: none;
            flex: 1;
            background: none;
            outline: none;
            font-size: 14px;
        }
        .search-keyword-box .search-icon {
            color: #f97316;
            font-size: 18px;
            margin-left: 10px;
        }
        .keyword-tags-box {
            flex: 1;
            background: #b08968;
            border-radius: 12px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            color: white;
        }
        .keyword-tags-box button {
            background: white;
            color: #6b4e3d;
            font-weight: bold;
            border-radius: 20px;
            padding: 6px 16px;
            border: none;
            font-size: 14px;
            width: fit-content;
        }
        .recent-search-box {
            background: white;
            border-radius: 12px;
            padding: 12px 16px;
            margin-top: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .recent-search-box span:first-child {
            color: #9ca3af;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        @media (max-width: 767px) {
            .search-panel {
                padding: 12px;
            }
            .search-tags {
                flex-direction: column;
                align-items: stretch;
            }
            .search-tag-box,
            .search-icon-box {
                width: 100%;
                justify-content: center;
            }
            .search-keyword-row {
                flex-direction: column;
                gap: 16px;
            }
            .keyword-tags-box {
                width: 100%;
                flex-direction: row;
                justify-content: center;
                flex-wrap: wrap;
                gap: 8px;
                padding: 12px;
            }
            .keyword-tags-box button {
                width: auto;
                flex-shrink: 0;
            }
            .search-keyword-box,
            .recent-search-box {
                width: 100%;
            }
        }
        @media (max-width: 767px) {
            .search-panel {
                padding: 20px 16px;
            }
            .search-tags {
                display: flex;
                flex-direction: column;
                gap: 12px;
                width: 100%;
            }
            .search-tag-box,
            .search-icon-box {
                width: 100%;
                box-sizing: border-box;
            }
        }
        .keyword-tags-box {
            max-width: 100%;
            overflow-x: hidden;
        }
        @media (max-width: 767px) {
            .keyword-tags-box {
                display: flex;
                flex-wrap: wrap;
                justify-content: center; /* ← 中央寄せに */
                gap: 8px;
                padding: 12px;
                background: #b08968;
                border-radius: 12px;
                width: 100%;             /* ← これがないと横スク発生する場合あり */
                box-sizing: border-box;  /* ← paddingの幅を含める */
            }
            .keyword-tags-box button {
                flex-shrink: 0;
                width: auto;
            }
        }
        /* 詳細検索 ここまで */

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
            grid-template-columns: repeat(2, 1fr); /* 通常は2列 */
            gap: 20px;
        }
        @media (max-width: 767px) {
            .feature-grid {
                grid-template-columns: 1fr; /* スマホでは1列 */
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
            text-decoration: none;     /* ← 下線消す */
            display: inline-block;     /* ← ボタン風維持 */
        }
        .bottom-buttons .btn-login {
            background: white;
            color: #6b7280;
            font-weight: bold;
            padding: 14px 40px;
            border-radius: 9999px;
            font-size: 16px;
            box-shadow: 0 0 0 1px #ccc inset;
            text-decoration: none;     /* ← 下線消す */
            display: inline-block;     /* ← ボタン風維持 */
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
            display: block;               /* ブロック化して子要素を包む */
            text-decoration: none;        /* 青文字＋下線（text‐decoration）は消す */
            color: inherit;
            border-bottom: 1px solid #000;/* ここで下線を再定義 */
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
                        {{-- タイトルのみのシンプル表示。必要なら日付を先頭に足せます --}}
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
                                                @if ($new_coupon->discount_type == 1)
                                                    <span style="color: #ef4444; font-weight: bold">⇒ {{ number_format(round($new_coupon->price * (1 - ($new_coupon->discount_price / 100))) + $new_coupon->service_price) }}円</span>
                                                @else
                                                    <span style="color: #ef4444; font-weight: bold">⇒ {{ number_format(round($new_coupon->price - $new_coupon->discount_price) + $new_coupon->service_price) }}円</span>
                                                @endif
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
                <?php /*元ソース
                @foreach (range(1, 6) as $i)
                    <div class="swiper-slide">
                        <a href="/site/coupondetail" class="card-link">
                            <div class="card">
                                <img src="https://picsum.photos/320/200?random={{ $i }}" alt="店舗画像" />
                                <div class="pr-badge">PR</div>
                                <div class="discount-image">
                                    <img src="/images/40off.png" alt="40% OFF" style="width: 100px" />
                                </div>
                                <div style="text-align: center; margin-top: 10px">
                                    <p>
                                        <span style="text-decoration: line-through; color: gray">$68.56</span>
                                        <span style="color: #ef4444; font-weight: bold">⇒ $40.56</span>
                                    </p>
                                    <p>ジャンルー店舗名</p>
                                    <p style="font-size: 12px; color: #6b7280">〇〇駅 北口徒歩何分</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
                */ ?>
            </div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>

    <div class="filter-buttons">
        <button class="active" onclick="location.href='/site/couponlist'">新着</button>
        <button onclick="location.href='/site/couponlist'">お気に入り</button>
        <button onclick="location.href='/site/couponlist'">マイエリア</button>
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
                            @if ($new_coupon->discount_type == 1)
                                <span class="price-after">→ {{ number_format(round($new_coupon->price * (1 - ($new_coupon->discount_price / 100))) + $new_coupon->service_price) }}円</span>
                            @else
                                <span class="price-after">→ {{ number_format(round($new_coupon->price - $new_coupon->discount_price) + $new_coupon->service_price) }}円</span>
                            @endif
                        @else
                            <span class="price-after">{{ number_format($new_coupon->price + $new_coupon->original_service_price) }}円</span>
                        @endif
                    </div>
                </a>
            @endforeach
        @else
            <p>現在、新着クーポンはありません</p>
        @endif
        <?php /* 元ソース
        <a href="/site/coupondetail" class="coupon-link coupon-item">
            <div class="coupon-title">
                <span class="new-badge">NEW!</span>
                <span class="fading-text">残り120分</span>｜骨盤矯正（初回限定）｜渋谷整体サロン｜渋谷駅 徒歩3分
            </div>
            <div class="coupon-price">
                <span class="discount-rate">50%OFF</span>
                <span class="price-before">通常6,000円</span>
                <span class="price-after">→ 3,000円</span>
            </div>
        </a>
        <a href="/site/coupondetail" class="coupon-link coupon-item">
            <div class="coupon-title">
                <span class="new-badge">NEW!</span>
                <span class="fading-text">残り110分</span>｜ジェルネイル（ワンカラー）｜表参道ネイルルーム｜表参道駅 徒歩2分
            </div>
            <div class="coupon-price">
                <span class="discount-rate">40%OFF</span>
                <span class="price-before">通常5,000円</span>
                <span class="price-after">→ 3,000円</span>
            </div>
        </a>
        <a href="/site/coupondetail" class="coupon-link coupon-item">
            <div class="coupon-title">
                <span class="fading-text">あと30分</span>｜カット＋パーマ（男性歓迎）｜池袋ヘアサロンM｜池袋駅 徒歩5分
            </div>
            <div class="coupon-price">
                <span class="discount-rate">30%OFF</span>
                <span class="price-before">通常7,800円</span>
                <span class="price-after">→ 5,460円</span>
            </div>
        </a>
        */ ?>
    </div>

    @php
        // 表示したいカテゴリ名を配列で用意
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

    <div class="detailed-search-section">
        <div>
            <h2 style="display: flex; align-items: center; font-size: 20px;">
                <span style="font-size: 20px; margin-right: 8px;">🔍</span>
                詳細検索
            </h2>
        </div>
        <div class="search-panel">
            <div class="search-tags">
                <div class="search-tag-box">📦 ジャンル ×</div>
                <div class="search-tag-box">📍 場所 ×</div>
                <div class="search-tag-box">➕ こだわり条件</div>
                <div class="search-icon-box">検索</div>
            </div>
            <div class="search-keyword-row">
                <div style="flex: 2; display: flex; flex-direction: column; gap: 12px;">
                    <div class="search-keyword-box">
                        <input type="text" placeholder="キーワードから探す" />
                        <span class="search-icon">🔍</span>
                    </div>
                    <div class="recent-search-box">
                        <span>🕵 最近検索した条件</span>
                        <span>なし</span>
                    </div>
                </div>
                <div class="keyword-tags-box">
                    <button># 人気条件</button>
                    <button># 残り時間</button>
                </div>
            </div>
        </div>
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
                    <?php /*
                    <div class="feature-card">
                        @if($special_future->image)
                            <img width="50" height="50" src="{{ asset('/assets/images/'. $special_future->image) }}" >
                        @else
                            <img src="https://picsum.photos/seed/winter/200/200" alt="Feature 1"> {{--未設定用画像--}}
                        @endif
                        <p>{{$special_future->name}}<br>{!! Str::limit($special_future->detail, 50) !!}</p>
                        {{--{!! nl2br(e(Str::limit(strip_tags($special_future->detail, 50)))) !!}--}}
                    </div>
                    */ ?>
                    <div class="feature-card">
                        @if($special_future->image)
                            <img src="{{ asset('/assets/images/' . $special_future->image) }}" alt="画像" class="feature-image">
                        @else
                            <img src="https://picsum.photos/seed/winter/200/200" alt="未設定画像" class="feature-image">
                        @endif

                        <div class="feature-text ms-3">
                            <p class="mb-1">{{ $special_future->name }}</p>
                            <p class="mb-0">{!! Str::limit($special_future->detail, 50) !!}</p>
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
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const swiper = new Swiper('.swiper-container', {
            slidesPerView: 8,
            spaceBetween: 20,
            centeredSlides: false, // ← 中央寄せ
            loop: true,
            autoplay: {
                delay: 2500, // 2.5秒ごとに自動スライド（ミリ秒単位）
                disableOnInteraction: false // ユーザー操作後も自動スライド継続
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev'
            },
            breakpoints: {
                // モバイル（0 ～ 479px）
                0: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                // スマホ小（480 ～ 767px）
                480: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                // タブレット以上
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
