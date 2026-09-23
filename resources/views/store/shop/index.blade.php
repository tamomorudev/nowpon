@extends('layouts.store.app', ['authgroup'=>'store_user'])

@section('title')
店舗一覧
@endsection

@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">店舗一覧</h1>
        </div>

        @if (session('shop_success'))
            <div class="alert alert-success" role="status">{{ session('shop_success') }}</div>
        @endif
        @if (session('shop_error'))
            <div class="alert alert-danger" role="alert">{{ session('shop_error') }}</div>
        @endif
        @if ($errors->has('si'))
            <div class="alert alert-danger" role="alert">削除対象の店舗を確認してください。</div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>画像</th>
                                <th>店舗名</th>
                                <th>メールアドレス</th>
                                <th>電話番号</th>
                                <th>ジャンル</th>
                                <th>最寄り駅</th>
                                <th style="width: 1%; white-space: nowrap;">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stores as $store)
                            <tr>
                                <td style="width:96px;text-align:center;">
                                    @if($store->image)
                                        <img
                                            src="{{ asset('/assets/images/'. $store->image) }}"
                                            alt="{{ $store->store_name }}"
                                            width="80"
                                            height="80"
                                            style="object-fit:cover;border-radius:4px;border:1px solid #ddd;"
                                        >
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
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
                                <td>
                                    <div class="d-flex flex-column" style="gap: 8px; min-width: 56px;">
                                        <a class="btn btn-success btn-sm w-100 text-nowrap" href="{{ route('store.shop.detail', ['si' => $store->id]) }}" aria-label="{{ $store->store_name }}の詳細">詳細</a>
                                        <form method="POST" action="{{ route('store.shop.delete') }}" class="js-shop-delete m-0" data-store-name="{{ $store->store_name }}">
                                            @csrf
                                            <input type="hidden" name="si" value="{{ $store->id }}">
                                            <button type="submit" class="btn btn-danger btn-sm w-100 text-nowrap" aria-label="{{ $store->store_name }}を削除">削除</button>
                                        </form>
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
                {{ $stores->links('pagination::bootstrap-4') }}
            </div>
            <div class="text-muted">
                全 {{ $stores->total() }} 件中 
                {{ $stores->firstItem() }} - {{ $stores->lastItem() }} 件を表示
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
document.querySelectorAll('.js-shop-delete').forEach(function (form) {
    form.addEventListener('submit', function (event) {
        const message = '「' + form.dataset.storeName + '」を削除しますか？\nこの操作は元に戻せません。\nクーポン・購入履歴などが紐づく店舗は削除できません。';
        if (!window.confirm(message)) event.preventDefault();
    });
});
</script>
@endpush
