/*
 * 決済ページ用処理。
 * Bladeからdata属性で渡されたStripe設定を使い、カード入力と決済API呼び出しを行う。
 */
document.addEventListener("DOMContentLoaded", function () {
    const root = document.getElementById("checkoutPayment");
    const payButton = document.getElementById("pay-button");
    const loading = document.getElementById("pay-loading");
    const errors = document.getElementById("card-errors");

    if (!root || !payButton || !loading || !errors || typeof Stripe === "undefined") return;

    const stripe = Stripe(root.dataset.stripeKey);
    const elements = stripe.elements();
    const cardElement = elements.create("card", {
        hidePostalCode: true,
        style: {
            base: {
                fontSize: "16px",
                color: "#333"
            }
        }
    });

    cardElement.mount("#card-element");

    const setLoading = function (isLoading) {
        payButton.disabled = isLoading;
        loading.hidden = !isLoading;
    };

    const showError = function (message) {
        errors.textContent = message || "";
    };

    payButton.addEventListener("click", async function () {
        setLoading(true);
        showError("");

        const { paymentMethod, error } = await stripe.createPaymentMethod({
            type: "card",
            card: cardElement
        });

        if (error) {
            showError(error.message);
            setLoading(false);
            return;
        }

        // サーバ側で決済を実行し、必要なら3Dセキュア認証に進む。
        try {
            const response = await fetch(root.dataset.chargeUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": root.dataset.csrfToken
                },
                body: JSON.stringify({
                    payment_method_id: paymentMethod.id,
                    cid: root.dataset.couponCode
                })
            });

            const result = await response.json();

            if (result.success) {
                window.location.href = result.redirect;
                return;
            }

            if (result.requires_action) {
                const { error: confirmError } = await stripe.handleCardAction(result.client_secret);

                if (confirmError) {
                    showError("認証に失敗しました。もう一度お試しください。");
                    setLoading(false);
                }

                return;
            }

            showError(result.message || "決済に失敗しました。もう一度お試しください。");
            setLoading(false);
        } catch (error) {
            showError("通信エラーが発生しました。もう一度お試しください。");
            setLoading(false);
        }
    });
});
