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

        <div style="text-align:center; padding: 60px 24px;">
        <h1>キャンセルが完了しました</h1>
        <a href="{{ route('purchaseHistory') }}">購入履歴に戻る</a>
    </div>
</body>

<script>
</script>
@include('layouts.footer')
</html>
