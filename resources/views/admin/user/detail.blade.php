@extends('layouts.admin.app', ['authgroup'=>'admin_user'])

@section('title')
ユーザー詳細
@endsection

@section('content')

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">ユーザー詳細</h1>
    </div>

    <div class="card shadow mb-4 bg-white">
        <div class="card-header py-3" style="background-color: #615d5d;">
            <h6 class="m-0 font-weight-bold text-white">詳細情報</h6>
        </div>

        <div class="card-body">

            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th class="bg-light" style="width: 25%">ID</th>
                        <td>{{ $user_data->id }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">ユーザー名</th>
                        <td>{{ $user_data->name }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">ユーザー名</th>
                        <td>{{ $user_data->nickname }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">メールアドレス</th>
                        <td>{{ $user_data->email }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">郵便番号</th>
                        <td>{{ $user_data->postal_code }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">都道府県</th>
                        <td>{{ config('commons.prefectures')[$user_data->prefecture] }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">市区町村</th>
                        <td>{{ $user_data->city }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">電話番号</th>
                        <td>{{ $user_data->phone_number }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">性別</th>
                        <td>{{ config('commons.sexs')[$user_data->sex] }}</td>
                    </tr>
                     <tr>
                        <th class="bg-light">生年月日</th>
                        <td>{{ $user_data->birth_date }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="text-end mt-4">
                <a href="/admin/user" class="btn btn-secondary">戻る</a>
            </div>

        </div>
    </div>

</div>
@endsection

