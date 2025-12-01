@extends('layouts.store.app', ['authgroup'=>'store_user'])

@section('title')
店舗作成
@endsection

@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">店舗ユーザー登録</h1>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>入力値に誤りがあります。</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <form class="user" method="POST" action="{{ route('store.account.create') }}">
            @csrf
            <div class="form-group">
                <label for="name" class="col-md-4 col-form-label text-md-end">ユーザー名</label>
                <div class="col-sm-10 mb-3 mb-sm-0">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                        placeholder="">
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-4 col-form-label text-md-end">パスワード</label>
                <div class="col-sm-10 mb-3 mb-sm-0">
                    <input type="text" class="form-control @error('password') is-invalid @enderror" name="password"
                        placeholder="">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row"style="margin-left:10px">
                <div class="col-sm-3 mb-3 mb-sm-0">
                    <input type="submit" class="form-control btn btn-success btn-block" id="" placeholder="" value="登録">
                </div>
            </div>
        </form>

    </div>
@endsection
