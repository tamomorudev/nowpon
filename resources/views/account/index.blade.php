@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">アカウント情報</div>

                <div class="card-body">
                    ユーザー名：{{ $user->name }}
                </div>
                <div class="card-body">
                    ニックネーム：{{ $user->nickname }}
                </div>
                <div class="card-body">
                    メールアドレス：{{ $user->email }}
                </div>
                <div class="card-body">
                    郵便番号：{{ $user->postal_code }}
                </div>
                <div class="card-body">
                    都道府県：{{ config('commons.prefectures')[$user->prefecture] }}
                </div>
                <div class="card-body">
                    市区町村：{{ $user->city }}
                </div>
                <div class="card-body">
                    電話番号：{{ $user->phone_number }}
                </div>
                <div class="card-body">
                    性別：{{ config('commons.sexs')[$user->sex] }}
                </div>
                <div class="card-body">
                    生年月日：{{ $user->birth_date }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
