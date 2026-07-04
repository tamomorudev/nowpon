<!DOCTYPE html>
<html lang="ja">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- ▼ 詳細検索パーツ ▼ --}}
    <link rel="stylesheet" href="{{ asset('css/site/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/site/search.css') }}">
    <link rel="stylesheet" href="{{ asset('css/site/purchase-history.css') }}">
    <script src="{{ asset('js/site/search.js') }}" defer></script>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ナウポンTOP</title>
</head>
<body>
    <div class="container">
        @include('layouts.header')

        <div class="cancel-complete-message">
        <h1>キャンセルが完了しました</h1>
        <a href="{{ route('purchaseHistory') }}">購入履歴に戻る</a>
    </div>
    </div>
</body>

@include('layouts.footer')
</html>
