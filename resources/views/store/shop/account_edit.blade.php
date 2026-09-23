@extends('layouts.store.app', ['authgroup'=>'store_user'])

@section('title', '店舗ユーザー編集')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">店舗ユーザー編集</h1>
    @if($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card shadow mb-4"><div class="card-body">
        <form method="POST" action="{{ route('store.account.edit', ['ui' => $store_user->id]) }}">
            @csrf
            <div class="form-group">
                <label for="account-name">ユーザー名</label>
                <input id="account-name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $store_user->name) }}" maxlength="100" required autocomplete="username" aria-describedby="name-help">
                <small id="name-help" class="form-text text-muted">ログインに使用するユーザー名です。</small>
            </div>
            <div class="form-group">
                <label for="account-password">パスワード</label>
                <input id="account-password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" minlength="10" maxlength="100" autocomplete="new-password" aria-describedby="password-help">
                <small id="password-help" class="form-text text-muted">変更する場合のみ、10〜100文字で入力してください。空欄の場合は現在のパスワードを維持します。</small>
            </div>
            <div class="d-flex" style="gap:12px">
                <button type="submit" class="btn btn-success">更新</button>
                <a href="{{ route('store.account.index') }}" class="btn btn-secondary">戻る</a>
            </div>
        </form>
    </div></div>
</div>
@endsection
