@extends('layouts.admin.app', ['authgroup'=>'admin_user'])

@section('title')
    お知らせ一覧
@endsection


@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">お知らせ一覧</h1>
        </div>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>タイトル</th>
                            <th>内容</th>
                            <th>期間開始</th>
                            <th>期間終了</th>
                            <th>編集</th>
                            <th>削除</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($information_array as $information_data)
                            <tr>
                                <td>{{$information_data->name}}</td>
                                <td>
                                    {!! Str::limit($information_data->detail, 50) !!}
                                </td>
                                <td>{{$information_data->start_date}}</td>
                                <td>{{$information_data->end_date}}</td>
                                <td><a class="btn btn-success btn-sm w-100 text-nowrap" href="information/edit?id={{$information_data->id}}">編集</a></td>
                                <td>
                                    <form method="POST" action="{{ route('admin.information.delete') }}">
                                        @csrf
                                        <div>
                                            <input type="hidden" id="p_type" name="p_type" value="edit">
                                            <input type="hidden" id="id" name="id" value="{{$information_data->id}}">
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
        <div class="card-footer d-flex justify-content-between align-items-center">
            <div>
                {{ $information_array->links('pagination::bootstrap-4') }}
            </div>
            <div class="text-muted">
                全 {{ $information_array->total() }} 件中 
                {{ $information_array->firstItem() }} - {{ $information_array->lastItem() }} 件を表示
            </div>
        </div>
    </div>
@endsection
