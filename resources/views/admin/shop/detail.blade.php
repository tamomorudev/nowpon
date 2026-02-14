@extends('layouts.admin.app', ['authgroup'=>'admin_user'])

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
            <h1 class="h3 mb-0 text-gray-800">店舗詳細</h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">{{$store_data->store_name}}</h6>
            </div>

            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-8">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width: 30%">メールアドレス</th>
                                    <td>{{$store_data->email}}</td>
                                </tr>
                                <tr>
                                    <th>郵便番号</th>
                                    <td>{{$store_data->postal_code}}</td>
                                </tr>
                                <tr>
                                    <th>都道府県</th>
                                    <td>{{config('commons.prefectures')[$store_data->address1]}}</td>
                                </tr>
                                <tr>
                                    <th>市区町村</th>
                                    <td>{{$store_data->address2}}</td>
                                </tr>
                                <tr>
                                    <th>住所</th>
                                    <td>{{$store_data->address3}}</td>
                                </tr>
                                <tr>
                                    <th>電話番号</th>
                                    <td>{{$store_data->phone_number}}</td>
                                </tr>
                                <tr>
                                    <th>URL</th>
                                    <td>{{$store_data->url}}分</td>
                                </tr>
                                <tr>
                                    <th>ジャンル</th>
                                    <td>{{ config('commons.genre')[$store_data->genre] }}</td>
                                </tr>
                                <tr>
                                    <th>最寄り駅1</th>
                                    <td>{{$store_data->line}} {{$store_data->station}} {{config('commons.transportation')[$store_data->transportation]}} {{$store_data->time}}分</td>
                                </tr>
                                <tr>
                                    <th>最寄り駅2</th>
                                    <td>
                                        @if($store_data->station_2)
                                            {{$store_data->line_2}} {{$store_data->station_2}} {{config('commons.transportation')[$store_data->transportation_2]}} {{$store_data->time_2}}分
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>画像</th>
                                    <td>
                                        @if($store_data->image)
                                            <div class="col-sm-10 mb-3 mb-sm-0">
                                                <img width="50" height="50" src="{{ asset('/assets/images/'. $store_data->image) }}" >
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="text-left d-flex">
                    <a href="/admin/shop" class="btn btn-secondary btn-space">
                    戻る
                    </a>
                </div>

            </div>
        </div>

    </div>
@endsection
