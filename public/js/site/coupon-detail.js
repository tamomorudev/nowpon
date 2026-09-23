/*
 * クーポン詳細ページ用処理。
 * メイン画像スライダーとサムネイルスライダーを連動させる。
 */
document.addEventListener("DOMContentLoaded", function () {
    if (typeof Swiper === "undefined") return;

    document.querySelectorAll(".swiper-main").forEach(function (mainElement) {
        const gallery = mainElement.closest(".coupon-gallery") || mainElement.parentElement;
        const wrapper = mainElement.querySelector(".swiper-wrapper");
        const slideCount = wrapper
            ? Array.from(wrapper.children).filter(function (slide) {
                return slide.classList.contains("swiper-slide");
            }).length
            : 0;

        // Static galleries do not need navigation, loop clones, or Swiper sizing.
        if (slideCount <= 1) return;

        const thumbsElement = gallery.querySelector(".swiper-thumbs");
        const thumbButtons = thumbsElement
            ? Array.from(thumbsElement.querySelectorAll(".coupon-gallery-thumb"))
            : [];
        const thumbs = thumbsElement
            ? new Swiper(thumbsElement, {
                spaceBetween: 8,
                slidesPerView: 5,
                watchSlidesProgress: true,
                watchOverflow: true
            })
            : null;

        function syncThumbnails(swiper) {
            thumbButtons.forEach(function (button, index) {
                const selected = index === swiper.realIndex;
                button.setAttribute("aria-pressed", String(selected));
                const slide = button.closest(".swiper-slide");
                if (slide) slide.classList.toggle("swiper-slide-thumb-active", selected);
            });
            if (thumbs) thumbs.slideTo(swiper.realIndex);
        }

        const pagination = gallery.querySelector(".swiper-pagination");
        const nextButton = gallery.querySelector(".swiper-button-next");
        const previousButton = gallery.querySelector(".swiper-button-prev");
        const options = {
            spaceBetween: 12,
            slidesPerView: 1,
            loop: slideCount > 1,
            watchOverflow: true,
            a11y: {
                prevSlideMessage: "前の画像を表示",
                nextSlideMessage: "次の画像を表示",
                paginationBulletMessage: "画像 {{index}} を表示"
            },
            on: {
                init: syncThumbnails,
                slideChange: syncThumbnails
            }
        };

        if (pagination) options.pagination = { el: pagination, clickable: true };
        if (nextButton && previousButton) {
            options.navigation = { nextEl: nextButton, prevEl: previousButton };
        }

        const main = new Swiper(mainElement, options);
        thumbButtons.forEach(function (button, index) {
            // Native button clicks include Enter and Space keyboard activation.
            button.addEventListener("click", function () {
                main.slideToLoop(index);
            });
        });
    });
});
