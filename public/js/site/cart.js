/*
 * カートページ用処理。
 * 商品画像のSwiperカルーセルを初期化する。
 */
document.addEventListener("DOMContentLoaded", function () {
    const slider = document.querySelector(".cart-swiper");

    if (!slider || typeof Swiper === "undefined") return;

    new Swiper(slider, {
        loop: true,
        slidesPerView: 1,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev"
        },
        autoplay: {
            delay: 2500,
            disableOnInteraction: false
        }
    });
});
