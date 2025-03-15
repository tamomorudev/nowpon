@extends('layouts.admin.app', ['authgroup'=>'admin_user'])

@section('title')
画像マスタ
@endsection

@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">画像マスタ</h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>店舗名</th>
                                <th>企業ID</th>
                                <th>画像タイプ</th>
                                <th>画像</th>
                                <th>削除</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stores as $store)
                            <tr>
                                <td>{{$store->store_name}}</td>
                                <td>{{$store->company_id}}</td>
                                <td>店舗</td>
                                <td>
                                    @if($store->image)
                                        <img width="50" height="50" src="{{ asset('/assets/images/'. $store->image) }}" >
                                    @endif
                                </td>
                                <td>削除</td>
                            </tr>
                            @endforeach
                            @foreach($coupons as $coupon)
                            <tr>
                                <td>{{$coupon->coupon_name}}</td>
                                <td>{{$store->company_id}}</td>
                                <td>クーポン</td>
                                <td>
                                    @if($store->image)
                                        <img width="50" height="50" src="{{ asset('/assets/images/'. $coupon->img_url) }}" >
                                    @endif
                                </td>
                                <td>削除</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
