<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/site/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/site/content-page.css') }}">
    <title>お問い合わせ | ナウポン</title>
</head>

<body>
<div class="container">
    @include('layouts.header')

    <main class="contact-page">

        <div class="contact-page__title">
            <h1 class="contact-page__title-text">お問い合わせ</h1>
        </div>

        <section class="contact-page__card">
            <p class="contact-page__intro">
                ナウポンに関するご質問・ご意見・不具合のご連絡などは、以下のフォームよりお問い合わせください。
            </p>
            <p class="contact-page__intro">
                内容を確認のうえ、必要に応じて担当者よりご連絡いたします。
            </p>

            <div class="contact-page__form-wrapper">
                <!-- ▼▼ Googleフォーム埋め込み ▼▼
                     Googleフォーム編集画面の「送信」→「埋め込む</>」から発行される
                     iframe の src を以下にコピペしてください。
                -->
                <iframe
                    class="contact-page__iframe"
                    src="https://docs.google.com/forms/d/e/1FAIpQLSe3fSLkpdXLU3iZcFRDzdZpWCjNw4QQEHPSkjPQTleqd2pThA/viewform?usp=dialog">
                    読み込んでいます…
                </iframe>
                <!-- ▲▲ Googleフォーム埋め込み ▲▲ -->
            </div>
        </section>

        <div class="contact-page__back">
            <a href="/" class="contact-page__back-link">TOPページへ戻る</a>
        </div>

    </main>

    @include('layouts.footer')
</div>
</body>
</html>
