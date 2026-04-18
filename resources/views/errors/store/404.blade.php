@extends('layouts.store.app', ['authgroup'=>'store_user'])

@section('title')
ページが見つかりません - nowponストア管理
@endsection

@section('content')
<div class="container-fluid">
    <div style="text-align: center; padding: 80px 20px;">

        <div style="font-size: 100px; font-weight: bold; color: #e0e0e0; line-height: 1; margin-bottom: 0;">
            404
        </div>

        <div style="width: 60px; height: 4px; background: #cc9074; border-radius: 2px; margin: 16px auto 24px;">
        </div>

        <h4 style="font-weight: bold; color: #5a5c69; margin-bottom: 12px;">
            ページが見つかりませんでした
        </h4>

        <p style="color: #858796; font-size: 14px; line-height: 2; margin-bottom: 32px;">
            お探しのページは存在しないか、移動・削除された可能性があります。<br>
            URLをご確認いただくか、管理画面トップからお探しください。
        </p>

        <a href="/store" style="
            display: inline-block;
            background-color: #cc9074;
            color: #fff;
            font-weight: bold;
            font-size: 14px;
            padding: 12px 40px;
            border-radius: 6px;
            text-decoration: none;
        ">
            管理画面トップへ戻る
        </a>

    </div>
</div>
@endsection