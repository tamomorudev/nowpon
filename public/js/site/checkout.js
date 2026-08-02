/*
 * 決済ページ用処理。
 * Bladeからdata属性で渡されたStripe設定を使い、カード入力と決済API呼び出しを行う。
 */
document.addEventListener("DOMContentLoaded", function () {
    const root = document.getElementById("checkoutPayment");
    const payButton = document.getElementById("pay-button");
    const loading = document.getElementById("pay-loading");
    const errors = document.getElementById("card-errors");
    const cardElementContainer = document.getElementById("card-element");
    const cardLabel = root ? root.querySelector(".checkout-card-label") : null;

    if (!root || !payButton || !loading || !errors || !cardElementContainer) return;

    let cardLoadFailed = false;
    let cardReady = false;
    let cardReadyTimer = null;

    const showCardLoadError = function () {
        if (cardLoadFailed) return;

        cardLoadFailed = true;
        if (cardReadyTimer) {
            window.clearTimeout(cardReadyTimer);
        }
        if (cardLabel) {
            cardLabel.hidden = true;
        }
        cardElementContainer.replaceChildren();
        cardElementContainer.hidden = true;
        errors.textContent = "カード情報を読み込めませんでした。時間をおいてお試しください。";
        payButton.disabled = true;
    };

    const stripeKey = root.dataset.stripeKey ? root.dataset.stripeKey.trim() : "";
    if (!stripeKey || typeof Stripe === "undefined") {
        showCardLoadError();
        return;
    }

    let stripe;
    let cardElement;
    try {
        stripe = Stripe(stripeKey);
        const elements = stripe.elements();
        cardElement = elements.create("card", {
            hidePostalCode: true,
            style: {
                base: {
                    fontSize: "16px",
                    color: "#333"
                }
            }
        });

        payButton.disabled = true;
        cardElement.on("ready", function () {
            if (cardLoadFailed) return;

            cardReady = true;
            if (cardReadyTimer) {
                window.clearTimeout(cardReadyTimer);
            }
            payButton.disabled = false;
        });
        cardElement.mount("#card-element");
        cardReadyTimer = window.setTimeout(function () {
            if (!cardReady) {
                showCardLoadError();
            }
        }, 5000);
    } catch (error) {
        showCardLoadError();
        return;
    }

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
