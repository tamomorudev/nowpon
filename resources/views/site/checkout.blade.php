<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/site/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/site/purchase-flow.css') }}">
    <title>ナウポンTOP</title>

    <script src="https://js.stripe.com/v3/"></script>
    <script src="{{ asset('js/site/checkout.js') }}" defer></script>
</head>
<body>
<div class="container">
@include('layouts.header')
    <div class="cart-section cart-section--center container" id="checkoutPayment" data-stripe-key="{{ config('services.stripe.key') }}" data-charge-url="{{ route('checkout.charge') }}" data-csrf-token="{{ csrf_token() }}" data-coupon-code="{{ $coupon->coupon_code }}">
        <p class="checkout-note">
            「注文を確定する」ボタンを押してご注文いただくことで、お客様は当サイトの各種規約、プライバシー規約および当サイト上の販売条件に同意の上、商品をご注文されたことになります。価格については必ず商品ページおよびこちらをご確認ください。カードをご利用の場合、お客様のご注文に関する情報を、不正検出・防止のため、カード発行会社（「発行会社」）に提供します。発行会社が外国に所在する場合には、当該外国への情報提供となる場合があります。
        </p>

        <p class="checkout-note">
            nowponにおいては、発行会社をあらかじめ特定することが困難であるため、発行会社及びその所在国についてはお客様ご自身においてご確認ください。所在国が外国である場合における各国の個人情報保護制度に関する制度の参考情報については、個人情報保護委員会による情報提供をご参照ください。
        </p>

        <div class="checkout-card-box">
            <p class="checkout-card-label">カード情報を入力してください</p>
            <div id="card-element"></div>
            <div id="card-errors"></div>
            @if(request('3ds_error'))
                <div class="checkout-error-message">
                    3Dセキュア認証に失敗しました。もう一度お試しください。
                </div>
            @endif
        </div>

        <div class="checkout-action">
            <button id="pay-button" type="button" class="checkout-pay-button">
                注文を確定する
            </button>
        </div>

        <div id="pay-loading" class="checkout-loading" hidden>決済処理中です。そのままでしばらくお待ちください。</div>

        <!-- 🔻 線追加（ボタン下） -->
        <hr class="checkout-divider" />

        <table class="checkout-summary-table">
            <tr>
                <td class="checkout-summary-label">店舗支払金額：</td>
                @if ($coupon->discount_rate > 0)
                    <td class="checkout-summary-price">￥{{ number_format(round($coupon->store_pay_price)) }}</td>
                @else
                    <td class="checkout-summary-price">￥{{ number_format($coupon->price) }}</td>
                @endif
            </tr>
            <tr>
                <td class="checkout-summary-label">手数料：</td>
                @if ($coupon->discount_rate > 0)
                    <td class="checkout-summary-price">￥{{ number_format($coupon->service_price) }}</td>
                @else
                    <td class="checkout-summary-price">￥{{ number_format($coupon->original_service_price) }}</td>
                @endif
            </tr>
            <tr class="checkout-summary-total">
                <td class="checkout-summary-label">ご請求：</td>
                @if ($coupon->discount_rate > 0)
                    <td class="checkout-summary-price">￥{{ number_format($coupon->service_price) }}</td>
                @else
                    <td class="checkout-summary-price">￥{{ number_format($coupon->original_service_price) }}</td>
                @endif
            </tr>
        </table>

        <?php /*一旦都度入力のためコメントアウト* /
        <!-- 🔻 線追加（ご請求の下） -->
        <hr class="checkout-divider" />

        <div class="checkout-payment-method">
            <div class="checkout-payment-title">お支払い方法：カード名</div>
            <div class="checkout-payment-change">
                <a href="#">お支払い方法を変更する</a>
            </div>
        </div>
        */?>
    </div>
</div>
@include('layouts.footer')

</body>
</html>
