<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/site/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/site/purchase-flow.css') }}">
    <title>ナウポンTOP</title>
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
                <div class="swiper-container cart-swiper">
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
<script src="{{ asset('js/site/cart.js') }}" defer></script>

@include('layouts.footer')
</body>
</html>
