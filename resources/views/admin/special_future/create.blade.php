@extends('layouts.admin.app', ['authgroup'=>'admin_user'])

@section('title')
特集作成
@endsection

@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">特集作成登録</h1>
        </div>

        @if($errors->any())
        <div class="d-sm-flex align-items-center justify-content-between mb-4" style="color:red">入力値に誤りがあります。</div>
        @endif

        <form class="user" method="POST" action="{{ route('admin.special_future.create') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name" class="col-md-4 col-form-label text-md-end">特集名<span class="text-danger">*</span></label>
                <div class="col-sm-10 mb-3 mb-sm-0">
                    <input type="text" class="form-control" name="name"
                            value="{{ old('name') }}" placeholder="">
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-4 col-form-label text-md-end">内容<span class="text-danger">*</span></label>
                <div class="col-sm-10 mb-3 mb-sm-0">
                    <textarea class="form-control" rows="10" cols="60" name="detail">{{ old('detail') }}</textarea>
                </div>
            </div>
            <div class="form-group">
            <label for="name" class="col-md-4 col-form-label text-md-end">期間<span class="text-danger">*</span></label>
                <div class="row"style="margin-left:0px">
                    <div class="col-sm-3 mb-3 mb-sm-0">
                        <input type="datetime-local" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') }}">
                    </div>
                    ～
                    <div class="col-sm-3">
                        <input type="datetime-local" class="form-control" id="end_date" name="end_date" value="{{ old('end_date') }}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="images" class="col-md-4 col-form-label text-md-end">画像</label>
                <div class="col-sm-10 mb-3 mb-sm-0">
                    <input type="file" class="form-control" name="images">
                </div>
            </div>
            <div class="form-group row" style="margin-left:10px">
                <div class="col-sm-3 mb-3 mb-sm-0">
                    <input type="submit" class="form-control btn btn-success btn-block" id="">
                </div>
            </div>
        </form>
    </div>

@endsection
