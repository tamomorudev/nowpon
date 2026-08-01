<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/site/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/site/purchase-flow.css') }}">
    <title>購入完了 | ナウポン</title>
</head>
<body>
<div class="container">
@include('layouts.header')
    <div class="cart-section cart-section--center container">
        <p class="checkout-complete-title">
            注文が確定しました。<br>
            ご購入ありがとうございます。
        </p>
        <p class="checkout-complete-note">
            確認メールが送信されます
        </p>

        <!-- 🔻 線追加（ボタン下） -->
        <hr class="checkout-divider" />

        <p class="checkout-complete-shop">
            @if (!empty($coupon->url))
                <a href="{{$coupon->url}}" target="_blank" rel="noopener noreferrer" class="checkout-complete-shop-link">{{$coupon->store_name}}</a><br>
            @else
                {{$coupon->store_name}}<br>
            @endif
            @if (!empty($coupon->line))
            {{$coupon->line}}線 {{$coupon->station}}駅 {{config('commons.transportation')[$coupon->transportation]}}{{$coupon->time}}分<br>
            @endif
            @if (!empty($coupon->line_2))
                {{$coupon->line_2}}線 {{$coupon->station_2}}駅 {{config('commons.transportation')[$coupon->transportation_2]}}{{$coupon->time_2}}分<br>
            @endif
            @if (!empty($coupon->map))
                Map:{{$coupon->map}}<br>
            @endif
            @if (!empty($coupon->phone_number))
                TEL：{{$coupon->phone_number}}<br>
            @endif
        </p>

<p class="checkout-complete-reservation">
予約日時:{{$coupon->cource_start}}〜<br>
コース名：{{$coupon->coupon_name}}
</p>

<div class="checkout-action">
<a href="/site/purchase_history" class="checkout-history-link">
    購入履歴へ
</a>
</div>
</div>
</div>
@include('layouts.footer')
</body>
</html>
