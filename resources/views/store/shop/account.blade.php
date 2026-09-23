@extends('layouts.store.app', ['authgroup'=>'store_user'])

@section('title')
店舗ユーザー一覧
@endsection

@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">店舗ユーザー一覧</h1>
        </div>

        @if(session('account_success'))
            <div class="alert alert-success" role="status">{{ session('account_success') }}</div>
        @endif
        @if(session('account_error'))
            <div class="alert alert-danger" role="alert">{{ session('account_error') }}</div>
        @endif
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ユーザー名</th>
                                <th>登録日</th>
                                <th class="text-center text-nowrap" style="width:1%">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($store_users as $store_user)
                            <tr>
                                <td>{{$store_user->name}}</td>
                                <td>{{$store_user->created_at}}</td>
                                <td class="align-middle">
                                    <div class="d-flex flex-column" style="gap:8px;min-width:56px">
                                        <a href="{{ route('store.account.edit', ['ui' => $store_user->id]) }}" class="btn btn-success btn-sm w-100 text-nowrap">編集</a>
                                        @if((int) $store_user->id !== (int) $user->id)
                                            <form method="POST" action="{{ route('store.account.delete') }}" class="m-0" onsubmit="return confirm('この店舗ユーザーを削除しますか？削除するとログインできなくなります。');">
                                                @csrf
                                                <input type="hidden" name="ui" value="{{ $store_user->id }}">
                                                <button type="submit" class="btn btn-danger btn-sm w-100 text-nowrap">削除</button>
                                            </form>
                                        @endif
                                    </div>
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
                {{ $store_users->links('pagination::bootstrap-4') }}
            </div>
            <div class="text-muted">
                全 {{ $store_users->total() }} 件中 
                {{ $store_users->firstItem() }} - {{ $store_users->lastItem() }} 件を表示
            </div>
        </div>

    </div>
@endsection
