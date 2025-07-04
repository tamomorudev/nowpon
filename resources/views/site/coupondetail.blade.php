<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ナウポンTOP</title>
    <style>
        .coupon-detail {
            margin: 40px 0 40px 24px;
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
        .recommend-item {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
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
            <button type="button">カートに入れる</button>
            <button type="button">今すぐ購入する</button>
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
                    <div class="recommend-item">
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
                                <button class="icon-btn share">⤴︎</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 同じエリアでのクーポン -->
        <div class="recommend-section">
            <hr class="recommend-separator">
            <h3 class="recommend-title">同じエリアでのクーポン </h3>
            <div class="recommend-grid">
                @foreach (range(5,8) as $i)
                    <div class="recommend-item">
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
                                <button class="icon-btn share">⤴︎</button>
                            </div>
                        </div>
                    </div>
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
