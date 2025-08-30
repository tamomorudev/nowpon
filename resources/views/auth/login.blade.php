<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ナウポンログイン</title>
</head>
<body>
{{-- 共通ヘッダー（ヘッダー側でhtml/bodyのCSSは定義済み想定） --}}
@include('layouts.header')

<style>
    /* ===== Page layout ===== */
    /* header 側で body{display:flex;flex-direction:column;} がある前提 */
    main.page-main {
        flex: 1;                         /* ← これでフッターを下部に固定 */
        display: grid;
        place-items: center;
        padding: 48px 16px;
    }

    /* ===== Card ===== */
    .login-card {
        width: 100%;
        max-width: 420px;
        margin: 0 auto;                  /* 中央寄せ */
        background: #fffaf5;             /* わずかに色味をつけて背景と差別化 */
        border: 1px solid #ead8c6;       /* 薄いアウトラインで輪郭を強調 */
        border-radius: 12px;
        box-shadow:
            0 10px 24px rgba(0,0,0,0.10),  /* 影を少し強めに */
            0 1px 0 rgba(255,255,255,0.8) inset;
        padding: 28px;
        box-sizing: border-box;
        position: relative;               /* アクセントバー用 */
    }
    /* アクセントバー（上部の短いライン）*/
    .login-card::before {
        content: "";
        position: absolute;
        top: 10px;
        left: 50%;
        transform: translateX(-50%);
        width: 64px;
        height: 4px;
        background: #c29663;
        border-radius: 9999px;
        opacity: 0.9;
    }

    .login-title {
        margin: 14px 0 18px;             /* アクセントバー分だけ余白を調整 */
        text-align: center;
        font-weight: 700;
        font-size: 22px;
        color: #6b4e3d;
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
        height: 44px;                    /* 入力とボタンで高さ統一 */
        padding: 10px 12px;
        border: 1px solid #d9d9d9;
        border-radius: 8px;
        font-size: 15px;
        outline: none;
        transition: box-shadow .15s, border-color .15s, background-color .15s;
        background: #fff;
        box-sizing: border-box;          /* 横幅突き抜け防止 */
    }
    .login-input:focus {
        border-color: #c29663;
        box-shadow: 0 0 0 3px rgba(194,150,99,.2);
        background-color: #fffefd;
    }
    .login-error {
        margin-top: 6px;
        color: #c0392b;
        font-size: 12px;
    }

    .login-remember {
        display: flex; align-items: center; gap: 8px;
        margin: 4px 0 18px;
        font-size: 14px; color: #6b4e3d;
        user-select: none;
    }

    .login-actions { display: grid; gap: 10px; }

    /* ===== Button（中央揃え） ===== */
    .login-button {
        width: 100%;
        height: 44px;
        padding: 0 16px;                 /* 縦方向のpaddingは0に */
        display: inline-flex;            /* テキストの完全中央寄せ */
        align-items: center;
        justify-content: center;
        text-align: center;

        border: 0;
        border-radius: 9999px;
        background: #b08968;
        color: #fff;
        font-weight: 700;
        cursor: pointer;
        transition: transform .02s ease, background .2s ease;
        box-sizing: border-box;
    }
    .login-button:hover { background: #a17857; }
    .login-button:active { transform: translateY(1px); }

    .login-links {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
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
        .login-links { flex-direction: column; align-items: stretch; gap: 6px; }
    }
</style>


<main class="page-main">
    <div class="login-card" role="main" aria-labelledby="loginTitle">
        <h1 id="loginTitle" class="login-title">ログイン</h1>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="login-field">
                <label for="email" class="login-label">メールアドレス</label>
                <input
                    id="email"
                    type="text"
                    class="login-input @error('email') is-invalid @enderror"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >
                @error('email')
                    <div class="login-error">{{ $message }}</div>
                @enderror
                @error('login_error')
                    <div class="login-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="login-field">
                <label for="password" class="login-label">パスワード</label>
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

            <label class="login-remember">
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                ログイン情報を保持する
            </label>

            <div class="login-actions">
                <button type="submit" class="login-button">ログイン</button>

                <div class="login-links">
                    @if (Route::has('password.request'))
                        <a class="login-link" href="{{ route('password.request') }}">パスワードを忘れた方</a>
                    @endif

                    @if (Route::has('register'))
                        <a class="login-link" href="{{ route('register') }}">会員登録</a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</main>

{{-- 共通フッター（body 内、最後に置く） --}}
@include('layouts.footer')
</body>
</html>
