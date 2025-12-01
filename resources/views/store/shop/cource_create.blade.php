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

            <form class="user" method="POST" action="{{ route('store.shop.cource') }}">
                @csrf
                <div class="form-group">
                    <label for="name" class="col-md-4 col-form-label text-md-end">店舗</label>
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <select name="store_name" id="name" class="form-control">
                            @foreach($stores as $store)
                                <option value="{{$store->id}}">{{$store->store_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="cource_name" class="col-md-4 col-form-label text-md-end">メニュー名</label>
                    <div class="col-sm-10 mb-3 mb-sm-0">
                        <input type="text" class="form-control @error('cource_name') is-invalid @enderror" name="cource_name"
                            placeholder="">
                        @error('cource_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="price" class="col-md-4 col-form-label text-md-end">クーポン金額</label>
                    <div class="col-sm-3 mb-3 mb-sm-0">
                        <input type="text" class="form-control @error('price') is-invalid @enderror" name="price"
                            placeholder="">
                        @error('price')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="detail" class="col-md-4 col-form-label text-md-end">説明</label>
                    <div class="col-sm-10 mb-3 mb-sm-0">
                        <textarea class="form-control @error('detail') is-invalid @enderror" rows="10" cols="60" name="detail"></textarea>
                        @error('detail')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row"style="margin-left:10px">
                    <div class="col-sm-3 mb-3 mb-sm-0">
                        <input type="submit" class="form-control btn btn-success btn-block" id="">
                    </div>
                </div>
            </form>
        @else
            <div class="d-sm-flex align-items-center justify-content-between mb-4">店舗登録を行ってください</div>
        @endif

    </div>
@endsection
