<!DOCTYPE html>
<html lang="ja">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- ▼ 詳細検索パーツ ▼ --}}
    <link rel="stylesheet" href="{{ asset('css/nowpon-search.css') }}">
    <script src="{{ asset('js/nowpon-search.js') }}" defer></script>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ナウポンTOP</title>
    <style>
        .purchase-history-section {
            max-width: 1200px;
            margin: 40px auto 0;
            padding: 0 24px;
        }

        .purchase-history-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 16px;
            margin-bottom: 28px;
            flex-wrap: wrap;
        }

        .purchase-history-title {
            margin: 0;
            font-size: 25px;
            line-height: 1.2;
            color: #8f8a7f;
            font-weight: 700;
            letter-spacing: 0.04em;
        }

        .purchase-history-count {
            margin: 0;
            font-size: 15px;
            line-height: 1.2;
            color: #8f8a7f;
            font-weight: 700;
        }

        .purchase-history-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 56px 64px;
        }

        .purchase-card {
            appearance: none;
            -webkit-appearance: none;
            text-decoration: none;
            display: block;
            transition: transform 0.2s ease, opacity 0.2s ease;
            border: none;
            outline: none;
            box-shadow: none;
            background: transparent;
            padding: 0;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .purchase-card:hover {
            transform: translateY(-3px);
            opacity: 0.95;
        }

        .purchase-card-image-wrap {
            width: 100%;
            aspect-ratio: 16 / 9;
            overflow: hidden;
            background: #f4f1ed;
        }

        .purchase-card-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .purchase-card-body {
            text-align: center;
            padding-top: 18px;
        }

        .purchase-card-title {
            margin: 0 0 8px;
            color: #c54b33;
            font-size: 18px;
            font-weight: 500;
            line-height: 1.5;
            letter-spacing: 0.04em;
        }

        .purchase-card-access {
            margin: 0;
            color: #c54b33;
            font-size: 15x;
            font-weight: 400;
            line-height: 1.5;
            letter-spacing: 0.03em;
        }

        @media screen and (max-width: 768px) {
            .purchase-history-section {
                padding: 0 16px;
                margin-top: 28px;
            }

            .purchase-history-title {
                font-size: 20px;
            }

            .purchase-history-count {
                font-size: 15px;
            }

            .purchase-history-grid {
                grid-template-columns: 1fr;
                gap: 40px;
            }
        }


        /* 以下モーダル */
        <style>
         .purchase-card {
             text-decoration: none;
             display: block;
             transition: transform 0.2s ease, opacity 0.2s ease;
             border: 0;
             background: transparent;
             padding: 0;
             width: 100%;
             text-align: left;
             cursor: pointer;
         }

        .purchase-card:hover {
            transform: translateY(-3px);
            opacity: 0.95;
        }

        .cancel-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.35);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 24px;
            z-index: 9999;
        }

        .cancel-modal-overlay.is-open {
            display: flex;
        }

        .cancel-modal {
            position: relative;
            width: 100%;
            max-width: 980px;
            background: #f8f8f8;
            border-radius: 20px;
            padding: 28px 28px 24px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .cancel-modal-title {
            margin: 0 0 18px;
            font-size: 22px;
            color: #333;
            font-weight: 700;
        }

        .cancel-modal-close {
            position: absolute;
            top: 12px;
            right: 18px;
            border: 0;
            background: transparent;
            font-size: 34px;
            line-height: 1;
            color: #b5412e;
            cursor: pointer;
        }

        .cancel-modal-top {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-bottom: 18px;
        }

        .cancel-info-box,
        .cancel-item-box {
            border: 1px solid #8d8d8d;
            border-radius: 28px;
            background: #f8f8f8;
        }

        .cancel-info-box {
            padding: 18px 20px;
            min-height: 136px;
        }

        .cancel-info-box p {
            margin: 0 0 6px;
            color: #333;
            font-size: 15px;
            line-height: 1.5;
        }

        .cancel-info-total {
            font-size: 22px !important;
            font-weight: 700;
        }

        .cancel-item-box {
            display: grid;
            grid-template-columns: 220px 1fr;
            gap: 32px;
            padding: 22px;
            margin-bottom: 18px;
            align-items: center;
        }
        .cancel-item-image-wrap {
            width: 220px;
            height: 160px;
            flex-shrink: 0;
            overflow: hidden;
            border-radius: 8px;
            background: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cancel-item-image {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: contain;
        }

        .cancel-item-content {
            display: flex;
            align-items: center;
        }

        .cancel-item-meta {
            display: flex;
            justify-content: flex-start;
            margin-bottom: 0;
        }

        .cancel-item-right p {
            margin: 0 0 4px;
            font-size: 18px;
            color: #444;
        }

        .cancel-item-price {
            margin: 0 0 8px;
            font-size: 26px;
            font-weight: 700;
            color: #222;
        }

        .cancel-item-name,
        .cancel-item-tel,
        .cancel-item-address {
            margin: 0 0 6px;
            font-size: 18px;
            color: #333;
            font-weight: 600;
        }

        .cancel-modal-bottom {
            display: flex;
            gap: 24px;
            align-items: center;
        }

        .cancel-reason-select {
            width: 350px;
            height: 54px;
            border: 1px solid #8d8d8d;
            border-radius: 9999px;
            background: #f8f8f8;
            padding: 0 22px;
            font-size: 18px;
            color: #333;
            outline: none;
        }

        .cancel-submit-button {
            flex: 1;
            height: 54px;
            border: 0;
            border-radius: 9999px;
            background: #f1df93;
            color: #333;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
        }

        .cancel-submit-button:hover {
            opacity: 0.92;
        }

        body.modal-open {
            overflow: hidden;
        }

        @media screen and (max-width: 768px) {
            .cancel-modal {
                padding: 20px 16px;
                border-radius: 16px;
            }

            .cancel-modal-top {
                grid-template-columns: 1fr;
            }

            .cancel-item-box {
                flex-direction: column;
            }

            .cancel-item-image-wrap {
                width: 100%;
            }

            .cancel-item-meta {
                justify-content: flex-start;
            }

            .cancel-modal-bottom {
                flex-direction: column;
            }

            .cancel-reason-select,
            .cancel-submit-button {
                width: 100%;
                height: 54px;
            }

            .cancel-submit-button {
                flex: none;
                min-height: 54px;
                line-height: 54px;
                padding: 0;
            }
        }
    </style>
</head>
<body>
<div class="container">
    @include('layouts.header')

    <div class="purchase-history-section">

        <!-- モーダルここから -->
        <div class="cancel-modal-overlay" id="cancelModal">
            <div class="cancel-modal" role="dialog" aria-modal="true" aria-labelledby="cancelModalTitle">
                <button type="button" class="cancel-modal-close" id="closeCancelModal" aria-label="閉じる">×</button>

                <h2 class="cancel-modal-title" id="cancelModalTitle">購入をキャンセル</h2>

                <div class="cancel-modal-top">
                    <div class="cancel-info-box">
                        <p>注文日:　　2025/03/15</p>
                        <p>注文番号:　55555555</p>
                        <p>注文合計　¥3500</p>
                    </div>

                    <div class="cancel-info-box">
                        <p>お支払い情報</p>
                        <p>支払い方法</p>
                        <p>visa下4桁1234</p>
                        <p>一括払い</p>
                    </div>

                    <div class="cancel-info-box">
                        <p>領収書/明細書</p>
                        <p>商品: 整体</p>
                        <p>金額3500円</p>
                        <p>手数料350円</p>
                        <p class="cancel-info-total">合計</p>
                    </div>
                </div>

                <div class="cancel-item-box">
                    <div class="cancel-item-image-wrap">
                        <img src="https://images.unsplash.com/photo-1519823551278-64ac92734fb1?auto=format&fit=crop&w=1200&q=80" alt="整体イメージ" class="cancel-item-image" id="cancelModalImage">
                    </div>

                    <div class="cancel-item-content">
                        <div class="cancel-item-meta">
                            <div class="cancel-item-right">
                                <p>購入日　2025/03/15</p>
                                <p>利用日　2025/03/16　12:30〜</p>
                                <p class="cancel-item-name">ジャンルー店舗名</p>
                                <p class="cancel-item-tel">☎</p>
                                <p class="cancel-item-address">住所</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="cancel-modal-bottom">
                    <select class="cancel-reason-select">
                        <option selected>キャンセルの理由</option>
                        <option>予定が変わったため</option>
                        <option>日時を間違えたため</option>
                        <option>別の店舗を利用するため</option>
                    </select>

                    <button type="button" class="cancel-submit-button">キャンセルする</button>
                </div>
            </div>
        </div>
        <!-- モーダルここまで -->


        <div class="purchase-history-head">
            <h1 class="purchase-history-title">購入履歴</h1>
            <p class="purchase-history-count">過去20件</p>
        </div>

        <div class="purchase-history-grid">
            <button type="button" class="purchase-card js-open-cancel-modal">
                <div class="purchase-card-image-wrap">
                    <img src="https://images.unsplash.com/photo-1519823551278-64ac92734fb1?auto=format&fit=crop&w=1200&q=80" alt="整体イメージ" class="purchase-card-image">
                </div>
                <div class="purchase-card-body">
                    <p class="purchase-card-title">3月15日 整体ー店舗名</p>
                    <p class="purchase-card-access">○○駅 北口徒歩何分</p>
                </div>
            </button>

            <button type="button" class="purchase-card js-open-cancel-modal">
                <div class="purchase-card-image-wrap">
                    <img src="https://images.unsplash.com/photo-1560066984-138dadb4c035?auto=format&fit=crop&w=1200&q=80" alt="美容室イメージ" class="purchase-card-image">
                </div>
                <div class="purchase-card-body">
                    <p class="purchase-card-title">3月16日 ヘアサロンー店舗名</p>
                    <p class="purchase-card-access">○○駅 北口徒歩何分</p>
                </div>
            </button>

            <button type="button" class="purchase-card js-open-cancel-modal">
                <div class="purchase-card-image-wrap">
                    <img src="https://images.unsplash.com/photo-1519823551278-64ac92734fb1?auto=format&fit=crop&w=1200&q=80" alt="整体イメージ" class="purchase-card-image">
                </div>
                <div class="purchase-card-body">
                    <p class="purchase-card-title">3月17日 整体ー店舗名</p>
                    <p class="purchase-card-access">○○駅 北口徒歩何分</p>
                </div>
            </button>

            <button type="button" class="purchase-card js-open-cancel-modal">
                <div class="purchase-card-image-wrap">
                    <img src="https://images.unsplash.com/photo-1560066984-138dadb4c035?auto=format&fit=crop&w=1200&q=80" alt="美容室イメージ" class="purchase-card-image">
                </div>
                <div class="purchase-card-body">
                    <p class="purchase-card-title">3月18日 ヘアサロンー店舗名</p>
                    <p class="purchase-card-access">○○駅 北口徒歩何分</p>
                </div>
            </button>
        </div>
    </div>
</div>
</body>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('cancelModal');
        const openButtons = document.querySelectorAll('.js-open-cancel-modal');
        const closeButton = document.getElementById('closeCancelModal');
        const modalImage = document.getElementById('cancelModalImage');

        openButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                const cardImage = button.querySelector('.purchase-card-image');

                if (cardImage && modalImage) {
                    modalImage.src = cardImage.src;
                    modalImage.alt = cardImage.alt;
                }

                modal.classList.add('is-open');
                document.body.classList.add('modal-open');
            });
        });

        closeButton.addEventListener('click', function () {
            modal.classList.remove('is-open');
            document.body.classList.remove('modal-open');
        });

        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                modal.classList.remove('is-open');
                document.body.classList.remove('modal-open');
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                modal.classList.remove('is-open');
                document.body.classList.remove('modal-open');
            }
        });
    });
</script>
@include('layouts.footer')
</html>
