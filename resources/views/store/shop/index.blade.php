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

        {{$user->name}}の店舗

    </div>
@endsection
