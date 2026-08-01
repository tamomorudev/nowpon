<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/site/common.css') }}">
    <title>新規会員登録</title>
</head>
<body>
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
    .register-card {
        width: 100%;
        max-width: 800px;
        background: #fffaf5;
        border: 1px solid #ead8c6;
        border-radius: 12px;
        box-shadow: 0 10px 24px rgba(0,0,0,0.10), 0 1px 0 rgba(255,255,255,0.8) inset;
        padding: 28px;
        box-sizing: border-box;
        position: relative;
    }
    .register-card::before {
        content: "";
        position: absolute;
        top: 10px; left: 50%;
        transform: translateX(-50%);
        width: 72px; height: 4px;
        background: var(--site-color-brand, #c5a067);
        border-radius: 9999px;
        opacity: .9;
    }
    .register-title {
        margin: 14px 0 24px;
        text-align: center;
        font-weight: 700;
        font-size: 22px;
        color: #6b4e3d;
    }

    /* ===== Form ===== */
    .form-group { margin-bottom: 16px; }
    .form-label {
        display: block;
        margin-bottom: 6px;
        font-size: 14px;
        font-weight: 600;
        color: #6b4e3d;
    }
    .form-control,
    .form-select {
        width: 100%;
        height: 44px;
        padding: 10px 12px;
        border: 1px solid #d9d9d9;
        border-radius: 8px;
        font-size: 15px;
        box-sizing: border-box;
        background: #fff;
        transition: box-shadow .15s, border-color .15s;
    }
    .form-control:focus,
    .form-select:focus {
        border-color: var(--site-color-brand, #c5a067);
        box-shadow: 0 0 0 3px rgba(194,150,99,.2);
    }
    .form-control:focus-visible,
    .form-select:focus-visible {
        outline: none;
    }
    .invalid-feedback {
        margin-top: 4px;
        color: #c0392b;
        font-size: 12px;
    }

    .form-radio-group {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        align-items: center;
    }
    .form-radio-group label {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
        color: #3b2a22;
        user-select: none;
    }

    .form-actions {
        margin-top: 40px;
        text-align: center;    /* 中央揃え */
    }
    .btn-primary {
        width: auto;                 /* ← 幅を自動調整（テキストサイズに合わせる） */
        min-width: 180px;             /* ← 最低幅を設定（見た目安定） */
        height: 40px;                 /* ← 高さを少し低めに */
        padding: 0 20px;              /* ← 横の余白を少し少なめに */
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--site-color-brand, #c5a067);
        color: #fff;
        border-radius: 9999px;
        border: none;
        font-weight: 700;
        cursor: pointer;
        transition: background .2s;
    }
    .btn-primary:hover { background: var(--site-color-brand, #c5a067); opacity: 0.88; }
    .btn-primary { height: 44px; }
    .form-required-note { margin: -12px 0 20px; color: #6b7280; font-size: 13px; }
    .required-mark { color: #c0392b; }
    .form-hint { display: block; margin-top: 4px; color: #6b7280; font-size: 12px; }
    .form-fieldset { margin: 0 0 16px; padding: 0; border: 0; }
</style>

<main class="page-main">
    <div class="register-card" role="main" aria-labelledby="registerTitle">
        <h1 id="registerTitle" class="register-title">新規会員登録</h1>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <p class="form-required-note"><span class="required-mark">*</span> は必須項目です。</p>

            <div class="form-group">
                <label for="name" class="form-label">氏名 <span class="required-mark" aria-hidden="true">*</span></label>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                       name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="nickname" class="form-label">ニックネーム <span class="required-mark" aria-hidden="true">*</span></label>
                <input id="nickname" type="text" class="form-control @error('nickname') is-invalid @enderror"
                       name="nickname" value="{{ old('nickname') }}" required>
                @error('nickname') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">メールアドレス <span class="required-mark" aria-hidden="true">*</span></label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                       name="email" value="{{ old('email') }}" required autocomplete="email">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">パスワード <span class="required-mark" aria-hidden="true">*</span></label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                       name="password" required minlength="8" autocomplete="new-password" aria-describedby="password-hint">
                <small id="password-hint" class="form-hint">8文字以上で入力してください。</small>
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="password-confirm" class="form-label">パスワード(確認用) <span class="required-mark" aria-hidden="true">*</span></label>
                <input id="password-confirm" type="password" class="form-control"
                       name="password_confirmation" required autocomplete="new-password">
            </div>

            <div class="form-group">
                <label for="postal_code" class="form-label">郵便番号 <span class="required-mark" aria-hidden="true">*</span></label>
                <input id="postal_code" type="text" class="form-control @error('postal_code') is-invalid @enderror"
                       name="postal_code" value="{{ old('postal_code') }}" required inputmode="numeric" autocomplete="postal-code" maxlength="7" placeholder="1000001">
                <small class="form-hint">ハイフンなしの7桁で入力してください。</small>
                @error('postal_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="prefecture" class="form-label">都道府県 <span class="required-mark" aria-hidden="true">*</span></label>
                <select name="prefecture" id="prefecture" class="form-select @error('prefecture') is-invalid @enderror" required>
                    <option value="" {{ old('prefecture') === null ? 'selected' : '' }}>未選択</option>
                    @foreach(config('commons.prefectures') as $key => $prefecture)
                        <option value="{{ $key }}" {{ old('prefecture') !== null && old('prefecture') == $key ? 'selected' : '' }}>
                            {{ $prefecture }}
                        </option>
                    @endforeach
                </select>
                @error('prefecture') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="city" class="form-label">市区町村 <span class="required-mark" aria-hidden="true">*</span></label>
                <input id="city" type="text" class="form-control @error('city') is-invalid @enderror"
                       name="city" value="{{ old('city') }}" required>
                @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="phone_number" class="form-label">電話番号 <span class="required-mark" aria-hidden="true">*</span></label>
                <input id="phone_number" type="text" class="form-control @error('phone_number') is-invalid @enderror"
                       name="phone_number" value="{{ old('phone_number') }}" required inputmode="tel" autocomplete="tel" maxlength="13" placeholder="09012345678">
                <small class="form-hint">ハイフンあり・なしのどちらでも入力できます。</small>
                @error('phone_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <fieldset class="form-fieldset">
                <legend class="form-label">性別 <span class="required-mark" aria-hidden="true">*</span></legend>
                <div class="form-radio-group">
                    <label><input type="radio" name="sex" value="1" {{ old('sex', 1) == 1 ? 'checked' : '' }}> 男性</label>
                    <label><input type="radio" name="sex" value="0" {{ old('sex') !== null && old('sex') == 0 ? 'checked' : '' }}> 女性</label>
                </div>
                @error('sex') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </fieldset>

            <div class="form-group">
                <label for="birth_date" class="form-label">生年月日 <span class="required-mark" aria-hidden="true">*</span></label>
                <input id="birth_date" type="date" class="form-control @error('birth_date') is-invalid @enderror"
                       name="birth_date" value="{{ old('birth_date') }}" required max="{{ now()->toDateString() }}" autocomplete="bday">
                @error('birth_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">登録</button>
            </div>
        </form>
    </div>
</main>

@include('layouts.footer')
</body>
</html>
