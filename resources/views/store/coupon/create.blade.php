@extends('layouts.store.app', ['authgroup'=>'store_user'])

@section('title')
クーポン作成
@endsection

@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">クーポン登録</h1>
        </div>

        @if (isset($stores) && count($stores) > 0)

            @if(isset($error))
            <div class="d-sm-flex align-items-center justify-content-between mb-4" style="color:red">入力値に誤りがあります。</div>
            @endif

            <form class="user" method="POST" action="{{ route('store.coupon.create') }}">
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
                    <label for="name" class="col-md-4 col-form-label text-md-end">クーポン名</label>
                    <div class="col-sm-10 mb-3 mb-sm-0">
                        <input type="text" class="form-control" name="coupon_name"
                            placeholder="">
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-md-4 col-form-label text-md-end">クーポン金額</label>
                    <div class="col-sm-3 mb-3 mb-sm-0">
                        <input type="number" class="form-control" name="price" id="price"
                            placeholder="">
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-md-4 col-form-label text-md-end">クーポン割引額</label>
                    <div class="row"style="margin-left:0px">
                        <div class="col-sm-3 mb-3 mb-sm-0">
                            <input type="number" class="form-control" name="discount_price" id="discount_price"
                                placeholder="">
                        </div>
                        <div class="col-sm-1 mb-3 mb-sm-0">
                            <select name="discount_type" id="discount_type" class="form-control">
                                <option value="0">円</option>
                                <option value="1">％</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-md-4 col-form-label text-md-end">クーポン掲載額：<span style="color:red"><span id='coupon_commit'></span>円</span></label>
                </div>
                <div class="form-group">
                    <label for="name" class="col-md-4 col-form-label text-md-end">説明</label>
                    <div class="col-sm-10 mb-3 mb-sm-0">
                        <textarea class="form-control" rows="10" cols="60" name="detail"></textarea>
                    </div>
                </div>
                <div class="form-group">
                <label for="name" class="col-md-4 col-form-label text-md-end">有効期限</label>
                    <div class="row"style="margin-left:0px">
                        <div class="col-sm-3 mb-3 mb-sm-0">
                            <input type="datetime-local" class="form-control" id="start_date" name="start_date">
                        </div>
                        ～
                        <div class="col-sm-3">
                            <input type="datetime-local" class="form-control" id="end_date" name="end_date">
                        </div>
                    </div>
                </div>
                <div class="form-group row" style="margin-left:10px">
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

    </div>

    <script>
        $(function(){
            $("#coupon_commit").text('0');
            $('#price').change(function() {
                current_price = $("#price").val();
                current_discount_price = $("#discount_price").val();
                current_discount_type = $("#discount_type").val();

                $("#coupon_commit").text('0');

                if (current_discount_price && current_discount_type) {
                    if (current_discount_type == 1) {
                        commit = Math.round(parseInt(current_price) * (1 - (parseInt(current_discount_price) / 100)));
                    } else {
                        commit = Math.round(parseInt(current_price) - parseInt(current_discount_price));
                    }
                    if (commit <= 0) {
                        $("#coupon_commit").text('0');
                    } else {
                        $("#coupon_commit").text(commit);
                    }
                } else if (current_price) {
                    $("#coupon_commit").text(current_price);
                } else {
                    $("#coupon_commit").text('0');
                }
                
            });

            $('#discount_price').change(function() {
                current_price = $("#price").val();
                current_discount_price = $("#discount_price").val();
                current_discount_type = $("#discount_type").val();

                $("#coupon_commit").text('0');

                if (current_discount_price && current_discount_type) {
                    if (current_discount_type == 1) {
                        commit = Math.round(parseInt(current_price) * (1 - (parseInt(current_discount_price) / 100)));
                    } else {
                        commit = Math.round(parseInt(current_price) - parseInt(current_discount_price));
                    }
                    if (commit <= 0) {
                        $("#coupon_commit").text('0');
                    } else {
                        $("#coupon_commit").text(commit);
                    }
                } else if (current_price) {
                    $("#coupon_commit").text(current_price);
                } else {
                    $("#coupon_commit").text('0');
                }
                
            });

            $('#discount_type').change(function() {
                current_price = $("#price").val();
                current_discount_price = $("#discount_price").val();
                current_discount_type = $("#discount_type").val();

                $("#coupon_commit").text('0');

                if (current_discount_price && current_discount_type) {
                    if (current_discount_type == 1) {
                        commit = Math.round(parseInt(current_price) * (1 - (parseInt(current_discount_price) / 100)));
                    } else {
                        commit = Math.round(parseInt(current_price) - parseInt(current_discount_price));
                    }
                    if (commit <= 0) {
                        $("#coupon_commit").text('0');
                    } else {
                        $("#coupon_commit").text(commit);
                    }
                } else if (current_price) {
                    $("#coupon_commit").text(current_price);
                } else {
                    $("#coupon_commit").text('0');
                }
                
            });
        });
    </script>
@endsection
