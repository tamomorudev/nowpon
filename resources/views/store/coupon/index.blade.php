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
                                <th>定価</th>
                                <th>割引金額</th>
                                <th>サービス料(手数料)</th>
                                <th>割引後の掲載金額</th>
                                <th><b>店舗支払金額</b></th>
                                <th>コース時間(分)</th>
                                <th>コース開始時間</th>
                                <th>発行開始時間</th>
                                <th>発行終了時間</th>
                                <th>画像</th>
                                <th>ステータス</th>
                                <th></th>
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
                                @if ($coupon->discount_type == 1)
                                    <td>{{$coupon->discount_price}}%</td>
                                    <td>{{$coupon->service_price}}円</td>
                                    <td>{{ round($coupon->price * (1 - ($coupon->discount_price / 100))) + $coupon->service_price}}円</td>
                                    <td style="color: red; font-weight: bold;">{{ round($coupon->price * (1 - ($coupon->discount_price / 100))) }}円</td>
                                @else
                                    <td>{{$coupon->discount_price}}円</td>
                                    <td>{{$coupon->service_price}}円</td>
                                    <td>{{ round($coupon->price - $coupon->discount_price) + $coupon->service_price}}円</td>
                                    <td style="color: red; font-weight: bold;">{{ round($coupon->price - $coupon->discount_price) }}円</td>
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
                                    {{ couponStatus($coupon) }}
                                </td>
                                <td>
                                    @if ($coupon->expire_start_date > date('Y-m-d H:i:s'))
                                        <a class="btn btn-success btn-sm w-100 text-nowrap" href="coupon/edit?ci={{$coupon->id}}">編集</a>
                                    @endif
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('store.coupon.delete') }}">
                                        @csrf
                                        <div>
                                            <input type="hidden" id="p_type" name="p_type" value="edit">
                                            <input type="hidden" id="ci" name="ci" value="{{$coupon->id}}">
                                            <input type="submit" class="btn btn-danger btn-sm w-100 text-nowrap" id="" value='削除'>
                                        </div>
                                    </form>
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
