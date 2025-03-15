@extends('layouts.store.app', ['authgroup'=>'store_user'])

@section('title')
クーポン編集
@endsection

@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">クーポン編集</h1>
        </div>

        @if (isset($stores) && count($stores) > 0)

            @if($errors->any())
            <div class="d-sm-flex align-items-center justify-content-between mb-4" style="color:red">入力値に誤りがあります。</div>
            @endif

            <form class="user" method="POST" action="{{ route('store.coupon.edit') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name" class="col-md-4 col-form-label text-md-end">店舗<span class="text-danger">*</span></label>
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <select name="store_name" id="name" class="form-control">
                            @foreach($stores as $store)
                                <option value="{{$store->id}}" @if($coupon_data->store_id == $store->id) selected @endif >{{$store->store_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-md-4 col-form-label text-md-end">クーポン名<span class="text-danger">*</span></label>
                    <div class="col-sm-10 mb-3 mb-sm-0">
                        <input type="text" class="form-control" name="coupon_name"
                        value="{{ old('coupon_name', $coupon_data->coupon_name) }}" placeholder="">
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-md-4 col-form-label text-md-end">クーポン金額<span class="text-danger">*</span></label>
                    <div class="col-sm-3 mb-3 mb-sm-0">
                        <input type="number" class="form-control" name="price" id="price"
                               value="{{ old('price', (int)$coupon_data->price) }}" min="0" step="1" placeholder="価格を入力してください">
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-md-4 col-form-label text-md-end">クーポン割引額<span class="text-danger">*</span></label>
                    <div class="row"style="margin-left:0px">
                        <div class="col-sm-3 mb-3 mb-sm-0">
                            <input type="number" class="form-control" name="discount_price" id="discount_price"
                                   value="{{ old('discount_price', $coupon_data->discount_price) }}" min="0" step="1" placeholder="割引額を入力してください">
                        </div>
                        <div class="col-sm-1 mb-3 mb-sm-0">
                            <select name="discount_type" id="discount_type" class="form-control">
                                <option value="0" {{ old('discount_type', $coupon_data->discount_type) == '0' ? 'selected' : '' }}>円</option>
                                <option value="1" {{ old('discount_type', $coupon_data->discount_type) == '1' ? 'selected' : '' }}>％</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-md-4 col-form-label text-md-end">クーポン掲載額：<span style="color:red"><span id='coupon_commit'></span>円</span></label>
                </div>
                <div class="form-group">
                    <label for="name" class="col-md-4 col-form-label text-md-end">コース時間<span class="text-danger">*</span></label>
                    <div class="col-sm-3 mb-3 mb-sm-0">
                        <input type="number" class="form-control" name="cource_time" id="cource_time"
                            min="10" value="{{ old('cource_time', $coupon_data->cource_time) }}" placeholder="">
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-md-4 col-form-label text-md-end">コース開始時間<span class="text-danger">*</span></label>
                    <div class="col-sm-3 mb-3 mb-sm-0">
                        <input type="datetime-local" class="form-control" id="cource_start" name="cource_start" value="{{ old('cource_start', $coupon_data->cource_start) }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-md-4 col-form-label text-md-end">説明<span class="text-danger">*</span></label>
                    <div class="col-sm-10 mb-3 mb-sm-0">
                        <textarea class="form-control" rows="10" cols="60" name="detail">{{ old('detail', $coupon_data->detail) }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                <label for="name" class="col-md-4 col-form-label text-md-end">有効期限<span class="text-danger">*</span></label>
                    <div class="row"style="margin-left:0px">
                        <div class="col-sm-3 mb-3 mb-sm-0">
                            <input type="datetime-local" class="form-control" id="start_date" name="start_date" value="{{ old('start_date', $coupon_data->expire_start_date) }}">
                        </div>
                        ～
                        <div class="col-sm-3">
                            <input type="datetime-local" class="form-control" id="end_date" name="end_date" value="{{ old('end_date', $coupon_data->expire_end_date) }}">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="images" class="col-md-4 col-form-label text-md-end">画像</label>
                    @if($coupon_data->img_url)
                    <div class="col-sm-10 mb-3 mb-sm-0">
                        <img width="50" height="50" src="{{ asset('/assets/images/'. $coupon_data->img_url) }}" >
                    </div>
                    @endif
                    <div class="col-sm-10 mb-3 mb-sm-0">
                        <input type="file" class="form-control" name="images">
                    </div>
                </div>
                <div class="form-group row" style="margin-left:10px">
                    <div class="col-sm-3 mb-3 mb-sm-0">
                        <input type="hidden" id="p_type" name="p_type" value="edit">
                        <input type="hidden" id="ci" name="ci" value="{{$coupon_data->id}}">
                        <input type="submit" class="form-control btn btn-success btn-block" id="">
                    </div>
                </div>
            </form>
        @else
            <div class="d-sm-flex align-items-center justify-content-between mb-4">店舗登録を行ってください</div>
        @endif
    </div>

    <script>
        $(function() {
            // old() の値を保持し、存在しない場合のみ初期化
            let priceInitial = "{{ old('price', $coupon_data->price) }}";
            let discountPriceInitial = "{{ old('discount_price', $coupon_data->discount_price) }}";
            let discountTypeInitial = "{{ old('discount_type', $coupon_data->discount_price) }}";

            $("#price").val(priceInitial);
            $("#discount_price").val(discountPriceInitial);
            $("#discount_type").val(discountTypeInitial);
            $("#coupon_commit").text('0');  // クーポン掲載額は計算結果で初期化

            // 価格、割引額、割引タイプの変更時に同じ処理を適用
            $('#price, #discount_price, #discount_type').on('change keyup', updateCouponCommit);

            // クーポン金額と割引額の入力制限（リアルタイムで制限）
            $("#price, #discount_price").on('input', function() {
                let value = this.value;
                // 先頭の0を削除（ただし単体の0は許可）
                if (value.length > 1 && value.startsWith('0')) {
                    this.value = value.replace(/^0+/, '');
                }
                // 0未満の入力を制限
                if (parseFloat(this.value) < 0 || isNaN(this.value)) {
                    this.value = 0;
                }
            });

            // クーポン掲載額の計算関数を定義
            function updateCouponCommit() {
                let current_price = parseFloat($("#price").val()) || 0;
                let current_discount_price = parseFloat($("#discount_price").val()) || 0;
                let current_discount_type = $("#discount_type").val();
                let commit = current_price; // 初期値

                // 割引額の計算
                if (current_discount_price && current_discount_type) {
                    if (current_discount_type == '1') {  // %割引
                        commit = Math.round(current_price * (1 - (current_discount_price / 100)));
                    } else {  // 円引き
                        commit = Math.round(current_price - current_discount_price);
                    }
                }
                // 値のリセットと表示
                $("#coupon_commit").text(commit > 0 ? commit : '0');
            }

            // ページ読み込み時の初期計算
            updateCouponCommit();
        });
    </script>
@endsection
