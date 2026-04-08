<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ナウポンTOP|購入完了</title>
    <style>
        .cart-section {
            padding: 40px 20px;
            font-family: 'Nunito', sans-serif;
            text-align: center;
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
            .order-button {
                display: inline-block; /* text-align:centerの効果を受けるため */
                margin: 0 auto 30px;
            }
        }
    </style>
</head>
<body>
<div class="container">
@include('layouts.header')
    <div class="cart-section container">
        <p style="font-size: 25px; line-height: 1.6; margin-bottom: 15px;">
            注文が確定しました。<br>
            ご購入ありがとうございます。
        </p>
        <p style="font-size: 14px; line-height: 1.6; margin-bottom: 30px;">
            確認メールが送信されます
        </p>

        <!-- 🔻 線追加（ボタン下） -->
        <hr style="border: none; border-top: 3px solid #b08968; width: 100%; max-width: 400px;" />

        <p style="font-size: 16px; line-height: 1.6; margin-bottom: 15px;">
            @if (!empty($coupon->url))
                <a href="{{$coupon->url}}" target="_blank" rel="noopener noreferrer" style="font-weight: bold; color: #6fa8dc; text-decoration: none;">{{$coupon->store_name}}</a><br>
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

<p style="font-size: 20px; line-height: 1.6; margin-bottom: 15px;">
予約日時:{{$coupon->cource_start}}〜<br>
コース名：{{$coupon->coupon_name}}
</p>

<div class="order-button" style="text-align: center; margin-bottom: 30px;">
<a href="/site/purchase_history" style="
    display: inline-block;
    background-color: #f9e98f;
    color: #000;
    font-weight: bold;
    font-size: 16px;
    border: none;
    border-radius: 20px;
    padding: 12px 40px;
    cursor: pointer;
    text-decoration: none;
">
    購入履歴へ
</a>
</div>
</div>
</div>
@include('layouts.footer')
</body>
</html>
