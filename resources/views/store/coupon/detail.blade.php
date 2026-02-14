@extends('layouts.store.app', ['authgroup'=>'store_user'])

@section('title')
クーポン詳細
@endsection


@section('content')
<style>
.highlight-payment {
    background-color: #ffe4e1;
}
</style>

<style>
    .btn-space {
        margin-right: 10px;
    }
</style>


    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">クーポン詳細</h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">{{$coupon_data->coupon_name}}</h6>
            </div>

            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-8">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width: 30%">店舗名</th>
                                    <td>{{$coupon_data->store_name}}</td>
                                </tr>
                                <tr>
                                    <th>クーポンコード</th>
                                    <td>{{$coupon_data->coupon_code}}</td>
                                </tr>
                                <tr>
                                    <th>定価</th>
                                    <td>{{$coupon_data->price}}円</td>
                                </tr>
                                <tr>
                                    <th>店舗支払金額</th>
                                    <td>{{ round($coupon_data->store_pay_price) }}円</td>
                                </tr>
                                <tr>
                                    <th>サービス料(手数料)</th>
                                    <td>{{$coupon_data->service_price}}円</td>
                                </tr>
                                <tr>
                                    <th>掲載金額</th>
                                    <td>{{ round($coupon_data->store_pay_price) + $coupon_data->service_price}}円</td>
                                </tr>
                                <tr>
                                    <th>コース時間(分)</th>
                                    <td>{{$coupon_data->cource_time}}分</td>
                                </tr>
                                <tr>
                                    <th>コース開始時間</th>
                                    <td>{{$coupon_data->cource_start}}</td>
                                </tr>
                                <tr>
                                    <th>発行開始時間</th>
                                    <td>{{$coupon_data->expire_start_date}}</td>
                                </tr>
                                <tr>
                                    <th>発行終了時間</th>
                                    <td>{{$coupon_data->expire_end_date}}</td>
                                </tr>
                                <tr>
                                    <th>画像</th>
                                    <td>
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
                                    </td>
                                </tr>
                                <tr>
                                    <th>ステータス</th>
                                    <td>{{ couponStatus($coupon_data) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="text-left d-flex">

                        @if ($coupon_data->expire_start_date > date('Y-m-d H:i:s'))
                            <a href="/store/coupon/edit?ci={{ $coupon_data->id }}" class="btn btn-success btn-space">
                                編集
                            </a>
                        @endif
                        <form action="/store/coupon/create" method="POST" class="btn-space">
                            @csrf
                            <input type="hidden" name="re_ci" value="{{ $coupon_data->id }}">
                            <button type="submit" class="btn btn-primary">
                                このクーポンを再利用する
                            </button>
                        </form>

                        <form action="{{ route('store.coupon.delete') }}" method="POST" class="btn-space">
                            @csrf
                            <div>
                                <input type="hidden" id="p_type" name="p_type" value="edit">
                                <input type="hidden" id="ci" name="ci" value="{{$coupon_data->id}}">
                                <button type="submit" class="btn btn-danger">
                                    削除
                                </button>
                            </div>
                        </form>

                        <a href="/store/coupon" class="btn btn-secondary btn-space">
                        戻る
                    </a>

                </div>

            </div>
        </div>

    </div>
@endsection
