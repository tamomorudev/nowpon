@extends('layouts.admin.app', ['authgroup'=>'admin_user'])

@section('title')
店舗情報
@endsection

@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">店舗一覧</h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>店舗名</th>
                                <th>企業ID</th>
                                <th>メールアドレス</th>
                                <th>電話番号</th>
                                <th>ジャンル</th>
                                <th>最寄り駅</th>
                                <th>画像</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stores as $store)
                            <tr>
                                <td>{{$store->store_name}}</td>
                                <td>{{$store->company_id}}</td>
                                <td>{{$store->email}}</td>
                                <td>{{$store->phone_number}}</td>
                                <td>{{ config('commons.genre')[$store->genre] }}</td>
                                <td>
                                    {{ $store->line }} {{ $store->station }}
                                    @if($store->station_2)
                                        <br>{{ $store->line_2 }} {{ $store->station_2 }}
                                    @endif
                                </td>
                                <td>
                                    @if($store->image)
                                        <img width="50" height="50" src="{{ asset('/assets/images/'. $store->image) }}" >
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
