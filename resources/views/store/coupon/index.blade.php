@extends('layouts.store.app', ['authgroup'=>'store_user'])

@section('title')
クーポン一覧
@endsection


@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">クーポン一覧</h1>
        </div>

        {{$user->name}}のクーポン

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
                                <th>金額</th>
                                <th>割引金額</th>
                                <th>発行開始時間</th>
                                <th>発行終了時間</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($coupons as $coupon)
                            <tr>
                                <td>{{$coupon->store_name}}</td>
                                <td>{{$coupon->coupon_name}}</td>
                                <td>{{$coupon->coupon_code}}</td>
                                <td>{{$coupon->price}}</td>
                                <td>{{$coupon->discount_price}}</td>
                                <td>{{$coupon->expire_start_date}}</td>
                                <td>{{$coupon->expire_end_date}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
