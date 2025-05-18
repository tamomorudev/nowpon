<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ナウポンTOP</title>
     <style>
        /* カルーセル ここから */
        .carousel-wrapper {
            padding: 20px 0;
        }
        .swiper-container {
            overflow: hidden;
        }
        .swiper-wrapper {
            padding: 0; /* padding削除 */
        }
        .swiper-slide {
            display: flex;
            justify-content: center;
        }
        .card {
            width: 100%;
            max-width: 320px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 12px;
            position: relative;
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

        /* 検索スペース ここから */
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
        /* 検索スペース ここまで */

        /* 特集 ここから */
        .feature-section {
            margin-top: 60px;
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
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            align-items: center;
        }
        .feature-card img {
            width: 100px;
            height: 100px;
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
    <div class="carousel-wrapper">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                @foreach (range(1, 6) as $i)
                    <div class="swiper-slide">
                        <div class="card">
                            <img src="https://picsum.photos/320/200?random={{ $i }}" alt="店舗画像" />
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
                    </div>
                @endforeach
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
        <div class="coupon-item">
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
        <div class="coupon-item">
            <div class="coupon-title">
                <span class="new-badge">NEW!</span>
                <span class="fading-text">残り110分</span>｜ジェルネイル（ワンカラー）｜表参道ネイルルーム｜表参道駅 徒歩2分
            </div>
            <div class="coupon-price">
                <span class="discount-rate">40%OFF</span>
                <span class="price-before">通常5,000円</span>
                <span class="price-after">→ 3,000円</span>
            </div>
        </div>
        <div class="coupon-item">
            <div class="coupon-title">
                <span class="fading-text">あと30分</span>｜カット＋パーマ（男性歓迎）｜池袋ヘアサロンM｜池袋駅 徒歩5分
            </div>
            <div class="coupon-price">
                <span class="discount-rate">30%OFF</span>
                <span class="price-before">通常7,800円</span>
                <span class="price-after">→ 5,460円</span>
            </div>
        </div>
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

    <div class="feature-section">
        <div class="feature-header">
            <h2 style="display: flex; align-items: center; font-size: 20px;">
                <span style="font-size: 20px; margin-right: 8px;">📰</span>
                特集
            </h2>
            <a href="#" style="color: #3b82f6; font-size: 14px;">もっと見る ＞</a>
        </div>
        <div class="feature-grid">
            <div class="feature-card">
                <img src="https://picsum.photos/seed/winter/100/100" alt="Feature 1">
                <p>冬の特別キャンペーン！<br>掲載中の美容室全メニュー 30% OFF！<br>この冬、特別な自分に変身しませんか？<br>期間限定：〜12月31日まで ご予約はお早めに！</p>
            </div>
            <div class="feature-card">
                <img src="https://picsum.photos/seed/discount/100/100" alt="Feature 2">
                <p>スーパーゲリラクーポン配布中！<br>残り数分のラストチャンス 超超お得な割引チャンス！<br>急いでゲットしよう！今すぐチェック！</p>
            </div>
            <div class="feature-card">
                <img src="https://picsum.photos/seed/vip/100/100" alt="Feature 3">
                <p>会員限定特典<br>会員向けに特別な割引をいち早くサービスを提供。</p>
            </div>
            <div class="feature-card">
                <img src="https://picsum.photos/seed/coupon/100/100" alt="Feature 4">
                <p>連続来店割引付きクーポン<br>初回50%OFFクーポン＋<br>次回使える30%OFFクーポン</p>
            </div>
        </div>

        <div class="bottom-buttons">
            <a href="/register" class="btn-register">まずは会員登録</a>
            <a href="/login" class="btn-login">ログイン</a>
        </div>
    </div>
</div>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const swiper = new Swiper('.swiper-container', {
            slidesPerView: 3,
            spaceBetween: 20,
            centeredSlides: true, // ← 中央寄せ
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
                0: { slidesPerView: 2, spaceBetween: 40},
                480: {slidesPerView: 2, spaceBetween: 40},
                768: { slidesPerView: 2.5 },
                1024: { slidesPerView: 3 }
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
