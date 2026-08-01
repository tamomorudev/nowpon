/*
 * TOPページ専用処理。
 * 新着クーポンカルーセルとカテゴリ横スクロールの操作を担当する。
 */
document.addEventListener('DOMContentLoaded', function () {
    const initHomeCarousel = function (retryCount) {
        const carousel = document.querySelector('.js-home-carousel');

        // SwiperのCDN読み込みが遅れた場合は、短時間だけ再試行する。
        if (typeof Swiper === 'undefined') {
            if (retryCount > 0) {
                window.setTimeout(function () {
                    initHomeCarousel(retryCount - 1);
                }, 200);
            }
            return;
        }

        if (!carousel) return;

        const slideCount = carousel.querySelectorAll('.swiper-slide').length;

        new Swiper('.js-home-carousel', {
            slidesPerView: 4,
            spaceBetween: 20,
            centeredSlides: false,
            // 表示枚数より少ない時にloopを有効にするとSwiperが警告を出し、操作が不安定になる。
            loop: slideCount > 4,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false
            },
            navigation: {
                nextEl: carousel.querySelector('.swiper-button-next'),
                prevEl: carousel.querySelector('.swiper-button-prev')
            },
            pagination: {
                el: carousel.querySelector('.swiper-pagination'),
                clickable: true
            },
            breakpoints: {
                0: {
                    slidesPerView: 1.1,
                    spaceBetween: 12
                },
                480: {
                    slidesPerView: 1.25,
                    spaceBetween: 16
                },
                768: {
                    slidesPerView: 2.5
                },
                1024: {
                    slidesPerView: 3.5
                },
                1280: {
                    slidesPerView: 4
                },
                1440: {
                    slidesPerView: 4
                }
            }
        });
    };

    const initCategoryScroll = function () {
        const categoryList = document.querySelector('.js-category-list');
        const prevButton = document.querySelector('.js-category-scroll-prev');
        const nextButton = document.querySelector('.js-category-scroll-next');

        if (!categoryList || !prevButton || !nextButton) return;

        // PCで横スクロールしやすいよう、左右ボタンで表示幅の約8割ずつ送る。
        const getScrollAmount = function () {
            return Math.max(Math.round(categoryList.clientWidth * 0.8), 180);
        };

        const updateButtonState = function () {
            const maxScrollLeft = categoryList.scrollWidth - categoryList.clientWidth;
            prevButton.disabled = categoryList.scrollLeft <= 1;
            nextButton.disabled = categoryList.scrollLeft >= maxScrollLeft - 1;
        };

        prevButton.addEventListener('click', function () {
            categoryList.scrollBy({
                left: -getScrollAmount(),
                behavior: 'smooth'
            });
        });

        nextButton.addEventListener('click', function () {
            categoryList.scrollBy({
                left: getScrollAmount(),
                behavior: 'smooth'
            });
        });

        categoryList.addEventListener('scroll', updateButtonState);
        window.addEventListener('resize', updateButtonState);
        updateButtonState();
    };

    // Swiperライブラリとカルーセル要素があるページでだけ初期化する。
    initHomeCarousel(10);
    initCategoryScroll();
});
