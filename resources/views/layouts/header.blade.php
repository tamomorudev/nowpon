<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

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
        border-bottom: 3px solid var(--site-color-brand, #c5a067);
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
        color: var(--site-color-brand, #c5a067);
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
        outline: 2px solid var(--site-color-brand, #c5a067);
        outline-offset: 2px;
    }

    .username {
        display: block;
        max-width: 180px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .header-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 36px;
        padding: 0 14px;
        border: 1px solid #d4a373;
        border-radius: 9999px;
        color: #6b4e3d;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
    }

    .header-action--primary {
        border-color: #c5a067;
        background: #c5a067;
        color: #ffffff;
    }

    .user-icon {
        font-size: 20px;
        color: var(--site-color-brand, #c5a067);
    }

    @media screen and (max-width: 767px) {
        .header {
            min-height: 64px;
            flex-direction: row;
            align-items: center;
            gap: 10px;
            margin-top: 0;
            padding: 10px 16px;
            border-bottom-width: 1px;
        }

        .header-logo {
            justify-content: flex-start;
        }

        .header-logo img {
            height: 30px;
        }

        .header-right {
            width: auto;
            margin-left: auto;
            justify-content: flex-end;
            flex-wrap: nowrap;
            gap: 8px;
        }

        .header-nav {
            gap: 8px;
            font-size: 12px;
        }

        .header-user {
            gap: 3px;
            font-size: 12px;
            max-width: 100%;
        }

        .username { max-width: 72px; }

        .header-actions {
            width: auto;
            justify-content: flex-end;
            gap: 6px;
        }

        .header-action {
            min-height: 40px;
            padding: 0 11px;
            font-size: 12px;
        }

        .user-icon {
            font-size: 18px;
        }
    }

    @media screen and (max-width: 359px) {
        .header {
            padding-inline: 12px;
        }

        .header-logo img {
            height: 27px;
        }

        .header-actions {
            gap: 4px;
        }

        .header-action {
            padding-inline: 8px;
            font-size: 11px;
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
            @if(Auth::guard('web')->check())
                {{-- <a href="/site/cart" class="{{ isActive(['site/cart', 'site/checkout']) }}">カート</a> --}}
                <a href="/site/purchase_history" class="{{ isActive(['site/purchase_history']) }}">購入履歴</a>
            @endif
        </div>

        @if(Auth::guard('web')->check())
            <div class="header-user">
                <span class="user-icon">👤</span>
                <a class="username" href="{{ url('/account') }}">
                    {{ Auth::guard('web')->user()->name }}
                </a>
            </div>
        @else
            <div class="header-actions" aria-label="アカウント操作">
                <a class="header-action" href="{{ route('login') }}">ログイン</a>
                <a class="header-action header-action--primary" href="{{ route('register') }}">会員登録</a>
            </div>
        @endif
    </div>
</div>
