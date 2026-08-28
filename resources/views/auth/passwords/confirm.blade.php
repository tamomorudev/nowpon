<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/site/common.css') }}">
    <title>ナウポンパスワード変更</title>
</head>
<body>
{{-- 共通ヘッダー（ヘッダー側でhtml/bodyのCSSは定義済み想定） --}}
@include('layouts.header')

<style>
    /* ===== Page layout ===== */
    main.page-main {
        flex: 1;
        display: grid;
        place-items: center;
        padding: 48px 16px;
    }

    /* ===== Card ===== */
    .login-card {
        width: 100%;
        max-width: 420px;
        margin: 0 auto;
        background: #fffaf5;
        border: 1px solid #ead8c6;
        border-radius: 12px;
        box-shadow:
            0 10px 24px rgba(0,0,0,0.10),
            0 1px 0 rgba(255,255,255,0.8) inset;
        padding: 28px;
        box-sizing: border-box;
        position: relative;
    }

    .login-card::before {
        content: "";
        position: absolute;
        top: 10px;
        left: 50%;
        transform: translateX(-50%);
        width: 64px;
        height: 4px;
        background: var(--site-color-brand, #c5a067);
        border-radius: 9999px;
        opacity: 0.9;
    }

    .login-title {
        margin: 14px 0 18px;
        text-align: center;
        font-weight: 700;
        font-size: 22px;
        color: #6b4e3d;
    }

    .login-description {
        margin: 0 0 18px;
        font-size: 14px;
        line-height: 1.6;
        color: #6b4e3d;
        text-align: center;
    }

    /* ===== Form ===== */
    .login-field { margin-bottom: 16px; }
    .login-label {
        display: block;
        margin-bottom: 6px;
        font-size: 14px;
        font-weight: 600;
        color: #6b4e3d;
    }
    .login-input {
        width: 100%;
        height: 44px;
        padding: 10px 12px;
        border: 1px solid #d9d9d9;
        border-radius: 8px;
        font-size: 15px;
        transition: box-shadow .15s, border-color .15s, background-color .15s;
        background: #fff;
        box-sizing: border-box;
    }
    .login-input:focus {
        border-color: var(--site-color-brand, #c5a067);
        box-shadow: 0 0 0 3px rgba(194,150,99,.2);
        background-color: #fffefd;
    }
    .login-input:focus-visible {
        outline: none;
    }
    .login-error {
        margin-top: 6px;
        color: #c0392b;
        font-size: 12px;
    }

    .login-actions { display: grid; gap: 10px; margin-top: 4px; }

    /* ===== Button（中央揃え） ===== */
    .login-button {
        width: 100%;
        height: 44px;
        padding: 0 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-align: center;

        border: 0;
        border-radius: 9999px;
        background: var(--site-color-brand, #c5a067);
        color: #fff;
        font-weight: 700;
        cursor: pointer;
        transition: transform .02s ease, background .2s ease;
        box-sizing: border-box;
    }
    .login-button:hover { background: var(--site-color-brand, #c5a067); opacity: 0.88; }
    .login-button:active { transform: translateY(1px); }

    .login-links {
        display: flex;
        justify-content: center;
        margin-top: 6px;
        font-size: 13px;
    }
    .login-link {
        text-decoration: none;
        color: #6b4e3d;
        white-space: nowrap;
    }
    .login-link:hover { text-decoration: underline; }

    /* 小さめ端末 */
    @media (max-width: 480px) {
        .login-card { padding: 22px; }
    }
</style>

<main class="page-main">
    <div class="login-card" role="main" aria-labelledby="confirmTitle">
        <h1 id="confirmTitle" class="login-title">{{ __('Confirm Password') }}</h1>

        <p class="login-description">{{ __('Please confirm your password before continuing.') }}</p>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="login-field">
                <label for="password" class="login-label">{{ __('Password') }}</label>
                <input
                    id="password"
                    type="password"
                    class="login-input @error('password') is-invalid @enderror"
                    name="password"
                    required
                    autocomplete="current-password"
                >
                @error('password')
                    <div class="login-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="login-actions">
                <button type="submit" class="login-button">
                    {{ __('Confirm Password') }}
                </button>

                @if (Route::has('password.request'))
                    <div class="login-links">
                        <a class="login-link" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    </div>
                @endif
            </div>
        </form>
    </div>
</main>

{{-- 共通フッター（body 内、最後に置く） --}}
@include('layouts.footer')
</body>
</html>
