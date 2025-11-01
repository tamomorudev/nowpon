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
