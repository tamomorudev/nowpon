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
            </div>
        </div>
    </div>
</div>
@endsection
