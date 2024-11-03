@extends('layouts.store.app', ['authgroup'=>'store_user'])

@section('title')
コース登録
@endsection

@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">コース登録</h1>
        </div>

        @if (isset($stores) && count($stores) > 0)

            @if(isset($error))
            <div class="d-sm-flex align-items-center justify-content-between mb-4" style="color:red">入力値に誤りがあります。</div>
            @endif

            <form class="user" method="POST" action="{{ route('store.shop.cource') }}">
                @csrf
                <div class="form-group">
                    <label for="name" class="col-md-4 col-form-label text-md-end">店舗</label>
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <select name="store_name" id="name" class="form-control">
                            <option value="1">店舗1</option>
                            <option value="2">店舗2</option>
                            <option value="3">店舗3</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-md-4 col-form-label text-md-end">メニュー名</label>
                    <div class="col-sm-10 mb-3 mb-sm-0">
                        <input type="text" class="form-control" name="cource_name"
                            placeholder="">
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-md-4 col-form-label text-md-end">クーポン金額</label>
                    <div class="col-sm-3 mb-3 mb-sm-0">
                        <input type="text" class="form-control" name="price"
                            placeholder="">
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-md-4 col-form-label text-md-end">クーポン割引額</label>
                    <div class="col-sm-3 mb-3 mb-sm-0">
                        <input type="text" class="form-control" name="discount"
                            placeholder="">
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-md-4 col-form-label text-md-end">説明</label>
                    <div class="col-sm-10 mb-3 mb-sm-0">
                        <textarea class="form-control" rows="10" cols="60" name="detail"></textarea>
                    </div>
                </div>
                <div class="form-group row"style="margin-left:10px">
                    <div class="col-sm-3 mb-3 mb-sm-0">
                        <input type="submit" class="form-control btn btn-success btn-block" id="">
                    </div>
                    <div class="col-sm-3 mb-3 mb-sm-0">
                        <input type="submit" class="form-control btn btn-primary btn-block" id="">
                    </div>
                </div>
            </form>
        @else
            <div class="d-sm-flex align-items-center justify-content-between mb-4">店舗登録を行ってください</div>
        @endif

    </div>
@endsection
