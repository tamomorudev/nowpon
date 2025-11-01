@extends('layouts.admin.app', ['authgroup'=>'admin_user'])

@section('add_head')
    <link href="{{ asset('summernote/summernote.min.css') }}" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>
@endsection

@section('title')
特集作成
@endsection

@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">特集作成登録</h1>
        </div>

        @if($errors->any())
            <div class="d-sm-flex align-items-center justify-content-between mb-4" style="color:red">入力値に誤りがあります。</div>
        @endif

        <form class="user" method="POST" action="{{ route('admin.special_future.create') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name" class="col-md-4 col-form-label text-md-end">特集名<span class="text-danger">*</span></label>
                <div class="col-sm-10 mb-3 mb-sm-0">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="">
                </div>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="outline" class="col-md-4 col-form-label text-md-end">概要(100文字以内)<span class="text-danger">*</span></label>
                <div class="col-sm-10 mb-3 mb-sm-0">
                    <input type="text" class="form-control @error('outline') is-invalid @enderror" name="outline" value="{{ old('outline') }}" placeholder="">
                    @error('outline')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="outline" class="col-md-4 mb-sm-3 col-form-label text-md-end">[条件]</label>

                <div class="col-sm-10 mb-3 form-radio-group">
                    <label class="form-label d-block">性別</label>
                    <label><input type="radio" name="sex" value="99" {{ old('sex') == 99 ? 'checked' : '' }}> なし</label>
                    <label><input type="radio" name="sex" value="1" {{ old('sex') === 1 ? 'checked' : '' }}> 男性</label>
                    <label><input type="radio" name="sex" value="0" {{ old('sex') === 0 ? 'checked' : '' }}> 女性</label>
                    @error('sex') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="col-sm-10 mb-3">
                    <label class="form-label d-block">期間</label>
                    <div class="row">
                        <div class="col-sm-3 mb-3">
                            <input type="datetime-local" class="form-control @error('coupon_date_start') is-invalid @enderror"
                                id="coupon_date_start" name="coupon_date_start" value="{{ old('coupon_date_start') }}">
                            @error('coupon_date_start')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="col-sm-1 text-center">～</div>
                        <div class="col-sm-3">
                            <input type="datetime-local" class="form-control @error('coupon_date_end') is-invalid @enderror"
                                id="coupon_date_end" name="coupon_date_end" value="{{ old('coupon_date_end') }}">
                            @error('coupon_date_end')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-sm-3 mb-3">
                    <label class="form-label d-block">曜日</label>
                    @foreach(config('commons.day_of_the_weeks') as $dkey => $day_of_the_week)
                        <div class="form-check">
                            <input type="checkbox" name="day_of_week[]" id="day_of_week_{{ $dkey }}" value="{{ $dkey }}" class="form-check-input"
                                {{ is_array(old('day_of_week', $defaultweeks ?? [])) && in_array($dkey, old('day_of_week', $defaultweeks ?? [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="day_of_week_{{ $dkey }}">{{ $day_of_the_week }}</label>
                        </div>
                    @endforeach
                    @error('day_of_week') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="col-sm-3 mb-3">
                    <label for="discount_rate" class="form-label">割引率</label>
                    <input type="text" class="form-control @error('discount_rate') is-invalid @enderror"
                        name="discount_rate" id="discount_rate" value="{{ old('discount_rate', '') }}" min="0" step="1">
                    @error('discount_rate')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="col-sm-3 mb-3">
                    <label for="genre" class="form-label">カテゴリ（ジャンル）</label>
                    <select name="genre" id="genre" class="form-control">
                        <option value="99" {{ old('genre', $defaultGenre ?? '') == "99" ? 'selected' : '' }}>すべて</option>
                        @foreach(config('commons.genre') as $gkey => $genre)
                            <option value="{{ $gkey }}"
                                {{ old('genre', $defaultGenre ?? '') == $gkey ? 'selected' : '' }}>
                                {{ $genre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-md-4 col-form-label text-md-end">内容<span class="text-danger">*</span></label>
                <div class="col-sm-10 mb-3 mb-sm-0">
                    <textarea id="summernote" class="form-control" rows="10" cols="60" name="detail">{{ old('detail') }}</textarea>
                </div>
            </div>
            <div class="form-group">
            <label for="name" class="col-md-4 col-form-label text-md-end">期間<span class="text-danger">*</span></label>
                <div class="row"style="margin-left:0px">
                    <div class="col-sm-3 mb-3 mb-sm-0">
                        <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date') }}">
                        @error('start_date')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    ～
                    <div class="col-sm-3">
                        <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date') }}">
                        @error('end_date')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="images" class="col-md-4 col-form-label text-md-end">画像</label>
                <div class="col-sm-10 mb-3 mb-sm-0">
                    <input type="file" class="form-control" name="images">
                </div>
            </div>
            <div class="form-group row" style="margin-left:10px">
                <div class="col-sm-3 mb-3 mb-sm-0">
                    <input type="submit" class="form-control btn btn-success btn-block" id="">
                </div>
            </div>
        </form>
    </div>

@endsection

@section('add_script')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <!-- Summernote JS -->
    <script src="{{ asset('summernote/summernote.min.js') }}"></script>
    <script src="{{ asset('summernote/lang/summernote-ja-JP.min.js') }}"></script>

    <!-- 初期化 -->
    <script>
        $(function() {
            $('#summernote').summernote({
                height: 300,
                lang: 'ja-JP'
            });
        });
    </script>
@endsection
