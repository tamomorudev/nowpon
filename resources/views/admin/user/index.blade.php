@extends('layouts.admin.app', ['authgroup'=>'admin_user'])

@section('title')
ユーザー一覧
@endsection

@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">ユーザー一覧</h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>ユーザー名</th>
                                <th>メールアドレス</th>
                                <th>郵便番号</th>
                                <th>編集</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user_data)
                            <tr>
                                <td>{{$user_data->id}}</td>
                                <td>{{$user_data->name}}</td>
                                <td>{{$user_data->email}}</td>
                                <td>{{$user_data->postal_code}}</td>
                                <td><a class="btn btn-success btn-sm w-100 text-nowrap" href="user/detail?id={{$user_data->id}}">詳細</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <div>
                {{ $users->links('pagination::bootstrap-4') }}
            </div>
            <div class="text-muted">
                全 {{ $users->total() }} 件中 
                {{ $users->firstItem() }} - {{ $users->lastItem() }} 件を表示
            </div>
        </div>
    </div>
@endsection
