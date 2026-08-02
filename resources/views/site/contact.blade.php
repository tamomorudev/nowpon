<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/site/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/site/content-page.css') }}?v={{ filemtime(public_path('css/site/content-page.css')) }}">
    <title>お問い合わせ | ナウポン</title>
</head>

<body>
<div class="container">
    @include('layouts.header')

    <main class="contact-page">
        <div class="contact-page__title">
            <h1 class="contact-page__title-text">お問い合わせ</h1>
        </div>

        <section class="contact-page__card" aria-labelledby="contact-form-title">
            <h2 id="contact-form-title" class="contact-page__heading">ご質問・ご意見をお聞かせください</h2>
            <p class="contact-page__intro">ナウポンに関するご質問、ご意見、不具合のご連絡は、以下のフォームからお送りください。</p>
            <p class="contact-page__note">通常、内容を確認のうえ担当者よりご連絡します。</p>

            @if (session('contact_status'))
                <div id="contact-status" class="contact-page__alert contact-page__alert--success" role="status" tabindex="-1">
                    {{ session('contact_status') }}
                </div>
                <div class="contact-page__complete-actions">
                    <a href="/" class="contact-page__back-link">TOPページへ戻る</a>
                </div>
            @else
                @if (session('contact_error'))
                    <div class="contact-page__alert contact-page__alert--error" role="alert">
                        {{ session('contact_error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div id="contact-error-summary" class="contact-page__alert contact-page__alert--error" role="alert" tabindex="-1">
                        <p>入力内容をご確認ください。</p>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="contact-form" method="POST" action="{{ route('contact.submit') }}">
                    @csrf

                    <div class="contact-form__field">
                        <label for="contact-name" class="contact-form__label">お名前 <span class="contact-form__required">必須</span></label>
                        <input id="contact-name" name="name" type="text" maxlength="100" class="contact-form__input @error('name') contact-form__input--error @enderror"
                               value="{{ old('name') }}" autocomplete="name" enterkeyhint="next" required aria-required="true" aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}" @error('name') aria-describedby="contact-name-error" @enderror>
                        @error('name')
                            <p id="contact-name-error" class="contact-form__error" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="contact-form__field">
                        <label for="contact-email" class="contact-form__label">メールアドレス <span class="contact-form__required">必須</span></label>
                        <input id="contact-email" name="email" type="email" maxlength="255" class="contact-form__input @error('email') contact-form__input--error @enderror"
                               value="{{ old('email') }}" autocomplete="email" inputmode="email" enterkeyhint="next" required aria-required="true" aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}" aria-describedby="contact-email-help @error('email') contact-email-error @enderror">
                        <p id="contact-email-help" class="contact-form__help">ご返信が必要な場合に使用します。</p>
                        @error('email')
                            <p id="contact-email-error" class="contact-form__error" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="contact-form__field">
                        <label for="contact-type" class="contact-form__label">お問い合わせ種別 <span class="contact-form__optional">任意</span></label>
                        <select id="contact-type" name="inquiry_type" class="contact-form__input contact-form__select @error('inquiry_type') contact-form__input--error @enderror" aria-invalid="{{ $errors->has('inquiry_type') ? 'true' : 'false' }}" @error('inquiry_type') aria-describedby="contact-type-error" @enderror>
                            <option value="">選択してください</option>
                            @foreach ($inquiryTypes as $value => $label)
                                <option value="{{ $value }}" @selected(old('inquiry_type') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('inquiry_type')
                            <p id="contact-type-error" class="contact-form__error" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="contact-form__field">
                        <label for="contact-message" class="contact-form__label">内容 <span class="contact-form__required">必須</span></label>
                        <textarea id="contact-message" name="message" rows="8" maxlength="5000" class="contact-form__input contact-form__textarea @error('message') contact-form__input--error @enderror"
                                  required aria-required="true" aria-invalid="{{ $errors->has('message') ? 'true' : 'false' }}" @error('message') aria-describedby="contact-message-error" @enderror>{{ old('message') }}</textarea>
                        @error('message')
                            <p id="contact-message-error" class="contact-form__error" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="contact-form__actions">
                        <button type="submit" class="contact-form__submit">送信する</button>
                    </div>
                </form>
            @endif
        </section>

        @if (!session('contact_status'))
            <div class="contact-page__back">
                <a href="/" class="contact-page__back-link">TOPページへ戻る</a>
            </div>
        @endif
    </main>

    @include('layouts.footer')
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var focusTarget = document.getElementById('contact-status') || document.getElementById('contact-error-summary');
    if (focusTarget) focusTarget.focus();

    var form = document.querySelector('.contact-form');
    if (!form) return;

    form.addEventListener('submit', function () {
        if (!form.checkValidity()) return;

        var submitButton = form.querySelector('button[type="submit"]');
        form.setAttribute('aria-busy', 'true');
        submitButton.disabled = true;
        submitButton.textContent = '送信中…';
    });
});
</script>
</body>
</html>
