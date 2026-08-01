<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/site/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/site/content-page.css') }}">
    <title>掲載店舗一覧 | ナウポン</title>
</head>
<body>
<div class="container">
    @include('layouts.header')

    <main class="terms-page site-list-page">
        <div class="terms-page__title">
            <h1 class="terms-page__title-text">掲載店舗一覧</h1>
        </div>

        <section class="terms-page__card" aria-labelledby="storeListTitle">
            <h2 id="storeListTitle" class="terms-page__section-title">ナウポンの掲載店舗</h2>
            <ul class="site-list__items">
                <li>ヘアサロン新宿店</li>
                <li>ヘアサロン新宿三丁目店</li>
            </ul>
            <div class="terms-page__back">
                <a href="{{ route('couponlist') }}" class="site-button site-button--primary">クーポンを探す</a>
            </div>
        </section>
    </main>

    @include('layouts.footer')
</div>
</body>
</html>
