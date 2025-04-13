<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
<!-- Swiper CSS -->
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

<!-- 共通のヘッダースタイル -->
<style>
    body {
        font-family: 'Nunito', sans-serif;
        background-color: #f3f4f6;
        margin-top: 10px;
        margin-right: 10px;
        margin-left: 10px;
        margin-bottom: 0;
    }
    .container {
        max-width: 1200px;
        margin: 0 auto;
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
        <span class="user-icon">👤</span>
        <span class="username">guest</span>
    </div>

    <div class="header-search">
        <div class="search-box">
            <span class="search-icon">🔍</span>
            <input type="text" placeholder="Search" />
        </div>
    </div>

    <div class="header-nav">
        <a href="#" class="active">HOME</a>
        <a href="#">カート</a>
        <a href="#">購入履歴</a>
        <a href="#">CONTACT</a>
    </div>
</div>
