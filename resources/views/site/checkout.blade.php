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
            「注文を確定する」ボタンを押してご注文いただくことで、お客様は当サイトの各種規約、プライバシー規約および当サイト上の販売条件に同意の上、商品をご注文されたことになります。価格については必ず商品ページおよびこちらをご確認ください。カードをご利用の場合、お客様のご注文に関する情報を、不正検出・防止のため、カード発行会社（「発行会社」）に提供します。発行会社が外国に所在する場合には、当該外国への情報提供となる場合があります。
        </p>

        <p style="font-size: 14px; line-height: 1.6; margin-bottom: 30px;">
            nowponにおいては、発行会社をあらかじめ特定することが困難であるため、発行会社及びその所在国についてはお客様ご自身においてご確認ください。所在国が外国である場合における各国の個人情報保護制度に関する制度の参考情報については、個人情報保護委員会による情報提供をご参照ください。
        </p>

        <div class="order-button" style="text-align: center; margin-bottom: 30px;">
            <button style="
            background-color: #f9e98f;
            color: #999;
            font-weight: bold;
            font-size: 16px;
            border: none;
            border-radius: 20px;
            padding: 12px 40px;
            cursor: not-allowed;
        " disabled>注文を確定する</button>
        </div>

        <!-- 🔻 線追加（ボタン下） -->
        <hr style="border: none; border-top: 3px solid #b08968; width: 100%; max-width: 400px; margin: 0 auto 30px;" />

        <table style="width: 100%; max-width: 400px; margin: 0 auto 30px; font-size: 16px;">
            <tr>
                <td style="text-align: left;">商品の小計：</td>
                <td style="text-align: right;">￥3000</td>
            </tr>
            <tr>
                <td style="text-align: left;">手数料：</td>
                <td style="text-align: right;">￥0</td>
            </tr>
            <tr style="font-weight: bold;">
                <td style="text-align: left;">ご請求：</td>
                <td style="text-align: right;">￥3000</td>
            </tr>
        </table>

        <!-- 🔻 線追加（ご請求の下） -->
        <hr style="border: none; border-top: 3px solid #b08968; width: 100%; max-width: 400px; margin: 0 auto 30px;" />

        <div style="max-width: 400px; margin: 0 auto; font-size: 16px;">
            <div style="font-weight: bold; margin-bottom: 5px;">お支払い方法：カード名</div>
            <div style="font-size: 14px; color: #555;">
                <a href="#" style="color: #555; text-decoration: underline;">お支払い方法を変更する</a>
            </div>
        </div>
    </div>
</div>
@include('layouts.footer')
</body>
</html>
