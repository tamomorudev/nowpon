@extends('layouts.store.app', ['authgroup'=>'store_user'])

@section('title')
クーポン分類一覧
@endsection

@section('content')
<style>
.highlight-payment {
    background-color: #ffe4e1;
}
</style>

    <div class="container-fluid">

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">クーポン分類一覧</h1>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#categoryAddModal">
                <i class="fas fa-plus"></i> 分類を追加
            </button>
        </div>

        {{-- メッセージ --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>クーポン分類</th>
                                <th style="width:180px;">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($coupon_categories as $coupon_category)
                            <tr>
                                <td>{{ $coupon_category->category_name }}</td>
                                <td>
                                    <button type="button"
                                        class="btn btn-primary btn-sm text-nowrap btn-edit-category"
                                        data-id="{{ $coupon_category->id }}"
                                        data-name="{{ $coupon_category->category_name }}">
                                        編集
                                    </button>

                                    <button type="button"
                                        class="btn btn-danger btn-sm text-nowrap btn-delete-category"
                                        data-id="{{ $coupon_category->id }}"
                                        data-name="{{ $coupon_category->category_name }}">
                                        削除
                                    </button>
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
                {{ $coupon_categories->links('pagination::bootstrap-4') }}
            </div>
            <div class="text-muted">
                全 {{ $coupon_categories->total() }} 件中
                {{ $coupon_categories->firstItem() }} - {{ $coupon_categories->lastItem() }} 件を表示
            </div>
        </div>
    </div>

    <div class="modal fade" id="categoryAddModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('store.coupon.category.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">分類を追加</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="add_category_name">分類名</label>
                            <input type="text"
                                   class="form-control @error('category_name') is-invalid @enderror"
                                   id="add_category_name"
                                   name="category_name"
                                   maxlength="255"
                                   value="{{ old('category_name') }}"
                                   required>
                            @error('category_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
                        <button type="submit" class="btn btn-primary">保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="categoryEditModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ url('/store/coupon/category/update') }}" method="POST" id="categoryEditForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">分類を編集</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_category_name">分類名</label>
                            <input type="text"
                                class="form-control"
                                id="edit_category_name"
                                name="category_name"
                                maxlength="255"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
                        <button type="submit" class="btn btn-primary">保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="categoryDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ url('/store/coupon/category/delete') }}" method="POST" id="categoryDeleteForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">分類の削除</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">
                            「<span id="delete_category_name" class="font-weight-bold"></span>」を削除してよろしいですか?<br>
                            <span class="text-danger">この操作は取り消せません。<br>既に分類が設定されているクーポンの設定は解除されます。</span>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
                        <button type="submit" class="btn btn-danger">削除する</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(function () {
            const editForm = $('#categoryEditForm');
            const baseUrl = "{{ url('/store/coupon/category/update') }}";

            $(document).on('click', '.btn-edit-category', function () {
                const id = $(this).data('id');
                const name = $(this).data('name');

                editForm.attr('action', baseUrl + '/' + id);
                $('#edit_category_name').val(name);

                $('#categoryEditModal').modal('show');
            });

            const deleteForm = $('#categoryDeleteForm');
            const deleteBaseUrl = "{{ url('/store/coupon/category/delete') }}";

            $(document).on('click', '.btn-delete-category', function () {
                const id = $(this).data('id');
                const name = $(this).data('name');

                deleteForm.attr('action', deleteBaseUrl + '/' + id);
                $('#delete_category_name').text(name);
                $('#categoryDeleteModal').modal('show');
            });
        });
    </script>
@endsection