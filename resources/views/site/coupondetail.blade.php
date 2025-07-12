<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ナウポンTOP</title>
    <style>
        .coupon-detail {
            /* 上下 40px、左右 16px に */
            margin: 40px 16px;
            font-family: sans-serif;
        }
        .coupon-detail h2 {
            text-align: left;
            margin-left: 24px;
        }
        /* 上部ボタン */
        .coupon-detail .btn-group {
            display: flex;
            justify-content: left;
            gap: 12px;
            margin-bottom: 24px;
        }
        .coupon-detail .btn-group button {
            background: #fde68a;
            border: none;
            border-radius: 24px;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: bold;
            color: #555;
            cursor: pointer;
        }

        /* メイン／サムネカルーセルのレイアウト */
        .coupon-detail .detail-body {
            display: flex;
            gap: 40px;
            flex-wrap: wrap;
            justify-content: flex-start;
        }
        .coupon-detail .carousel-wrapper {
            flex: 1;
            min-width: 320px;
            max-width: 600px;
        }
        .coupon-detail .info-panel {
            flex: 1;
            min-width: 280px;
            max-width: 350px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* 商品内容タブ */
        .coupon-detail .info-panel .tab {
            align-self: flex-start;
            padding: 6px 80px;
            border: 2px solid #c29663;
            border-radius: 9999px;
            color: #c29663;
            font-weight: bold;
            font-size: 14px;
        }

        /* 商品情報テキスト */
        .coupon-detail .info-panel .info {
            line-height: 1.6;
            color: #333;
            font-size: 14px;
        }

        /* Swiper カード共通 */
        .swiper {
            position: relative;
        }
        .swiper-button-prev,
        .swiper-button-next {
            color: #1e40af;
        }

        /* メインスライダー */
        .swiper-main {
            width: 100%;
            height: 320px;
            margin-bottom: 12px;
        }
        .swiper-main .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        /* サムネイル */
        .swiper-thumbs {
            width: 100%;
            height: 80px;
        }
        .swiper-thumbs .swiper-slide {
            width: 20%;
            opacity: 0.4;
        }
        .swiper-thumbs .swiper-slide-thumb-active {
            opacity: 1;
        }
        .swiper-thumbs .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 4px;
        }


        /* 追加CSS */
        .recommend-section {
            max-width: 1200px;
            margin: 60px auto;
        }
        .recommend-separator {
            border: none;
            border-top: 2px solid #c29663;
            margin-bottom: 16px;
        }
        .recommend-title {
            font-size: 18px;
            color: #333;
            margin-bottom: 24px;
        }

        /* グリッド */
        .recommend-grid {
            display: grid;
            gap: 24px;
            /* デフォルトは 4 列 */
            grid-template-columns: repeat(4, 1fr);
        }

        /* 画面幅が 1024px 以下になったら 2 列へ */
        @media (max-width: 1024px) {
            .recommend-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* 画面幅が 600px 以下になったら 1 列へ */
        @media (max-width: 600px) {
            .recommend-grid {
                grid-template-columns: 1fr;
            }
        }

        /* 各アイテム */
        /* a.recommend-item に元のカードスタイルを適用 */
        a.recommend-item {
            display: flex;
            flex-direction: column;
            background: #fff;           /* 背景色 */
            border: 1px solid #ddd;     /* 枠線 */
            border-radius: 8px;         /* 角丸 */
            overflow: hidden;           /* はみ出し画像を丸に沿わせる */
            text-decoration: none;      /* 下線を消す */
            color: inherit;             /* 文字色を継承 */
            transition: box-shadow .2s, transform .2s; /* ホバーアニメ */
        }

        /* ホバー時のほんのり浮かせエフェクト */
        a.recommend-item:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        /* .recommend-grid のままだと a として扱われない場合は以下を追加 */
        .recommend-grid a.recommend-item {
            /* グリッド内のアイテムとして扱う */
            width: 100%;
        }
        .card-wrapper {
            position: relative;
        }
        .card-wrapper img {
            width: 100%;
            display: block;
        }
        /* 情報部 */
        .recommend-info {
            padding: 12px;
            font-size: 13px;
            line-height: 1.4;
            color: #333;
        }
        .recommend-info .price {
            font-weight: bold;
            margin: 4px 0;
        }

        /* フッター部：ドット＋アイコン */
        .recommend-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px 12px;
        }
        .icons {
            display: flex;
            gap: 8px;
        }
        .icon-btn {
            border: none;
            background: none;
            font-size: 16px;
            cursor: pointer;
        }
        .icon-btn.heart { color: #e74c3c; }
        .icon-btn.share { color: #555; }



    </style>
</head>

<body>
<div class="container">
    @include('layouts.header')

    <!-- クーポン詳細 -->
    <div class="coupon-detail">
        <h2>商品に関する情報</h2>

        <!-- 上部ボタン -->
        <div class="btn-group">
            <button type="button" onclick="location.href='/site/cart';">カートに入れる</button>
            <button type="button" onclick="location.href='/site/checkout';">今すぐ購入する</button>
        </div>

        <div class="detail-body">
            <!-- 左：カルーセル -->
            <div class="carousel-wrapper">
                <!-- メインスライダー -->
                <div class="swiper swiper-main">
                    <div class="swiper-wrapper">
                        @foreach (range(1,5) as $i)
                            <div class="swiper-slide">
                                <img src="https://picsum.photos/600/320?random={{ $i }}" alt="商品画像{{ $i }}">
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-pagination"></div>
                </div>
                <!-- サムネイルスライダー -->
                <div class="swiper swiper-thumbs">
                    <div class="swiper-wrapper">
                        @foreach (range(1,6) as $i)
                            <div class="swiper-slide">
                                <img src="https://picsum.photos/100/80?random={{ $i }}" alt="サムネ{{ $i }}">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- 右：商品情報パネル -->
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
        </div>


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
                                    <svg width="18" height="18" viewBox="0 0 24 24" preserveAspectRatio="xMidYMax meet" shape-rendering="crispEdges" fill="currentColor" style="display:block">
                                        <path d="M18 16.08c-.76 0-1.44.3-1.97.8l-7.12-4.18c.05-.23.09-.47.09-.7s-.04-.47-.09-.7l7.12-4.18c.53.5 1.21.8 1.97.8 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7l-7.12 4.18c-.5-.5-1.18-.8-1.91-.8C5.33 9.08 4 10.42 4 12s1.33 2.92 2.99 2.92c.74 0 1.42-.3 1.91-.8l7.12 4.18c-.05.23-.09.47-.09.7 0 1.66 1.34 3 3 3s3-1.34 3-3-1.34-3-3-3z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- 同じエリアでのクーポン -->
        <div class="recommend-section">
            <hr class="recommend-separator">
            <h3 class="recommend-title">同じエリアでのクーポン </h3>
            <div class="recommend-grid">
                @foreach (range(5,8) as $i)
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
                                    <svg width="18" height="18" viewBox="0 0 24 24" preserveAspectRatio="xMidYMax meet" shape-rendering="crispEdges" fill="currentColor" style="display:block">
                                        <path d="M18 16.08c-.76 0-1.44.3-1.97.8l-7.12-4.18c.05-.23.09-.47.09-.7s-.04-.47-.09-.7l7.12-4.18c.53.5 1.21.8 1.97.8 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7l-7.12 4.18c-.5-.5-1.18-.8-1.91-.8C5.33 9.08 4 10.42 4 12s1.33 2.92 2.99 2.92c.74 0 1.42-.3 1.91-.8l7.12 4.18c-.05.23-.09.47-.09.7 0 1.66 1.34 3 3 3s3-1.34 3-3-1.34-3-3-3z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>


    </div>
</div>

    <!-- Swiper JS 読み込み -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // サムネイルスライダー初期化
            const thumbs = new Swiper('.swiper-thumbs', {
                spaceBetween: 8,
                slidesPerView: 5,
                watchSlidesProgress: true
            });
            // メインスライダー初期化
            new Swiper('.swiper-main', {
                spaceBetween: 12,
                loop: true,
                centeredSlides: true,
                pagination: { el: '.swiper-pagination', clickable: true },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev'
                },
                thumbs: { swiper: thumbs }
            });
        });
    </script>

</body>
@include('layouts.footer')
</html>
