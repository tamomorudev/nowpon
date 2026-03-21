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
        <p style="font-size: 14px; line-height: 1.6; margin-bottom: 30px;">
            購入が完了しました。購入履歴より詳細を確認してください。
        </p>

        <div class="order-button" style="text-align: center; margin-bottom: 30px;">
            <a href="/purchase/history" style="
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
