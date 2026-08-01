/*
 * 購入履歴ページ用処理。
 * キャンセルモーダルへの購入情報反映と、購入履歴カードの4件ページングを行う。
 */
document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("cancelModal");
    const openButtons = document.querySelectorAll(".js-open-cancel-modal");
    const closeButton = document.getElementById("closeCancelModal");
    const modalImage = document.getElementById("cancelModalImage");
    const cancelForm = document.querySelector(".cancel-modal-bottom form");
    const cancelMsg = document.getElementById("cancelMessage");
    let lastFocusedElement = null;

    // モーダル内の同じclassを持つ表示箇所をまとめて更新する。
    const setTextAll = function (selector, value) {
        document.querySelectorAll(selector).forEach(function (element) {
            element.textContent = value || "";
        });
    };

    const formatNumber = function (value) {
        return Number(value || 0).toLocaleString();
    };

    const closeModal = function () {
        if (!modal) return;

        modal.classList.remove("is-open");
        modal.setAttribute("aria-hidden", "true");
        document.body.classList.remove("modal-open");

        if (lastFocusedElement) {
            lastFocusedElement.focus();
            lastFocusedElement = null;
        }
    };

    const trapModalFocus = function (event) {
        if (!modal || !modal.classList.contains("is-open") || event.key !== "Tab") return;

        const focusable = Array.from(modal.querySelectorAll(
            'a[href], button:not([disabled]), select:not([disabled]), input:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
        )).filter(function (element) { return !element.hidden && element.offsetParent !== null; });

        if (focusable.length === 0) return;

        const first = focusable[0];
        const last = focusable[focusable.length - 1];
        if (event.shiftKey && document.activeElement === first) {
            event.preventDefault();
            last.focus();
        } else if (!event.shiftKey && document.activeElement === last) {
            event.preventDefault();
            first.focus();
        }
    };

    // 購入履歴カードをクリックした時に、data属性からモーダルへ情報を流し込む。
    if (modal && openButtons.length > 0) {
        openButtons.forEach(function (button) {
            button.addEventListener("click", function () {
                const d = button.dataset;
                lastFocusedElement = button;

                if (modalImage) {
                    modalImage.src = d.image;
                    modalImage.alt = d.storename || "クーポン画像";
                }

                const hiddenCode = document.querySelector(".modal-hidden-code");
                if (hiddenCode) hiddenCode.value = d.code;

                if (cancelForm && cancelMsg) {
                    const canCancel = d.pat === "0";
                    cancelForm.classList.toggle("is-hidden", !canCancel);

                    if (d.pat === "1") {
                        cancelMsg.textContent = "利用時間の1時間前を過ぎているため、キャンセルできません。";
                    } else if (d.pat === "2") {
                        cancelMsg.textContent = "利用日時を過ぎているため、キャンセルできません。";
                    } else {
                        cancelMsg.textContent = "";
                    }
                }

                // 注文情報・店舗情報をモーダルに反映する。
                setTextAll(".modal-pdate", d.pdate);
                setTextAll(".modal-udate", d.udate);
                setTextAll(".modal-code", d.code);
                setTextAll(".modal-price", formatNumber(d.price));
                setTextAll(".modal-storeprice", formatNumber(d.storeprice));
                setTextAll(".modal-serviceprice", formatNumber(d.serviceprice));
                setTextAll(".modal-serviceprice2", formatNumber(d.serviceprice2));
                setTextAll(".modal-totalprice", formatNumber(d.totalprice));
                setTextAll(".modal-storename", d.storename);
                setTextAll(".modal-genre", d.genre);
                setTextAll(".modal-tel", d.tel);
                setTextAll(".modal-address", d.address);

                modal.classList.add("is-open");
                modal.setAttribute("aria-hidden", "false");
                document.body.classList.add("modal-open");
                if (closeButton) closeButton.focus();
            });
        });

        if (closeButton) {
            closeButton.addEventListener("click", closeModal);
        }

        modal.addEventListener("click", function (event) {
            if (event.target === modal) closeModal();
        });

        document.addEventListener("keydown", function (event) {
            if (event.key === "Escape") closeModal();
            trapModalFocus(event);
        });
    }

    // 購入履歴カードを4件ずつ表示するページャー。
    const ITEMS_PER_PAGE = 4;
    const grid = document.querySelector(".purchase-history-grid");
    const pager = document.getElementById("purchasePager");

    if (!grid || !pager) return;

    const getCards = function () {
        return Array.from(grid.querySelectorAll(".purchase-card"));
    };

    const showPage = function (page) {
        const cards = getCards();
        const totalPages = Math.ceil(cards.length / ITEMS_PER_PAGE);

        cards.forEach(function (card, index) {
            const inPage = index >= (page - 1) * ITEMS_PER_PAGE && index < page * ITEMS_PER_PAGE;
            card.hidden = !inPage;
        });

        renderPager(page, totalPages);
        window.scrollTo({ top: 0, behavior: "smooth" });
    };

    const renderPager = function (current, total) {
        pager.innerHTML = "";
        if (total <= 1) return;

        const prev = document.createElement("button");
        prev.type = "button";
        prev.className = "pager-btn pager-arrow";
        prev.textContent = "‹";
        prev.setAttribute("aria-label", "前のページ");
        prev.disabled = current === 1;
        prev.addEventListener("click", function () { showPage(current - 1); });
        pager.appendChild(prev);

        for (let page = 1; page <= total; page++) {
            const btn = document.createElement("button");
            btn.type = "button";
            btn.className = "pager-btn" + (page === current ? " is-active" : "");
            btn.textContent = page;
            btn.setAttribute("aria-label", page + "ページ目");
            if (page === current) btn.setAttribute("aria-current", "page");
            btn.addEventListener("click", function () { showPage(page); });
            pager.appendChild(btn);
        }

        const next = document.createElement("button");
        next.type = "button";
        next.className = "pager-btn pager-arrow";
        next.textContent = "›";
        next.setAttribute("aria-label", "次のページ");
        next.disabled = current === total;
        next.addEventListener("click", function () { showPage(current + 1); });
        pager.appendChild(next);
    };

    showPage(1);
});
