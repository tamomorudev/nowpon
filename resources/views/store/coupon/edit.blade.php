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
                        <input type="text" class="form-control @error('coupon_name') is-invalid @enderror" name="coupon_name"
                        value="{{ old('coupon_name', $coupon_data->coupon_name) }}" placeholder="">
                        @error('coupon_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-md-4 col-form-label text-md-end">定価<span class="text-danger">*</span></label>
                    <div class="col-sm-3 mb-3 mb-sm-0">
                        <input type="number" class="form-control @error('price') is-invalid @enderror" name="price" id="price"
                               value="{{ old('price', (int)$coupon_data->price) }}" min="0" step="1" placeholder="価格を入力してください">
                        @error('price')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-md-4 col-form-label text-md-end">店舗支払金額<span class="text-danger">*</span></label>
                    <div class="col-sm-3 mb-3 mb-sm-0">
                        <input type="number" class="form-control @error('store_pay_price') is-invalid @enderror" name="store_pay_price" id="store_pay_price"
                               value="{{ old('store_pay_price', (int)$coupon_data->store_pay_price) }}" min="0" step="1" placeholder="店舗支払金額を入力してください">
                        @error('store_pay_price')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-form-label text-md-end" style="padding-left: .75rem;">クーポン掲載額：<span style="color:red"><span id='coupon_commit'></span>円</span></label><br>
                    <label for="name" class="col-form-label text-md-end" style="padding-left: .75rem;"><span id='coupon_breakdown'></span></label>
                </div>
                <div class="form-group">
                    <label for="name" class="col-md-4 col-form-label text-md-end">コース時間(分)<span class="text-danger">*</span></label>
                    <div class="col-sm-3 mb-3 mb-sm-0">
                        <input type="number" class="form-control @error('cource_time') is-invalid @enderror" name="cource_time" id="cource_time"
                            min="10" value="{{ old('cource_time', $coupon_data->cource_time) }}" placeholder="">
                        @error('cource_time')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-md-4 col-form-label text-md-end">コース開始時間<span class="text-danger">*</span></label>
                    <div class="col-sm-3 mb-3 mb-sm-0">
                        <input type="datetime-local" class="form-control @error('cource_start') is-invalid @enderror" id="cource_start" name="cource_start" value="{{ old('cource_start', $coupon_data->cource_start) }}">
                        @error('cource_start')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-md-4 col-form-label text-md-end">説明<span class="text-danger">*</span></label>
                    <div class="col-sm-10 mb-3 mb-sm-0">
                        <textarea class="form-control @error('detail') is-invalid @enderror" rows="10" cols="60" name="detail">{{ old('detail', $coupon_data->detail) }}</textarea>
                        @error('detail')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                <label for="name" class="col-md-4 col-form-label text-md-end">有効期限<span class="text-danger">*</span></label>
                    <div class="row"style="margin-left:0px">
                        <div class="col-sm-3 mb-3 mb-sm-0">
                            <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date', $coupon_data->expire_start_date) }}">
                            @error('start_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        ～
                        <div class="col-sm-3">
                            <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date', $coupon_data->expire_end_date) }}">
                            @error('end_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="images" class="col-md-4 col-form-label text-md-end">画像</label>
                    @if($coupon_data->img_url)
                    <div class="col-sm-10 mb-3 mb-sm-0">
                        <img width="50" height="50" src="{{ asset('/assets/images/'. $coupon_data->img_url) }}" >
                        @if($coupon_data->img_url_2)
                            <img width="50" height="50" src="{{ asset('/assets/images/'. $coupon_data->img_url_2) }}" >
                        @endif
                        @if($coupon_data->img_url_3)
                            <img width="50" height="50" src="{{ asset('/assets/images/'. $coupon_data->img_url_3) }}" >
                        @endif
                        @if($coupon_data->img_url_4)
                            <img width="50" height="50" src="{{ asset('/assets/images/'. $coupon_data->img_url_4) }}" >
                        @endif
                        @if($coupon_data->img_url_5)
                            <img width="50" height="50" src="{{ asset('/assets/images/'. $coupon_data->img_url_5) }}" >
                        @endif
                    </div>
                    @endif
                    <br>
                    <div class="col-sm-10 mb-3 mb-sm-3">画像を更新する際は、すべての画像を再度アップロードしてください。</div>
                    <div class="col-sm-10 mb-3 mb-sm-3">
                        <input type="file" class="form-control" name="images">
                    </div>
                    <div class="col-sm-10 mb-3 mb-sm-3">
                        <input type="file" class="form-control" name="images_2">
                    </div>
                    <div class="col-sm-10 mb-3 mb-sm-3">
                        <input type="file" class="form-control" name="images_3">
                    </div>
                    <div class="col-sm-10 mb-3 mb-sm-3">
                        <input type="file" class="form-control" name="images_4">
                    </div>
                    <div class="col-sm-10 mb-3 mb-sm-0">
                        <input type="file" class="form-control" name="images_5">
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
            let StorePayPriceInitial = "{{ old('store_pay_price', $coupon_data->store_pay_price) }}";

            $("#price").val(priceInitial);
            $("#store_pay_price").val(StorePayPriceInitial);
            $("#coupon_commit").text('0');  // クーポン掲載額は計算結果で初期化

            // 価格、割引額、割引タイプの変更時に同じ処理を適用
            $('#price, #discount_price, #discount_type, #store_pay_price').on('change keyup', updateCouponCommit);

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
                let current_store_pay_price = parseFloat($("#store_pay_price").val()) || 0;
                let commit = current_price; // 初期値
                let commit_store_pay_price = current_store_pay_price; // 初期値(店舗支払)
                let breakdown = '';

                services = Math.round(commit_store_pay_price * 0.15);
                breakdown = '(店舗支払金額:'+commit_store_pay_price+'円 + サービス手数料(15%):'+services+')円';
                commit = parseInt(commit_store_pay_price) + parseInt(services);

                if (current_price <= 0 || commit_store_pay_price <= 0 || commit <= 0) {
                    breakdown = '';
                }

                // 値のリセットと表示
                $("#coupon_commit").text(commit > 0 ? commit : '0');
                $("#coupon_breakdown").text(breakdown);
            }

            // ページ読み込み時の初期計算
            updateCouponCommit();
        });
    </script>
@endsection
