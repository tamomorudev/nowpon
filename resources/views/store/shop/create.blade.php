@extends('layouts.store.app', ['authgroup'=>'store_user'])

@section('title')
店舗登録
@endsection

@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">店舗登録</h1>
        </div>

        @if(isset($error))
            <div class="d-sm-flex align-items-center justify-content-between mb-4" style="color:red">入力値に誤りがあります。</div>
        @endif

        <form class="user" method="POST" action="{{ route('store.shop.create') }}">
            @csrf
            <div class="form-group">
                <label for="name" class="col-md-4 col-form-label text-md-end">店舗名</label>
                <div class="col-sm-10 mb-3 mb-sm-0">
                    <input type="text" class="form-control" name="store_name"
                        placeholder="">
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-4 col-form-label text-md-end">メールアドレス</label>
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" class="form-control" name="email"
                        placeholder="">
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-4 col-form-label text-md-end">郵便番号</label>
                <div class="col-sm-3 mb-3 mb-sm-0">
                    <input type="text" class="form-control" name="postal_code"
                        placeholder="">
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-4 col-form-label text-md-end">都道府県</label>
                <div class="col-sm-3 mb-3 mb-sm-0">
                    <select name="address1" id="address1" class="form-control">
                        <option value="1">北海道</option>
                        <option value="2">青森県</option>
                        <option value="3">岩手県</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-4 col-form-label text-md-end">市区町村</label>
                <div class="col-sm-3 mb-3 mb-sm-0">
                    <input type="text" class="form-control" name="address2"
                        placeholder="">
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-4 col-form-label text-md-end">住所</label>
                <div class="col-sm-10 mb-3 mb-sm-0">
                    <input type="text" class="form-control" name="address3"
                        placeholder="">
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-4 col-form-label text-md-end">電話番号</label>
                <div class="col-sm-3 mb-3 mb-sm-0">
                    <input type="text" class="form-control" name="phone_number"
                        placeholder="">
                </div>
            </div>
            <div class="form-group row"style="margin-left:10px">
                <div class="col-sm-3 mb-3 mb-sm-0">
                    <input type="submit" class="form-control btn btn-success btn-block" id="" placeholder="">
                </div>
                <div class="col-sm-3 mb-3 mb-sm-0">
                    <input type="submit" class="form-control btn btn-primary btn-block" id="" placeholder="">
                </div>
            </div>
        </form>

    </div>
@endsection
