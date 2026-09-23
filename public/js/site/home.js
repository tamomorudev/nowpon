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

        const wrapper = carousel.querySelector('.swiper-wrapper');
        const slides = Array.from(wrapper.querySelectorAll('.swiper-slide'));
        const slideCount = slides.length;
        const pagination = carousel.querySelector('.swiper-pagination');
        const canLoop = slideCount > 1;

        if (!slideCount) {
            carousel.querySelectorAll('.swiper-button-prev, .swiper-button-next, .swiper-pagination').forEach(function (element) {
                element.style.display = 'none';
            });
            return;
        }

        // 最大4枚表示 + 次の1枚を確保。少数件でも元の順番のまま循環させる。
        // 一周単位で複製し、画面幅の変更時にもループの必要枚数を満たす。
        if (canLoop) {
            while (wrapper.children.length < 5) {
                slides.forEach(function (slide) {
                    wrapper.appendChild(slide.cloneNode(true));
                });
            }
        }
        const totalSlides = wrapper.children.length;
        const pageButtons = [];
        if (pagination && canLoop) {
            slides.forEach(function (_, index) {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'swiper-pagination-bullet';
                button.setAttribute('aria-label', 'クーポン ' + (index + 1) + ' を表示');
                pagination.appendChild(button);
                pageButtons.push(button);
            });
        }
        const syncPagination = function (swiper) {
            pageButtons.forEach(function (button, index) {
                const active = index === swiper.realIndex % slideCount;
                button.classList.toggle('swiper-pagination-bullet-active', active);
                button.setAttribute('aria-current', active ? 'true' : 'false');
            });
        };

        const swiper = new Swiper(carousel, {
            slidesPerView: Math.min(4, slideCount),
            spaceBetween: 20,
            centeredSlides: false,
            loop: canLoop,
            watchOverflow: true,
            autoplay: canLoop ? {
                delay: 2500,
                pauseOnMouseEnter: true,
                disableOnInteraction: false
            } : false,
            navigation: {
                nextEl: carousel.querySelector('.swiper-button-next'),
                prevEl: carousel.querySelector('.swiper-button-prev')
            },
            on: { init: syncPagination, slideChange: syncPagination },
            breakpoints: {
                0: {
                    slidesPerView: Math.min(1.1, slideCount),
                    spaceBetween: 12
                },
                480: {
                    slidesPerView: Math.min(1.25, slideCount),
                    spaceBetween: 16
                },
                768: {
                    slidesPerView: Math.min(2.5, slideCount)
                },
                1024: {
                    slidesPerView: Math.min(3.5, slideCount)
                },
                1280: {
                    slidesPerView: Math.min(4, slideCount)
                },
                1440: {
                    slidesPerView: Math.min(4, slideCount)
                }
            }
        });
        pageButtons.forEach(function (button, index) {
            button.addEventListener('click', function () {
                const offset = (index - swiper.realIndex % slideCount + slideCount) % slideCount;
                swiper.slideToLoop((swiper.realIndex + offset) % totalSlides);
            });
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
