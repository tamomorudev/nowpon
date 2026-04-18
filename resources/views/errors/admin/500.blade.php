@extends('layouts.admin.app', ['authgroup'=>'admin_user'])

@section('title')
システムエラー - nowponadmin管理
@endsection

@section('content')
<div class="container-fluid">
    <div style="text-align: center; padding: 80px 20px;">

        <div style="font-size: 100px; font-weight: bold; color: #e0e0e0; line-height: 1; margin-bottom: 0;">
            Error
        </div>

        <div style="width: 60px; height: 4px; background: #cc9074; border-radius: 2px; margin: 16px auto 24px;">
        </div>

        <h4 style="font-weight: bold; color: #5a5c69; margin-bottom: 12px;">
            システムエラーが発生しました
        </h4>

        <p style="color: #858796; font-size: 14px; line-height: 2; margin-bottom: 32px;">
            大変申し訳ありません。<br>
            しばらく時間をおいてから再度お試しください。<br>
            問題が解決しない場合は、お手数ですがお問い合わせください。
        </p>

        <a href="/admin" style="
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