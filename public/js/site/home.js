/*
 * TOPページ専用処理。
 * 新着クーポンカルーセルのSwiper初期化だけを担当する。
 */
document.addEventListener('DOMContentLoaded', function () {
    // Swiperライブラリとカルーセル要素があるページでだけ初期化する。
    if (typeof Swiper !== 'undefined' && document.querySelector('.swiper-container')) {
        new Swiper('.swiper-container', {
            slidesPerView: 8,
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
                    slidesPerView: 5
                }
            }
        });
    }
});
