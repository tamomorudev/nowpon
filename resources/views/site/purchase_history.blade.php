<!DOCTYPE html>
<html lang="ja">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- ▼ 詳細検索パーツ ▼ --}}
    <link rel="stylesheet" href="{{ asset('css/site/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/site/search.css') }}">
    <link rel="stylesheet" href="{{ asset('css/site/purchase-history.css') }}">
    <script src="{{ asset('js/site/search.js') }}" defer></script>
    <script src="{{ asset('js/site/purchase-history.js') }}" defer></script>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>購入履歴 | ナウポン</title>
</head>
<body>
<div class="container">
    @include('layouts.header')

    <div class="purchase-history-section">

        <!-- モーダルここから -->
        <div class="cancel-modal-overlay" id="cancelModal" aria-hidden="true">
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
                    <p id="cancelMessage" class="purchase-cancel-message"></p>
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
                        data-pdate="{{ \Carbon\Carbon::parse($purchase_coupon->purchase_date)->format('Y年n月j日') }}"
                        data-udate="{{ \Carbon\Carbon::parse($purchase_coupon->cource_start)->format('Y年n月j日 G時i分～') }}"
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
                            <p class="purchase-card-title">{{ \Carbon\Carbon::parse($purchase_coupon->purchase_date)->format('Y年n月j日') }} {{ config('commons.genre')[$purchase_coupon->genre] }}ー{{ $purchase_coupon->store_name }}</p>
                            <p class="purchase-card-access">{{ $purchase_coupon->station }}駅 {{ config('commons.transportation')[$purchase_coupon->transportation] }}{{ $purchase_coupon->time }}分</p>
                        </div>
                    </button>
                @endforeach
            </div>
            <nav class="purchase-pager" id="purchasePager" aria-label="ページ切り替え"></nav>
        @else
            <h2>直近の購入履歴はありません</h2>
        @endif
    </div>
</div>
@include('layouts.footer')
</body>
</html>
