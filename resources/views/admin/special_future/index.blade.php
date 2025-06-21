@extends('layouts.admin.app', ['authgroup'=>'admin_user'])

@section('title')
特集一覧
@endsection


@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">特集一覧</h1>
        </div>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>特集名</th>
                                <th>画像</th>
                                <th>内容</th>
                                <th>期間開始</th>
                                <th>期間終了</th>
                                <th>編集</th>
                                <th>削除</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($special_futures as $special_future)
                            <tr>
                                <td>{{$special_future->name}}</td>
                                <td>
                                    @if($special_future->image)
                                        <img width="50" height="50" src="{{ asset('/assets/images/'. $special_future->image) }}" >
                                    @endif
                                </td>
                                <td>
                                <?php $lines = strpos($special_future->detail, "\n");
                                if ($lines !== false) {
                                    echo substr($special_future->detail, 0, $lines);
                                } else {
                                    echo $special_future->detail;
                                }
                                ?>
                                </td>
                                <td>{{$special_future->start_date}}</td>
                                <td>{{$special_future->end_date}}</td>
                                <td><a class="form-control btn btn-success btn-block" href="special_future/edit?id={{$special_future->id}}">編集</a></td>
                                <td>
                                    <form method="POST" action="{{ route('admin.special_future.delete') }}">
                                        @csrf
                                        <div>
                                            <input type="hidden" id="p_type" name="p_type" value="edit">
                                            <input type="hidden" id="id" name="id" value="{{$special_future->id}}">
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
    </div>
@endsection
