<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ナウポンTOP</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f3f4f6;
            margin-top: 10px;
            margin-right: 10px;
            margin-left: 10px;
            margin-bottom: 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* ヘッター ここから*/
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 24px 12px; /* ← 上方向0に、左右は24px、下だけ余白 */
            border-bottom: 3px solid #c29663;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 10px; /* ← 念のため追加 */
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 16px;
            color: #6b4e3d;
        }
        .user-icon {
            font-size: 20px;
            color: #b08968;
        }
        .header-search {
            flex: 1;
            display: flex;
            justify-content: flex-start; /* ← 左寄せ */
        }
        .search-box {
            background: #d1dbe9;
            border-radius: 9999px;
            padding: 8px 16px;
            display: flex;
            align-items: center;
            width: 100%;
            max-width: 320px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .search-box input {
            border: none;
            background: transparent;
            outline: none;
            flex: 1;
            font-size: 16px;
        }
        .search-icon {
            color: #b08968;
            font-size: 18px;
            margin-right: 8px;
        }
        .header-nav {
            display: flex;
            gap: 16px;
            font-size: 14px;
            align-items: center;
            white-space: nowrap;
        }
        .header-nav a {
            color: #111;
            text-decoration: none;
            font-weight: 600;
        }
        .header-nav a.active {
            color: #b08968;
            font-weight: bold;
        }
        /* ヘッター ここまで*/

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
            grid-template-columns: repeat(2, 1fr); /* ← ここを2列に固定 */
            gap: 20px;
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

        /* フッターここから */
        .site-footer {
            background-color: #b08968;
            color: white;
            padding: 24px 0;
            margin-top: 60px;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 24px;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
        }

        .footer-links a:hover {
            text-decoration: underline;
            opacity: 0.9;
        }
        .copyright {
            font-size: 13px;
            opacity: 0.9;
        }
        /* フッター ここまで */
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="header-left">
            <span class="user-icon">👤</span>
            <span class="username">guest</span>
        </div>

        <div class="header-search">
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input type="text" placeholder="Search" />
            </div>
        </div>

        <div class="header-nav">
            <a href="#" class="active">HOME</a>
            <a href="#">カート</a>
            <a href="#">購入履歴</a>
            <a href="#">CONTACT</a>
        </div>
    </div>

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
        <button class="active">お気に入り</button>
        <button>エリア</button>
        <button>割引率</button>
        <button>ジャンル</button>
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
                0: { slidesPerView: 1 },
                768: { slidesPerView: 2 },
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

<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-links">
            <a href="#">利用規約</a>
            <a href="#">プライバシーポリシー</a>
            <a href="#">お問い合わせ</a>
        </div>
        <p class="copyright">© 2025 ナウポン</p>
    </div>
</footer>
</html>
