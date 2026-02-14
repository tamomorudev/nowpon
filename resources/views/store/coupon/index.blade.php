@extends('layouts.store.app', ['authgroup'=>'store_user'])

@section('title')
クーポン一覧
@endsection


@section('content')
<style>
.highlight-payment {
    background-color: #ffe4e1;
}
</style>

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">クーポン一覧</h1>
        </div>

        <!-- Search Form -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('store.coupon') }}">

                    <!-- 1行目：検索項目 -->
                    <div class="form-row">

                        <div class="col-md-3 mb-3">
                            <label for="coupon_name">クーポン名</label>
                            <input type="text" name="coupon_name" id="coupon_name"
                                value="{{ request('coupon_name') }}"
                                class="form-control">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="coupon_code">クーポンコード</label>
                            <input type="text" name="coupon_code" id="coupon_code"
                                value="{{ request('coupon_code') }}"
                                class="form-control">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="store_name">店舗名</label>
                            <select name="store_name" id="store_name" class="form-control">
                                <option value="">すべて</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}"
                                        {{ request('store_name') == $store->id ? 'selected' : '' }}>
                                        {{ $store->store_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="status">ステータス</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">すべて</option>
                                <option value="prepare" {{ request('status')=='prepare' ? 'selected' : '' }}>掲載予定</option>
                                <option value="active" {{ request('status')=='active' ? 'selected' : '' }}>掲載中</option>
                                <option value="expired" {{ request('status')=='expired' ? 'selected' : '' }}>終了</option>
                            </select>
                        </div>

                    </div>

                    <!-- 2行目：ボタン -->
                    <div class="form-row mt-2">
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-block">検索</button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('store.coupon') }}" class="btn btn-secondary btn-block">クリア</a>
                        </div>
                    </div>

                </form>
            </div>
        </div>


        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>店舗名</th>
                                <th>クーポン名</th>
                                <th>クーポンコード</th>
                                <th>定価</th>
                                <th class="highlight-payment"><b>店舗支払金額</b></th>
                                <th class="highlight-payment">サービス料(手数料)</th>
                                <th>掲載金額</th>
                                <th>コース時間(分)</th>
                                <th>コース開始時間</th>
                                <th>発行開始時間</th>
                                <th>発行終了時間</th>
                                <th>画像</th>
                                <th>ステータス</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($coupons as $coupon)
                            <tr>
                                <td>{{$coupon->store_name}}</td>
                                <td>{{$coupon->coupon_name}}</td>
                                <td>{{$coupon->coupon_code}}</td>
                                <td>{{$coupon->price}}円</td>
                                <td class="highlight-payment">{{ round($coupon->store_pay_price) }}円</td>
                                <td class="highlight-payment">{{$coupon->service_price}}円</td>
                                <td>{{ round($coupon->store_pay_price) + $coupon->service_price}}円</td>
                                <td>{{$coupon->cource_time}}分</td>
                                <td>{{$coupon->cource_start}}</td>
                                <td>{{$coupon->expire_start_date}}</td>
                                <td>{{$coupon->expire_end_date}}</td>
                                <td>
                                    @if($coupon->img_url)
                                        <img width="50" height="50" src="{{ asset('/assets/images/'. $coupon->img_url) }}" >
                                    @endif
                                </td>
                                <td>
                                    {{ couponStatus($coupon) }}
                                </td>
                                <td>
                                    <a class="btn btn-success btn-sm w-100 text-nowrap" href="coupon/detail?ci={{$coupon->id}}">詳細</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <div>
                {{ $coupons->links('pagination::bootstrap-4') }}
            </div>
            <div class="text-muted">
                全 {{ $coupons->total() }} 件中 
                {{ $coupons->firstItem() }} - {{ $coupons->lastItem() }} 件を表示
            </div>
        </div>
    </div>
@endsection
