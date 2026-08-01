<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>マイページ</title>

    <style>
        /* ※ layouts.header 側と被らないように、ページ専用のクラスのみ定義 */

        /* コンテナ(.container)は layouts.header 側にあるので触らない */
        .page-body{
            /* 画面中央寄せ用の内側ラッパー（container とは別名にする） */
            display: grid;
            place-items: center;
            padding: 48px 16px;
            width: 100%;
            box-sizing: border-box;
        }

        /* ===== Card ===== */
        .mypage-card {
            width: 100%;
            max-width: 720px;
            margin: 0 auto;
            background: #fffaf5;
            border: 1px solid #ead8c6;
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
            background: var(--site-color-brand, #c5a067);
            border-radius: 9999px;
            opacity: .9;
        }

        .mypage-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 14px 0 12px;
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
        .mypage-title { margin: 0; font-weight: 700; font-size: 22px; color: #6b4e3d; line-height: 1.2; }
        .mypage-sub   { font-size: 13px; color: #7e6a5e; }

        /* ===== Form ===== */
        .form-list { margin-top: 12px; border-top: 1px dashed #e6c7a8; }
        .form-row  { display: grid; grid-template-columns: 180px 1fr; gap: 12px; padding: 12px 0; border-bottom: 1px dashed #f0dcc7; }
        .form-label { color: #7a5b49; font-weight: 600; font-size: 14px; align-self: center; }
        .form-field { display: grid; gap: 8px; }
        .input, .select { width: 100%; padding: 10px 12px; border: 1px solid #e0cdb9; border-radius: 8px; background: #fff; font-size: 15px; box-sizing: border-box; }
        .help { font-size: 12px; color: #7e6a5e; }

        /* 必須マーク（パスワードは必須ではない） */
        .form-label.required::after{
            content: " *";
            color: #d12a2a;
            font-weight: 700;
            margin-left: 4px;
        }

        /* ===== Flash messages（カード内幅にフィット） ===== */
        .flash-message {
            padding: 10px;
            border-radius: 8px;
            font-size: 15px;
            margin: 0 0 16px 0;   /* 下余白のみ */
            width: 100%;          /* カード幅に追従 */
            text-align: center;
            box-sizing: border-box;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
        .flash-message.success {
            background: #e6ffed;
            border: 1px solid #b7e1c2;
            color: #2f6b3f;
        }
        .flash-message.error {
            background: #fff2f2;
            border: 1px solid #f1c3c3;
            color: #a84040;
        }

        /* ===== Actions ===== */
        .form-actions { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 18px; }
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; height: 40px; padding: 0 16px; border-radius: 9999px; font-weight: 700; text-decoration: none; box-sizing: border-box; cursor: pointer; transition: transform .02s ease, background .2s ease, color .2s ease, border-color .2s ease; user-select: none; border: 1px solid transparent; }
        .btn { min-height: 44px; }
        .btn-primary { background: var(--site-color-brand, #c5a067); color: #fff; border-color: var(--site-color-brand, #c5a067); }
        .btn-primary:hover { background: var(--site-color-brand, #c5a067); border-color: var(--site-color-brand, #c5a067); opacity: 0.88; }
        .btn-ghost { background: #fffaf5; color: #6b4e3d; border-color: #e4d2bf; }
        .btn-ghost:hover { background: #fff3e6; border-color: #d9c6b1; }

        /* ===== Responsive ===== */
        @media (max-width: 640px) {
            .form-row { grid-template-columns: 1fr; gap: 4px; }
            .avatar { width: 48px; height: 48px; font-size: 18px; }
            .mypage-title { font-size: 20px; }
        }
    </style>
</head>
<body>

<div class="container">
    @include('layouts.header')

    <div class="page-body">
        <section class="mypage-card" role="main" aria-labelledby="mypageTitle">
            <header class="mypage-header">
                <div class="avatar">👤</div>
                <div>
                    <h1 id="mypageTitle" class="mypage-title">マイページ</h1>
                    <div class="mypage-sub">アカウント情報の編集</div>
                </div>
            </header>

            {{-- プロフィール更新フォーム（入れ子禁止。ログアウトは別フォーム） --}}
            <form id="profile-form" method="POST" action="{{ route('account.update') }}">
                {{-- フラッシュメッセージ（カード内・フォーム先頭） --}}
                @if (session('status'))
                    <div class="flash-message success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="flash-message error" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="flash-message error" role="alert">
                        <ul style="margin:0; padding-left:18px; text-align:left;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @csrf
                @method('PUT')

                <div class="form-list">
                    <div class="form-row">
                        <label class="form-label required" for="name">ユーザー名</label>
                        <div class="form-field">
                            <input id="name" name="name" type="text" class="input"
                                   value="{{ old('name', $user->name) }}" required aria-required="true" autocomplete="name">
                        </div>
                    </div>

                    <div class="form-row">
                        <label class="form-label required" for="nickname">ニックネーム</label>
                        <div class="form-field">
                            <input id="nickname" name="nickname" type="text" class="input"
                                   value="{{ old('nickname', $user->nickname) }}" required aria-required="true" autocomplete="nickname">
                        </div>
                    </div>

                    <div class="form-row">
                        <label class="form-label required" for="email">メールアドレス</label>
                        <div class="form-field">
                            <input id="email" name="email" type="email" class="input"
                                   value="{{ old('email', $user->email) }}" required aria-required="true" autocomplete="email">
                        </div>
                    </div>

                    {{-- パスワードは任意。必須マークも required も付けない --}}
                    <div class="form-row">
                        <label class="form-label" for="password">パスワード</label>
                        <div class="form-field">
                            <input id="password" name="password" type="password" class="input"
                                   placeholder="変更する場合のみ入力" minlength="8" autocomplete="new-password">
                            <small class="help">※ 空欄で送信するとパスワードは変更されません。</small>
                        </div>
                    </div>

                    <div class="form-row">
                        <label class="form-label required" for="postal_code">郵便番号</label>
                        <div class="form-field">
                            <input id="postal_code" name="postal_code" type="text" inputmode="numeric"
                                   class="input" value="{{ old('postal_code', $user->postal_code) }}" required aria-required="true" autocomplete="postal-code">
                        </div>
                    </div>

                    {{-- 都道府県 --}}
                    <div class="form-row">
                        <label class="form-label required" for="prefecture">都道府県</label>
                        <div class="form-field">
                            <select id="prefecture" name="prefecture" class="select" required aria-required="true">
                                <option value="" disabled {{ (string)old('prefecture', $user->prefecture) === '' ? 'selected' : '' }}>選択してください</option>
                                @foreach (config('commons.prefectures') as $key => $label)
                                    <option value="{{ $key }}" {{ (string)old('prefecture', $user->prefecture) === (string)$key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <label class="form-label required" for="city">市区町村</label>
                        <div class="form-field">
                            <input id="city" name="city" type="text" class="input"
                                   value="{{ old('city', $user->city) }}" required aria-required="true" autocomplete="address-level2">
                        </div>
                    </div>

                    <div class="form-row">
                        <label class="form-label required" for="phone_number">電話番号</label>
                        <div class="form-field">
                            <input id="phone_number" name="phone_number" type="tel" inputmode="tel"
                                   class="input" value="{{ old('phone_number', $user->phone_number) }}" required aria-required="true" autocomplete="tel">
                        </div>
                    </div>

                    {{-- 性別 --}}
                    <div class="form-row">
                        <label class="form-label required" for="sex">性別</label>
                        <div class="form-field">
                            <select id="sex" name="sex" class="select" required aria-required="true">
                                <option value="" disabled {{ (string)old('sex', $user->sex) === '' ? 'selected' : '' }}>選択してください</option>
                                @foreach (config('commons.sexs') as $key => $label)
                                    <option value="{{ $key }}" {{ (string)old('sex', $user->sex) === (string)$key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <label class="form-label required" for="birth_date">生年月日</label>
                        <div class="form-field">
                            <input id="birth_date" name="birth_date" type="date" class="input"
                                   value="{{ old('birth_date', $user->birth_date) }}" required aria-required="true" autocomplete="bday">
                        </div>
                    </div>
                </div>
            </form>

            {{-- ログアウト用フォーム --}}
            <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">
                @csrf
            </form>

            <div class="form-actions">
                {{-- 送信先を form 属性で指定して入れ子回避 --}}
                <button type="submit" form="profile-form" class="btn btn-primary">プロフィールを更新</button>
                <button type="submit" form="logout-form" class="btn btn-ghost">ログアウト</button>
            </div>
        </section>
    </div>
</div>

@include('layouts.footer')
</body>
</html>
