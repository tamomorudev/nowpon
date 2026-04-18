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

        #card-element {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 12px;
            max-width: 400px;
            margin: 0 auto 12px;
            background: #fff;
        }
        #card-errors {
            color: #e74c3c;
            font-size: 14px;
            margin-bottom: 12px;
            min-height: 20px;
        }
        #pay-loading {
            font-size: 14px;
            color: #888;
            margin-bottom: 12px;
        }
        #pay-button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>

    <script src="https://js.stripe.com/v3/"></script>
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

        <div style="max-width: 400px; margin: 0 auto 16px; text-align: left;">
            <p style="font-size: 14px; font-weight: bold; margin-bottom: 8px;">カード情報を入力してください</p>
            <div id="card-element"></div>
            <div id="card-errors"></div>
            @if(request('3ds_error'))
                <div style="color: red; font-size: 14px; margin-bottom: 12px;">
                    3Dセキュア認証に失敗しました。もう一度お試しください。
                </div>
            @endif
        </div>

        <div class="order-button" style="text-align: center; margin-bottom: 30px;">
            <button id="pay-button" type="button" style="
                background-color: #f9e98f;
                color: #000;
                font-weight: bold;
                font-size: 16px;
                border: none;
                border-radius: 20px;
                padding: 12px 40px;
                cursor: pointer;
            ">
                注文を確定する
            </button>
        </div>

        <div id="pay-loading" style="display:none;">決済処理中です。そのままでしばらくお待ちください。</div>

        <!-- 🔻 線追加（ボタン下） -->
        <hr style="border: none; border-top: 3px solid #b08968; width: 100%; max-width: 400px; margin: 0 auto 30px;" />

        <table style="width: 100%; max-width: 400px; margin: 0 auto 30px; font-size: 16px;">
            <tr>
                <td style="text-align: left;">商品の小計：</td>
                @if ($coupon->discount_rate > 0)
                    <td style="text-align: right;">￥{{ number_format(round($coupon->store_pay_price)) }}</td>
                @else
                    <td style="text-align: right;">￥{{ number_format($coupon->price) }}</td>
                @endif
            </tr>
            <tr>
                <td style="text-align: left;">手数料：</td>
                @if ($coupon->discount_rate > 0)
                    <td style="text-align: right;">￥{{ number_format($coupon->service_price) }}</td>
                @else
                    <td style="text-align: right;">￥{{ number_format($coupon->original_service_price) }}</td>
                @endif
            </tr>
            <tr style="font-weight: bold;">
                <td style="text-align: left;">ご請求：</td>
                @if ($coupon->discount_rate > 0)
                    <td style="text-align: right;">￥{{ number_format(round($coupon->store_pay_price) + $coupon->service_price) }}</td>
                @else
                    <td style="text-align: right;">￥{{ number_format($coupon->price + $coupon->original_service_price) }}</td>
                @endif
            </tr>
        </table>

        <?php /*一旦都度入力のためコメントアウト* /
        <!-- 🔻 線追加（ご請求の下） -->
        <hr style="border: none; border-top: 3px solid #b08968; width: 100%; max-width: 400px; margin: 0 auto 30px;" />

        <div style="max-width: 400px; margin: 0 auto; font-size: 16px;">
            <div style="font-weight: bold; margin-bottom: 5px;">お支払い方法：カード名</div>
            <div style="font-size: 14px; color: #555;">
                <a href="#" style="color: #555; text-decoration: underline;">お支払い方法を変更する</a>
            </div>
        </div>
        */?>
    </div>
</div>
@include('layouts.footer')

<script>
    const stripe = Stripe('{{ config("services.stripe.key") }}');
    const elements = stripe.elements();
    const cardElement = elements.create('card', {
        hidePostalCode: true,
        style: {
            base: {
                fontSize: '16px',
                color: '#333',
            }
        }
    });
    cardElement.mount('#card-element');

    document.getElementById('pay-button').addEventListener('click', async () => {
        document.getElementById('pay-button').disabled = true;
        document.getElementById('pay-loading').style.display = 'block';
        document.getElementById('card-errors').textContent = '';

        const { paymentMethod, error } = await stripe.createPaymentMethod({
            type: 'card',
            card: cardElement,
        });

        if (error) {
            document.getElementById('card-errors').textContent = error.message;
            document.getElementById('pay-button').disabled = false;
            document.getElementById('pay-loading').style.display = 'none';
            return;
        }

        //決済処理
        try {
            const response = await fetch('{{ route("checkout.charge") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    payment_method_id: paymentMethod.id,
                    cid: '{{ $coupon->coupon_code }}',
                }),
            });

            const result = await response.json();

            if (result.success) {
                //決済成功
                window.location.href = result.redirect;

            } else if (result.requires_action) {
                //3Dセキュア認証
                const { error: confirmError, paymentIntent } = await stripe.handleCardAction(
                    result.client_secret
                );

                let finalPaymentIntent = paymentIntent;

                if (confirmError) {
                    //3Dセキュア認証失敗
                    document.getElementById('card-errors').textContent = '認証に失敗しました。もう一度お試しください。';
                    document.getElementById('pay-button').disabled = false;
                    document.getElementById('pay-loading').style.display = 'none';
                }
            } else {
                document.getElementById('card-errors').textContent = result.message ?? '決済に失敗しました。もう一度お試しください。';
                document.getElementById('pay-button').disabled = false;
                document.getElementById('pay-loading').style.display = 'none';
            }
        } catch (e) {
            document.getElementById('card-errors').textContent = '通信エラーが発生しました。もう一度お試しください。';
            document.getElementById('pay-button').disabled = false;
            document.getElementById('pay-loading').style.display = 'none';
        }
    });
</script>
</body>
</html>
