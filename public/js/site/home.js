/*
 * TOPページ専用処理。
 * 新着クーポンカルーセルのSwiper初期化だけを担当する。
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

        new Swiper('.js-home-carousel', {
            slidesPerView: 4,
            spaceBetween: 20,
            centeredSlides: false,
            loop: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev'
            },
            breakpoints: {
                0: {
                    slidesPerView: 2,
                    spaceBetween: 20
                },
                480: {
                    slidesPerView: 2,
                    spaceBetween: 20
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

    // Swiperライブラリとカルーセル要素があるページでだけ初期化する。
    initHomeCarousel(10);
});
