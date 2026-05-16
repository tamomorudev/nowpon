<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

<!-- Swiper -->
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

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

    .header {
        display: flex;
        align-items: center;
        padding: 0 24px 12px;
        border-bottom: 3px solid #c29663;
        gap: 20px;
        margin-top: 10px;
    }

    .header-logo {
        display: flex;
        align-items: center;
        flex-shrink: 0;
    }

    .header-logo a {
        display: flex;
        align-items: center;
    }

    .header-logo img {
        display: block;
        height: 38px;
        width: auto;
        max-width: none;
        object-fit: contain;
    }

    .header-right {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-left: auto;
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

    .header-user {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 16px;
        color: #6b4e3d;
        white-space: nowrap;
    }

    .header-user a {
        color: inherit;
        text-decoration: none;
        font-weight: normal;
    }

    .header-user a:focus-visible {
        outline: 2px solid #c29663;
        outline-offset: 2px;
    }

    .user-icon {
        font-size: 20px;
        color: #b08968;
    }

    @media screen and (max-width: 767px) {
        .header {
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
            padding: 0 16px 10px;
        }

        .header-logo {
            justify-content: center;
        }

        .header-logo img {
            height: 38px;
        }

        .header-right {
            width: 100%;
            margin-left: 0;
            justify-content: space-between;
            gap: 12px;
        }

        .header-nav {
            gap: 12px;
            font-size: 13px;
        }

        .header-user {
            font-size: 13px;
            gap: 4px;
        }

        .user-icon {
            font-size: 16px;
        }
    }
</style>

<div class="header">
    <div class="header-logo">
        <a href="/">
            <img src="{{ asset('assets/images/logo-nowpon.png') }}" alt="Nowpon">
        </a>
    </div>

    <div class="header-right">
        <div class="header-nav">
            <a href="/" class="{{ isActive(['/']) }}">HOME</a>

            @if(Auth::guard('web')->check())
                {{-- <a href="/site/cart" class="{{ isActive(['site/cart', 'site/checkout']) }}">カート</a> --}}
                <a href="/site/purchase_history" class="{{ isActive(['site/purchase_history']) }}">購入履歴</a>
            @endif

            <a href="/site/contact" class="{{ isActive(['site/contact']) }}">CONTACT</a>
        </div>

        @if(Auth::guard('web')->check())
            <div class="header-user">
                <span class="user-icon">👤</span>
                <a class="username" href="{{ url('/account') }}">
                    {{ Auth::guard('web')->user()->name }}
                </a>
            </div>
        @endif
    </div>
</div>
