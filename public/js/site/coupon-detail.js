/*
 * クーポン詳細ページ用処理。
 * メイン画像スライダーとサムネイルスライダーを連動させる。
 */
document.addEventListener("DOMContentLoaded", function () {
    if (typeof Swiper === "undefined") return;

    const thumbsElement = document.querySelector(".swiper-thumbs");
    const mainElement = document.querySelector(".swiper-main");

    if (!thumbsElement || !mainElement) return;

    // サムネイル側はメインスライダーのナビゲーションとして使う。
    const thumbs = new Swiper(thumbsElement, {
        spaceBetween: 8,
        slidesPerView: 5,
        watchSlidesProgress: true
    });

    // メイン画像はサムネイル選択、矢印、ページネーションで操作できる。
    new Swiper(mainElement, {
        spaceBetween: 12,
        loop: true,
        centeredSlides: true,
        pagination: { el: ".swiper-pagination", clickable: true },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev"
        },
        thumbs: { swiper: thumbs }
    });
});
