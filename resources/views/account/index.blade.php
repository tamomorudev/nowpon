<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>マイページ</title>
</head>
<body>
{{-- 共通ヘッダー（html/bodyのCSSはヘッダー側にある前提） --}}
@include('layouts.header')

{{-- マイページ専用スタイル --}}
<style>
    /* ===== Page layout ===== */
    main.page-main {
        flex: 1;                 /* ← スティッキーフッター用 */
        display: grid;
        place-items: center;
        padding: 48px 16px;
    }

    /* ===== Card ===== */
    .mypage-card {
        width: 100%;
        max-width: 720px;
        margin: 0 auto;
        background: #fffaf5;           /* ログインカードと揃えた淡い下地色 */
        border: 1px solid #ead8c6;     /* 薄いアウトラインで境界を明確に */
        border-radius: 12px;
        box-shadow: 0 10px 24px rgba(0,0,0,0.10), 0 1px 0 rgba(255,255,255,0.8) inset;
        padding: 24px;
        box-sizing: border-box;
        position: relative;
    }
    .mypage-card::before {
        content: "";
        position: absolute;
        top: 10px;
        left: 50%;
        transform: translateX(-50%);
        width: 72px;
        height: 4px;
        background: #c29663;
        border-radius: 9999px;
        opacity: .9;
    }

    .mypage-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin: 14px 0 12px;           /* ::before 分の余白調整込み */
    }
    .avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        background: #ffe7d0;
        color: #6b4e3d;
        font-weight: 800;
        font-size: 22px;
        border: 1px solid #e6c7a8;
        user-select: none;
    }
    .mypage-title {
        margin: 0;
        font-weight: 700;
        font-size: 22px;
        color: #6b4e3d;
        line-height: 1.2;
    }
    .mypage-sub {
        font-size: 13px;
        color: #7e6a5e;
    }

    /* ===== Info list ===== */
    .info-list {
        margin-top: 12px;
        border-top: 1px dashed #e6c7a8;
    }
    .info-row {
        display: grid;
        grid-template-columns: 180px 1fr;   /* ラベル / 値 */
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px dashed #f0dcc7;
    }
    .info-label {
        color: #7a5b49;
        font-weight: 600;
        font-size: 14px;
    }
    .info-value {
        color: #3b2a22;
        font-size: 15px;
        word-break: break-word;
    }

    /* ===== Actions ===== */
    .actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 18px;
    }
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        height: 40px;
        padding: 0 16px;
        border-radius: 9999px;
        font-weight: 700;
        text-decoration: none;
        box-sizing: border-box;
        cursor: pointer;
        transition: transform .02s ease, background .2s ease, color .2s ease, border-color .2s ease;
        user-select: none;
        border: 1px solid transparent;
    }
    .btn-primary {
        background: #b08968;
        color: #fff;
        border-color: #b08968;
    }
    .btn-primary:hover { background: #a17857; }
    .btn-ghost {
        background: #fffaf5;
        color: #6b4e3d;
        border-color: #e4d2bf;
    }
    .btn-ghost:hover {
        background: #fff3e6;
        border-color: #d9c6b1;
    }

    /* ===== Responsive ===== */
    @media (max-width: 640px) {
        .info-row {
            grid-template-columns: 1fr;       /* 縦積み */
            gap: 4px;
        }
        .avatar { width: 48px; height: 48px; font-size: 18px; }
        .mypage-title { font-size: 20px; }
    }
</style>

<main class="page-main">
    <section class="mypage-card" role="main" aria-labelledby="mypageTitle">
        <header class="mypage-header">
            <div class="avatar">👤</div>
            <div>
                <h1 id="mypageTitle" class="mypage-title">マイページ</h1>
                <div class="mypage-sub">アカウント情報の確認</div>
            </div>
        </header>

        <div class="info-list">
            <div class="info-row">
                <div class="info-label">ユーザー名</div>
                <div class="info-value">{{ $user->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">ニックネーム</div>
                <div class="info-value">{{ $user->nickname }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">メールアドレス</div>
                <div class="info-value">{{ $user->email }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">郵便番号</div>
                <div class="info-value">{{ $user->postal_code }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">都道府県</div>
                <div class="info-value">{{ config('commons.prefectures')[$user->prefecture] ?? '' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">市区町村</div>
                <div class="info-value">{{ $user->city }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">電話番号</div>
                <div class="info-value">{{ $user->phone_number }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">性別</div>
                <div class="info-value">{{ config('commons.sexs')[$user->sex] ?? '' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">生年月日</div>
                <div class="info-value">{{ $user->birth_date }}</div>
            </div>
        </div>

        <div class="actions">
            {{-- ルート名は仮。実装に合わせて変更してください --}}
            <a href="{{ url('/mypage/edit') }}" class="btn btn-ghost">プロフィールを編集</a>
            <a href="{{ url('/password/change') }}" class="btn btn-ghost">パスワード変更</a>

            {{-- ログアウト（POST） --}}
            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="btn btn-primary">ログアウト</button>
            </form>
        </div>
    </section>
</main>

{{-- 共通フッター --}}
@include('layouts.footer')
</body>
</html>
