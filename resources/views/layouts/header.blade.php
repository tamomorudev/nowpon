<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
<!-- Swiper -->
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

<!-- 共通のヘッダースタイル -->
<style>
    html, body {
        height: 100%;
    }

    body {
        display: flex;
        flex-direction: column;
    }
    .container {
        flex: 1;
    }

    /* ヘッダー ここから */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 24px 12px;
        border-bottom: 3px solid #c29663;
        gap: 20px;
        flex-wrap: wrap;
        margin-top: 10px;
    }
    .header-left {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 16px;
        color: #6b4e3d;
    }
    .header-left a {
        color: inherit;          /* 親(.header-left)の色を継承 → #6b4e3d */
        text-decoration: none;   /* 下線を消す */
        font-weight: normal;     /* 周囲と同じ太さに */
    }
    .header-left a:focus-visible {
        outline: 2px solid #c29663; /* キーボード操作向けの可視フォーカス */
        outline-offset: 2px;
    }

    .user-icon {
        font-size: 20px;
        color: #b08968;
    }
    .header-search {
        flex: 1;
        display: flex;
        justify-content: flex-start;
    }
    .search-box {
        background: #d1dbe9;
        border-radius: 9999px;
        padding: 8px 16px;
        display: flex;
        align-items: center;
        width: 100%;
        max-width: 320px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .search-box input {
        border: none;
        background: transparent;
        outline: none;
        flex: 1;
        font-size: 16px;
    }
    .search-icon {
        color: #b08968;
        font-size: 18px;
        margin-right: 8px;
    }
    .header-nav {
        display: flex;
        gap: 16px;
        font-size: 14px;
        align-items: center;
        white-space: nowrap;
    }
    .header-nav a {
        color: #111;
        text-decoration: none;
        font-weight: 600;
    }
    .header-nav a.active {
        color: #b08968;
        font-weight: bold;
    }
</style>

<div class="header">
    <div class="header-left">
        @if(Auth::guard('web')->check())
            {{-- ログイン中（セッションあり） --}}
            <span class="user-icon">👤</span>
            <a class="username" href="{{ url('/account') }}">
                {{ Auth::guard('web')->user()->name }}
            </a>
        @endif
    </div>

    <div class="header-nav">
        <a href="/" class="{{ isActive(['/']) }}">HOME</a>
        @if(Auth::guard('web')->check())
        <a href="/site/cart" class="{{ isActive(['site/cart', 'site/checkout']) }}">カート</a>
        <a href="#">購入履歴</a>
        @endif
        <a href="/site/contact">CONTACT</a>
    </div>
</div>
