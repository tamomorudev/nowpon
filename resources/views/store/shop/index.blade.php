@extends('layouts.store.app', ['authgroup'=>'store_user'])

@section('title')
店舗作成
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
                                <th>メールアドレス</th>
                                <th>電話番号</th>
                                <th>ジャンル</th>
                                <th>最寄り駅</th>
                                <th>詳細</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stores as $store)
                            <tr>
                                <td>{{$store->store_name}}</td>
                                <td>{{$store->email}}</td>
                                <td>{{$store->phone_number}}</td>
                                <td>{{ config('commons.genre')[$store->genre] }}</td>
                                <td>
                                    {{ $store->line }} {{ $store->station }}
                                    @if($store->station_2)
                                        <br>{{ $store->line_2 }} {{ $store->station_2 }}
                                    @endif
                                </td>
                                <td><a class="btn btn-success btn-sm w-100 text-nowrap" href="shop/detail?si={{$store->id}}">詳細</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <div>
                {{ $stores->links('pagination::bootstrap-4') }}
            </div>
            <div class="text-muted">
                全 {{ $stores->total() }} 件中 
                {{ $stores->firstItem() }} - {{ $stores->lastItem() }} 件を表示
            </div>
        </div>
    </div>
@endsection
