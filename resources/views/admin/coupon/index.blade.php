@extends('layouts.admin.app', ['authgroup'=>'admin_user'])

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

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>店舗名</th>
                                <th>企業ID</th>
                                <th>クーポン名</th>
                                <th>クーポンコード</th>
                                <th>金額</th>
                                <th>割引金額</th>
                                <th>サービス料</th>
                                <th>掲載金額</th>
                                <th>コース時間</th>
                                <th>コース開始時間</th>
                                <th>発行開始時間</th>
                                <th>発行終了時間</th>
                                <th>画像</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($coupons as $coupon)
                            <tr>
                                <td>{{$coupon->store_name}}</td>
                                <td>{{$coupon->company_id}}</td>
                                <td>{{$coupon->coupon_name}}</td>
                                <td>{{$coupon->coupon_code}}</td>
                                <td>{{$coupon->price}}円</td>
                                @if ($coupon->discount_type == 1)
                                    <td>{{$coupon->discount_price}}%</td>
                                    <td>{{$coupon->service_price}}円</td>
                                    <td>{{ round($coupon->price * (1 - ($coupon->discount_price / 100))) + $coupon->service_price}}円</td>
                                @else
                                    <td>{{$coupon->discount_price}}円</td>
                                    <td>{{$coupon->service_price}}円</td>
                                    <td>{{ round($coupon->price - $coupon->discount_price) + $coupon->service_price}}円</td>
                                @endif
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
                                    @if ($coupon->expire_start_date >= date('Y-m-d H:i:s'))
                                        <a class="form-control btn btn-success btn-block" href="coupon/edit?ci={{$coupon->id}}">編集</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
