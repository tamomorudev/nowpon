@extends('layouts.admin.app', ['authgroup'=>'admin_user'])

@section('title')
店舗画像一覧
@endsection

@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">店舗画像一覧</h1>
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
                                <td>
                                    <form method="POST" action="{{ route('admin.master.store_images_delete') }}">
                                        @csrf
                                        <div>
                                            <input type="hidden" id="d_type" name="d_type" value="store">
                                            <input type="hidden" id="id" name="id" value="{{$store->id}}">
                                            <input type="submit" class="form-control btn btn-danger btn-block" id="" value='削除'>
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
