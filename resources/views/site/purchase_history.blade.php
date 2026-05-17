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

        .cancel-modal-bottom form {
            display: flex;
            gap: 24px;
            align-items: center;
            width: 100%;
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

        /* ページャー */
        .purchase-pager {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin: 56px 0 40px;
        }

        .pager-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 2px solid #c54b33;
            background: transparent;
            color: #c54b33;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.18s, color 0.18s;
            line-height: 1;
        }

        .pager-btn:hover:not(:disabled) {
            background: #c54b33;
            color: #fff;
        }

        .pager-btn.is-active {
            background: #c54b33;
            color: #fff;
            border-color: #c54b33;
        }

        .pager-btn:disabled {
            border-color: #d9cfc8;
            color: #d9cfc8;
            cursor: default;
        }

        .pager-arrow {
            font-size: 18px;
            font-weight: 400;
        }

        @media screen and (max-width: 768px) {
            .purchase-pager {
                gap: 6px;
                margin: 40px 0 28px;
            }
            .pager-btn {
                width: 38px;
                height: 38px;
                font-size: 14px;
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
                        <p>注文日:　　<span class="modal-pdate"></span></p>
                        <p>注文番号:　<span class="modal-code"></span></p>
                        <p>店舗支払金額:　<span class="modal-storeprice"></span>円</p>
                        <p>サービス手数料:　<span class="modal-serviceprice"></span>円</p>
                        <p>注文合計　<span class="modal-totalprice"></span>円</p>
                    </div>

                    <div class="cancel-info-box">
                        <p>お支払い情報</p>
                        <p>支払い方法</p>
                        <p>クレジットカード</p>
                        <p>一括払い</p>
                    </div>

                    <div class="cancel-info-box">
                        <p>領収書/明細書</p>
                        <p>商品: <span class="modal-genre"></span></p>
                        <p>サービス手数料:<span class="modal-serviceprice2"></span>円</p>
                        <p class="cancel-info-total">合計:<span class="modal-price"></span>円</p>
                    </div>
                </div>
                <div class="cancel-item-box">
                    <div class="cancel-item-image-wrap">
                        <img src="https://images.unsplash.com/photo-1519823551278-64ac92734fb1?auto=format&fit=crop&w=1200&q=80" alt="整体イメージ" class="cancel-item-image" id="cancelModalImage">
                    </div>

                    <div class="cancel-item-content">
                        <div class="cancel-item-meta">
                            <div class="cancel-item-right">
                                <p>購入日　<span class="modal-pdate"></span></p>
                                <p>利用日　<span class="modal-udate"></span></p>
                                <p class="cancel-item-name"><span class="modal-storename"></span></p>
                                <p class="cancel-item-tel">☎<span class="modal-tel"></span></p>
                                <p class="cancel-item-address"><span class="modal-address"></span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="cancel-modal-bottom">
                    <p id="cancelMessage" style="color:#c54b33; font-size:15px; margin:0;"></p>
                    <form method="POST" action="{{ route('purchase.cancel') }}">
                        @csrf
                        <input type="hidden" name="purchase_code" class="modal-hidden-code" value="">

                        <select name="cancel_reason" class="cancel-reason-select">
                            <option value="" selected>キャンセルの理由</option>
                            @foreach(config('commons.cancel_reasons') as $r_key => $cancel_reason)
                                <option value="{{ $r_key }}"
                                    {{ old('genre', '') == $r_key ? 'selected' : '' }}>
                                    {{ $cancel_reason }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit" class="cancel-submit-button">キャンセルする</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- モーダルここまで -->


        <div class="purchase-history-head">
            <h1 class="purchase-history-title">購入履歴</h1>
            <p class="purchase-history-count">過去20件</p>
        </div>

        @if (count($purchase_coupons) > 0)
            <div class="purchase-history-grid">
                @foreach($purchase_coupons as $key => $purchase_coupon)
                    @php
                        $now = \Carbon\Carbon::now();
                        $start = \Carbon\Carbon::parse($purchase_coupon->cource_start);
                        
                        if ($now->greaterThanOrEqualTo($start)) {
                            $date_pat = 2;
                        } elseif ($now->greaterThanOrEqualTo($start->copy()->subHour())) {
                            $date_pat = 1;
                        } else {
                            $date_pat = 0;
                        }
                    @endphp
                    <button type="button" class="purchase-card js-open-cancel-modal"
                        data-pdate="{{ \Carbon\Carbon::parse($purchase_coupon->purchase_date)->format('n月j日') }}"
                        data-udate="{{ \Carbon\Carbon::parse($purchase_coupon->cource_start)->format('n月j日 G時i分～') }}"
                        data-code="{{ $purchase_coupon->purchase_code }}"
                        data-price="{{ $purchase_coupon->payment_amount }}"
                        data-storeprice="{{ $purchase_coupon->store_pay_price }}"
                        data-serviceprice="{{ $purchase_coupon->service_price }}"
                        data-serviceprice2="{{ $purchase_coupon->service_price }}"
                        data-totalprice="{{ $purchase_coupon->store_pay_price + $purchase_coupon->service_price }}"
                        data-genre="{{ config('commons.genre')[$purchase_coupon->genre] }}"
                        data-storename="{{ $purchase_coupon->store_name }}"
                        data-tel="{{ $purchase_coupon->phone_number }}"
                        data-image="{{ asset('/assets/images/'. $purchase_coupon->img_url) }}"
                        data-pat="{{ $date_pat }}"
                        data-address="{{ $purchase_coupon->address1 }} {{ $purchase_coupon->address2 }} {{ $purchase_coupon->address3 }}">
                        <div class="purchase-card-image-wrap">
                            @if($purchase_coupon->img_url)
                                <img src="{{ asset('/assets/images/'. $purchase_coupon->img_url) }}" alt="クーポン画像" class="purchase-card-image">
                            @else
                                <!--<img src="https://picsum.photos/80/80?random=1" alt="店舗画像" class="coupon-thumb" />-->
                            @endif
                        </div>
                        <div class="purchase-card-body">
                            <p class="purchase-card-title">{{ \Carbon\Carbon::parse($purchase_coupon->purchase_date)->format('n月j日') }} {{ config('commons.genre')[$purchase_coupon->genre] }}ー{{ $purchase_coupon->store_name }}</p>
                            <p class="purchase-card-access">{{ $purchase_coupon->station }}駅 {{ config('commons.transportation')[$purchase_coupon->transportation] }}{{ $purchase_coupon->time }}分</p>
                        </div>
                    </button>
                @endforeach
            </div>
            <nav class="purchase-pager" id="purchasePager" aria-label="ページ切り替え"></nav>
        @else
            <h4 class="" id="">直近の購入履歴はありません</h2>
        @endif
    </div>
</div>
</body>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('cancelModal');
        const openButtons = document.querySelectorAll('.js-open-cancel-modal');
        const closeButton = document.getElementById('closeCancelModal');
        const modalImage = document.getElementById('cancelModalImage');
        const cancelForm = document.querySelector('.cancel-modal-bottom form'); 

        openButtons.forEach(function (button) {
            button.addEventListener('click', function () {
               const d = button.dataset;

                //画像
                modalImage.src = d.image;
                modalImage.alt = d.storename;

                const hiddenCode = document.querySelector('.modal-hidden-code');
                if (hiddenCode) hiddenCode.value = d.code;

                const cancelMsg = document.getElementById('cancelMessage');

                if (d.pat === '0') {
                    cancelForm.style.display = 'flex';
                    cancelMsg.textContent = '';
                } else if (d.pat === '1') {
                    cancelForm.style.display = 'none';
                    cancelMsg.textContent = '利用時間の1時間前を過ぎているため、キャンセルできません。';
                } else if (d.pat === '2') {
                    cancelForm.style.display = 'none';
                    cancelMsg.textContent = '利用日時を過ぎているため、キャンセルできません。';
                }

                //注文
                document.querySelector('.modal-pdate').textContent     = d.pdate;
                document.querySelector('.modal-udate').textContent     = d.udate;
                document.querySelector('.modal-code').textContent      = d.code;
                document.querySelector('.modal-price').textContent     = Number(d.price).toLocaleString();
                document.querySelector('.modal-storeprice').textContent   = Number(d.storeprice).toLocaleString();
                document.querySelector('.modal-serviceprice').textContent   = Number(d.serviceprice).toLocaleString();
                document.querySelector('.modal-serviceprice2').textContent   = Number(d.serviceprice2).toLocaleString();
                document.querySelector('.modal-totalprice').textContent   = Number(d.totalprice).toLocaleString();
                document.querySelector('.modal-storename').textContent = d.storename;
                document.querySelector('.modal-genre').textContent = d.storename;
                document.querySelector('.modal-tel').textContent       = d.tel;
                document.querySelector('.modal-address').textContent   = d.address;

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

        //4件ページング
        const ITEMS_PER_PAGE = 4;
        const grid = document.querySelector('.purchase-history-grid');
        const pager = document.getElementById('purchasePager');

        function getCards() {
            return Array.from(grid.querySelectorAll('.purchase-card'));
        }

        function showPage(page) {
            const cards = getCards();
            const totalPages = Math.ceil(cards.length / ITEMS_PER_PAGE);

            cards.forEach(function (card, i) {
                const inPage = i >= (page - 1) * ITEMS_PER_PAGE && i < page * ITEMS_PER_PAGE;
                card.style.display = inPage ? '' : 'none';
            });

            renderPager(page, totalPages);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function renderPager(current, total) {
            pager.innerHTML = '';
            if (total <= 1) return;

            const prev = document.createElement('button');
            prev.type = 'button';
            prev.className = 'pager-btn pager-arrow';
            prev.textContent = '‹';
            prev.setAttribute('aria-label', '前のページ');
            prev.disabled = current === 1;
            prev.addEventListener('click', function () { showPage(current - 1); });
            pager.appendChild(prev);

            for (let p = 1; p <= total; p++) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'pager-btn' + (p === current ? ' is-active' : '');
                btn.textContent = p;
                btn.setAttribute('aria-label', p + 'ページ目');
                btn.setAttribute('aria-current', p === current ? 'page' : '');
                ;(function (page) {
                    btn.addEventListener('click', function () { showPage(page); });
                })(p);
                pager.appendChild(btn);
            }

            const next = document.createElement('button');
            next.type = 'button';
            next.className = 'pager-btn pager-arrow';
            next.textContent = '›';
            next.setAttribute('aria-label', '次のページ');
            next.disabled = current === total;
            next.addEventListener('click', function () { showPage(current + 1); });
            pager.appendChild(next);
        }

        showPage(1);
    });
</script>
@include('layouts.footer')
</html>
