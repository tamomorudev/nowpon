<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ナウポンTOP</title>
    <style>
        .cart-section {
            padding: 40px 20px;
            font-family: 'Nunito', sans-serif;
        }
        .cart-header {
            font-weight: bold;
            margin-bottom: 20px;
            font-size: 18px;
            color: #6b4e3d;
        }
        .order-button {
            background-color: #f9e98f;
            border-radius: 20px;
            padding: 12px 24px;
            font-weight: bold;
            color: #555;
            margin-bottom: 30px;
            display: inline-block;
            font-size: 16px;
        }
        @media (max-width: 768px) {
            .cart-section {
                text-align: center;
            }
            .order-button {
                display: inline-block; /* text-align:centerの効果を受けるため */
                margin: 0 auto 30px;
            }
            .cart-header {
                text-align: center;
            }
        }

        .product-content {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            border-top: 1px solid #ccc;
            padding-top: 20px;

            /* ↓これ追加 */
            padding-bottom: 40px;
            min-height: 300px;
        }
        .product-image {
            flex: 1 0 320px;
            max-width: 320px;
            height: 300px;
            overflow: hidden;
            flex-shrink: 0;
        }
        @media (max-width: 768px) {
            .product-content {
                flex-direction: column;
                align-items: center; /* ← これが中央寄せの主役 */
            }
            .product-image {
                margin: 0 auto;
            }
            .product-info {
                margin: 0 auto;
            }
        }
        .swiper-container {
            width: 100%;
            height: 100%;
            position: relative;
        }
        .swiper-wrapper {
            height: 100%;
        }
        .swiper-slide {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }
        .swiper-button-prev,
        .swiper-button-next {
            color: #c29663;
        }

        .product-info {
            flex: 2;
            font-size: 16px;            /* ← 文字を大きく */
            line-height: 1.8;           /* ← 行間も広めに */
            color: #333;
            position: relative;
            padding: 0px 10px 40px 40px; /* ← 左に余白追加 */
            box-sizing: border-box;    /* ← padding を含める */
        }
        .product-info-header {
            font-size: 16px;
            color: #8b5e3c;
            border: 1px solid #8b5e3c;
            display: inline-block;
            padding: 4px 130px;
            border-radius: 20px;
            margin-bottom: 10px;
        }
        @media (max-width: 768px) {
            .product-info {
                margin: 0 auto;
                text-align: center;
                padding: 20px;
            }

            .product-info-header {
                margin-left: auto;
                margin-right: auto;
            }
        }

        .trash-icon {
            position: absolute;  /* ← 絶対配置に */
            bottom: 0;
            right: 0;
        }

        .delete-button {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s;
        }

        .delete-button:hover svg {
            transform: scale(1.2);
        }
    </style>
</head>
<body>
<div class="container">
@include('layouts.header')
    <!-- カートセクション -->
    <div class="cart-section container">
        <div class="cart-header">カート内の商品に関する情報</div>
        <a href="/site/checkout" class="order-button">注文に進む（3000円税込）</a>

        <div class="product-content">
            <div class="product-image">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        @foreach (range(1, 6) as $i)
                            <div class="swiper-slide">
                                <img src="https://picsum.photos/320/200?random={{ $i }}" alt="施術イメージ{{ $i }}">
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
            </div>

            <div class="product-info">
                <div class="product-info-header">商品内容</div>
                <div>ジャンルー店舗名</div>
                <div>〇〇駅 北口徒歩何分</div>
                <div>￥3000（40％off）</div>
                <div>予約日時：2025年3月1日<br>16時〜</div>
                <div>コース名</div>
                <div class="trash-icon">
                    <button class="delete-button" aria-label="削除">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#c29663" viewBox="0 0 24 24">
                            <path d="M3 6h18v2H3V6zm2 3h14l-1.5 12.5a1 1 0 0 1-1 .5H7a1 1 0 0 1-1-.5L4.5 9zm5 2v8h2v-8H9zm4 0v8h2v-8h-2zM9 4V3h6v1h5v2H4V4h5z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
    const swiper = new Swiper('.swiper-container', {
        loop: true,
        slidesPerView: 1,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        autoplay: {
            delay: 2500, // 2.5秒ごとに自動スライド（ミリ秒単位）
            disableOnInteraction: false // ユーザー操作後も自動スライド継続
        },
    });
</script>

@include('layouts.footer')
</body>
</html>
