<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>システムエラー ナウポン</title>
     <style>
        /* カルーセル ここから */
        .carousel-wrapper {
            padding: 20px 0;
        }
        .swiper-container {
            overflow: hidden;
        }
        .swiper-wrapper {
            padding: 0; /* padding削除 */
        }
        .swiper-slide {
            display: flex;
            justify-content: center;
        }
        .card {
            width: 100%;
            max-width: 320px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 12px;
            position: relative;
        }
        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 6px;
        }
        .discount-image {
            position: absolute;
            top: 80px;
            left: 0;
            right: 0;
            text-align: center;
        }
        /* カルーセル ここまで */

        /* 「お気に入り」「エリア」「割引率」「ジャンル」ボタン ここから */
        .filter-buttons {
            display: flex;
            justify-content: flex-start;
            gap: 12px;
            margin: 30px 0;
        }
        .filter-buttons button {
            padding: 12px 24px;
            border-radius: 9999px;
            border: 2px solid #b08968;
            background: white;
            color: #6b4e3d;
            font-weight: bold;
            font-size: 14px;
            min-width: 120px;
            text-align: center;
            cursor: pointer;
            transition: background 0.3s, color 0.3s;
        }
        .filter-buttons button:hover,
        .filter-buttons button:focus {
            background: #d2a679;
            color: white;
            outline: none;
        }
        .filter-buttons button.active {
            background: #d2a679;
            color: white;
        }
        @media (max-width: 767px) {
            .filter-buttons {
                flex-wrap: wrap; /* ← 折り返し許可 */
                justify-content: center; /* ← 全体を中央寄せ */
                gap: 8px;
            }

            .filter-buttons button {
                min-width: unset; /* ← 固定幅を外す */
                padding: 12px 16px;
                font-size: 14px;
                word-break: keep-all;
                white-space: nowrap; /* ← 文字の折り返し防止 */
                flex: 1 1 auto; /* ← フレキシブルな横幅で伸縮 */
            }
        }
        /* 「お気に入り」「エリア」「割引率」「ジャンル」ボタン ここまで */

        /* 検索スペース ここから */
        .search-panel {
            background: #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
        }
        .search-tags {
            display: flex;
            gap: 12px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }
        .search-tag-box {
            background-color: white;
            border-radius: 10px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.08);
            border: 1px solid #eee;
            white-space: nowrap;
        }
        .search-icon-box {
            background: #b08968;
            color: white;
            font-weight: bold;
            height: 48px;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            border: none;
            font-size: 16px;
        }
        .search-keyword-row {
            display: flex;
            gap: 16px;
            align-items: flex-start;
        }
        .search-keyword-box {
            flex: 2;
            background: white;
            border-radius: 9999px;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            font-size: 14px;
        }
        .search-keyword-box input {
            border: none;
            flex: 1;
            background: none;
            outline: none;
            font-size: 14px;
        }
        .search-keyword-box .search-icon {
            color: #f97316;
            font-size: 18px;
            margin-left: 10px;
        }
        .keyword-tags-box {
            flex: 1;
            background: #b08968;
            border-radius: 12px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            color: white;
        }
        .keyword-tags-box button {
            background: white;
            color: #6b4e3d;
            font-weight: bold;
            border-radius: 20px;
            padding: 6px 16px;
            border: none;
            font-size: 14px;
            width: fit-content;
        }
        .recent-search-box {
            background: white;
            border-radius: 12px;
            padding: 12px 16px;
            margin-top: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .recent-search-box span:first-child {
            color: #9ca3af;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        @media (max-width: 767px) {
            .search-panel {
                padding: 12px;
            }
            .search-tags {
                flex-direction: column;
                align-items: stretch;
            }
            .search-tag-box,
            .search-icon-box {
                width: 100%;
                justify-content: center;
            }
            .search-keyword-row {
                flex-direction: column;
                gap: 16px;
            }
            .keyword-tags-box {
                width: 100%;
                flex-direction: row;
                justify-content: center;
                flex-wrap: wrap;
                gap: 8px;
                padding: 12px;
            }
            .keyword-tags-box button {
                width: auto;
                flex-shrink: 0;
            }
            .search-keyword-box,
            .recent-search-box {
                width: 100%;
            }
        }
        @media (max-width: 767px) {
            .search-panel {
                padding: 20px 16px;
            }
            .search-tags {
                display: flex;
                flex-direction: column;
                gap: 12px;
                width: 100%;
            }
            .search-tag-box,
            .search-icon-box {
                width: 100%;
                box-sizing: border-box;
            }
        }
        .keyword-tags-box {
            max-width: 100%;
            overflow-x: hidden;
        }
        @media (max-width: 767px) {
            .keyword-tags-box {
                display: flex;
                flex-wrap: wrap;
                justify-content: center; /* ← 中央寄せに */
                gap: 8px;
                padding: 12px;
                background: #b08968;
                border-radius: 12px;
                width: 100%;             /* ← これがないと横スク発生する場合あり */
                box-sizing: border-box;  /* ← paddingの幅を含める */
            }
            .keyword-tags-box button {
                flex-shrink: 0;
                width: auto;
            }
        }
        /* 検索スペース ここまで */

        /* 特集 ここから */
        .feature-section {
            margin-top: 60px;
        }
        .feature-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* 通常は2列 */
            gap: 20px;
        }
        @media (max-width: 767px) {
            .feature-grid {
                grid-template-columns: 1fr; /* スマホでは1列 */
            }
        }
        .feature-card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            display: flex;
            gap: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            align-items: center;
        }
        .feature-card img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }
        .feature-card p {
            font-size: 14px;
            margin: 0;
            line-height: 1.6;
        }
        /* 特集 ここまで */

        /* 「まずは会員登録」「ログイン」ここから */
        .bottom-buttons {
            margin-top: 40px;
            background: #e5e7eb;
            padding: 24px 0;
            border-radius: 20px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .bottom-buttons .btn-register {
            background: #d2a679;
            color: white;
            font-weight: bold;
            padding: 14px 40px;
            border-radius: 9999px;
            font-size: 16px;
            text-decoration: none;     /* ← 下線消す */
            display: inline-block;     /* ← ボタン風維持 */
        }
        .bottom-buttons .btn-login {
            background: white;
            color: #6b7280;
            font-weight: bold;
            padding: 14px 40px;
            border-radius: 9999px;
            font-size: 16px;
            box-shadow: 0 0 0 1px #ccc inset;
            text-decoration: none;     /* ← 下線消す */
            display: inline-block;     /* ← ボタン風維持 */
        }
        /* 「まずは会員登録」「ログイン」ここまで */
    </style>
</head>
<body>
<div class="container">
    @include('layouts.header')
    <div class="carousel-wrapper">
        ページが見つかりませんでした
    </div>
</div>
</body>
@include('layouts.footer')
</html>
