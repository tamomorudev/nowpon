@extends('layouts.store.app', ['authgroup'=>'store_user'])

@section('title')
店舗登録
@endsection

@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">店舗登録</h1>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>入力値に誤りがあります。</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <form class="user" method="POST" action="{{ route('store.shop.create') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="store_name" class="col-md-4 col-form-label text-md-end">店舗名<span class="text-danger">*</span></label>
                <div class="col-sm-10 mb-3 mb-sm-0">
                    <input type="text" class="form-control @error('store_name') is-invalid @enderror" name="store_name"
                           value="{{ old('store_name') }}" placeholder="">
                    @error('store_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-md-4 col-form-label text-md-end">メールアドレス<span class="text-danger">*</span></label>
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" class="form-control @error('email') is-invalid @enderror" name="email"
                           value="{{ old('email') }}" placeholder="">
                    @error('store_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="postal_code" class="col-md-4 col-form-label text-md-end">郵便番号(ハイフンなし)<span class="text-danger">*</span></label>
                <div class="col-sm-3 mb-3 mb-sm-0">
                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror" name="postal_code"
                           value="{{ old('postal_code') }}" placeholder="">
                    @error('postal_code')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="address1" class="col-md-4 col-form-label text-md-end">都道府県<span class="text-danger">*</span></label>
                <div class="col-sm-3 mb-3 mb-sm-0">
                    <select name="address1" id="address1 @error('address1') is-invalid @enderror" class="form-control">
                        <option value="">選択してください</option>
                        @foreach(config('commons.prefectures') as $key => $prefecture)
                            <option value="{{ $key }}"
                                {{ old('address1', $defaultPrefecture ?? '') == $key ? 'selected' : '' }}>
                                {{ $prefecture }}
                            </option>
                        @endforeach
                    </select>
                    @error('address1')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="address2" class="col-md-4 col-form-label text-md-end">市区町村<span class="text-danger">*</span></label>
                <div class="col-sm-3 mb-3 mb-sm-0">
                    <input type="text" class="form-control @error('address2') is-invalid @enderror" name="address2"
                           value="{{ old('address2') }}" placeholder="">
                    @error('address2')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="address3" class="col-md-4 col-form-label text-md-end">住所<span class="text-danger">*</span></label>
                <div class="col-sm-10 mb-3 mb-sm-0">
                    <input type="text" class="form-control @error('address3') is-invalid @enderror" name="address3"
                           value="{{ old('address3') }}" placeholder="">
                    @error('address3')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="phone_number" class="col-md-4 col-form-label text-md-end">電話番号<span class="text-danger">*</span></label>
                <div class="col-sm-3 mb-3 mb-sm-0">
                    <input type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number"
                           value="{{ old('phone_number') }}" placeholder="">
                    @error('phone_number')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="url" class="col-md-4 col-form-label text-md-end">URL</label>
                <div class="col-sm-10 mb-3 mb-sm-0">
                    <input type="text" class="form-control" name="url"
                           value="{{ old('url') }}" placeholder="">
                </div>
            </div>
            <div class="form-group">
                <label for="genre" class="col-md-4 col-form-label text-md-end">ジャンル<span class="text-danger">*</span></label>
                <div class="col-sm-3 mb-3 mb-sm-0">
                    <select name="genre" id="genre" class="form-control @error('genre') is-invalid @enderror">
                        @foreach(config('commons.genre') as $gkey => $genre)
                            <option value="{{ $gkey }}"
                                {{ old('genre', $defaultGenre ?? '') == $gkey ? 'selected' : '' }}>
                                {{ $genre }}
                            </option>
                        @endforeach
                    </select>
                    @error('genre')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="station_line" class="col-md-4 col-form-label text-md-end">最寄り駅<span class="text-danger">*</span></label>
                <div class="row"style="margin-left:0px">
                    <div class="col-sm-3 mb-3 mb-sm-0">路線
                        <select name="station_line" id="station_line" class="form-control @error('station_line') is-invalid @enderror">
                            <option selected disabled>都道府県を選択してください</option>
                        </select>
                        @error('station_line')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-sm-3 mb-3 mb-sm-0">駅
                        <select name="station" id="station" class="form-control @error('station') is-invalid @enderror">
                            <option selected disabled>路線を選択してください</option>
                        </select>
                        @error('station')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="transportation" class="col-md-4 col-form-label text-md-end">交通手段<span class="text-danger">*</span></label>
                <div class="col-sm-3 mb-3 mb-sm-0">
                    <select name="transportation" id="transportation" class="form-control @error('transportation') is-invalid @enderror">
                        @foreach(config('commons.transportation') as $tkey => $transportation)
                            <option value="{{ $tkey }}"
                                {{ old('transportation', $defaultTransportation ?? '') == $tkey ? 'selected' : '' }}>
                                {{ $transportation }}
                            </option>
                        @endforeach
                    </select>
                    @error('transportation')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="time" class="col-md-4 col-form-label text-md-end">時間(分)<span class="text-danger">*</span></label>
                <div class="col-sm-3 mb-3 mb-sm-0">
                    <input type="number" class="form-control @error('time') is-invalid @enderror" name="time" id="time" min="1" value="{{ old('time') }}" placeholder="">
                    @error('time')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="station_line_2" class="col-md-4 col-form-label text-md-end">最寄り駅 2</label>
                <div class="row"style="margin-left:0px">
                    <div class="col-sm-3 mb-3 mb-sm-0">路線
                        <select name="station_line_2" id="station_line_2" class="form-control">
                            <option selected disabled>都道府県を選択してください</option>
                        </select>
                    </div>

                    <div class="col-sm-3 mb-3 mb-sm-0">駅
                        <select name="station_2" id="station_2" class="form-control">
                            <option selected disabled>路線を選択してください</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="transportation_2" class="col-md-4 col-form-label text-md-end">交通手段 2</label>
                <div class="col-sm-3 mb-3 mb-sm-0">
                    <select name="transportation_2" id="transportation_2" class="form-control">
                        @foreach(config('commons.transportation') as $tkey => $transportation)
                            <option value="{{ $tkey }}"
                                {{ old('transportation_2', $defaultTransportation2 ?? '') == $tkey ? 'selected' : '' }}>
                                {{ $transportation }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="time_2" class="col-md-4 col-form-label text-md-end">時間(分) 2</label>
                <div class="col-sm-3 mb-3 mb-sm-0">
                    <input type="number" class="form-control" name="time_2" id="time_2" min="1" value="{{ old('time_2') }}" placeholder="">
                </div>
            </div>
            <div class="form-group">
                <label for="images" class="col-md-4 col-form-label text-md-end">画像</label>
                <div class="col-sm-10 mb-3 mb-sm-0">
                    <input type="file" class="form-control" name="images">
                </div>
            </div>
            <div class="form-group row"style="margin-left:10px">
                <div class="col-sm-3 mb-3 mb-sm-0">
                    <input type="submit" class="form-control btn btn-success btn-block" id="" placeholder="">
                </div>
            </div>
        </form>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        $(function() {
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $("[name='csrf-token']").attr("content") },
            })
            $("#address1").on('change', function() {
                var pval = $(this).val();
                if (pval) {
                    $.ajax({
                    type: "POST",
                    url: "check_station",
                    data: { "prefecture" : pval, "type" : 1 },
                    dataType : "json"
                    }).done(function(data) {
                        let station_linedown = $('#station_line');
                        let station_linedown_2 = $('#station_line_2');
                        station_linedown.empty();
                        station_linedown.append('<option value="">路線を選択</option>');
                        station_linedown_2.empty();
                        station_linedown_2.append('<option value="">路線を選択</option>');
                        $.each(data['lines'], function (key, value) {
                            station_linedown.append('<option value="' + value.line + '">' + value.line + '</option>');
                            station_linedown_2.append('<option value="' + value.line + '">' + value.line + '</option>');
                        });
                    }).fail(function(){
                        alert('エラーが発生しました');
                    });
                }
            })

            $("#station_line").on('change', function() {
                var lval = $(this).val();
                if (lval) {
                    $.ajax({
                    type: "POST",
                    url: "check_station",
                    data: { "line" : lval, "type" : 2 },
                    dataType : "json"
                    }).done(function(data) {
                        if (data['stations']) {
                            let station_down = $('#station');
                            station_down.empty();
                            station_down.append('<option value="">駅を選択</option>');
                            $.each(data['stations'], function (key, value) {
                                station_down.append('<option value="' + value.name + '">' + value.name + '</option>');
                            });
                        } else {
                            alert('エラーが発生しました');
                        }
                    }).fail(function(){
                        alert('エラーが発生しました');
                    });
                }
            })

            $("#station_line_2").on('change', function() {
                var lval = $(this).val();
                if (lval) {
                    $.ajax({
                    type: "POST",
                    url: "check_station",
                    data: { "line" : lval, "type" : 2 },
                    dataType : "json"
                    }).done(function(data) {
                        if (data['stations']) {
                            let station_down_2 = $('#station_2');
                            station_down_2.empty();
                            station_down_2.append('<option value="">駅を選択</option>');
                            $.each(data['stations'], function (key, value) {
                                station_down_2.append('<option value="' + value.name + '">' + value.name + '</option>');
                            });
                        } else {
                            alert('エラーが発生しました');
                        }
                    }).fail(function(){
                        alert('エラーが発生しました');
                    });
                }
            })

            var d_pval = $("#address1").val();
            if (d_pval) {
                var selectedStationLine = "{{ old('station_line') ?? '' }}";
                var selectedStationLine_2 = "{{ old('station_line_2') ?? '' }}";
                var selectedStation = "{{ old('station') ?? '' }}";
                var selectedStation_2 = "{{ old('station_2') ?? '' }}";

                $.ajax({
                type: "POST",
                url: "check_station",
                data: { "prefecture" : d_pval, "type" : 1 },
                dataType : "json"
                }).done(function(data) {
                    let station_linedown = $('#station_line');
                    let station_linedown_2 = $('#station_line_2');
                    station_linedown.empty();
                    station_linedown.append('<option value="">路線を選択</option>');
                    station_linedown_2.empty();
                    station_linedown_2.append('<option value="">路線を選択</option>');
                    $.each(data['lines'], function (key, value) {
                        let selected = (selectedStationLine === value.line) ? ' selected' : '';
                        let selected_2 = (selectedStationLine_2 === value.line) ? ' selected' : '';
                        station_linedown.append('<option value="' + value.line + '"' + selected + '>' + value.line + '</option>');
                        station_linedown_2.append('<option value="' + value.line + '"' + selected_2 + '>' + value.line + '</option>');
                    });
                }).fail(function(){
                    alert('エラーが発生しました');
                });

                if (selectedStationLine) {
                    var d_lval = selectedStationLine;
                    $.ajax({
                    type: "POST",
                    url: "check_station",
                    data: { "line" : d_lval, "type" : 2 },
                    dataType : "json"
                    }).done(function(data) {
                        if (data['stations']) {
                            let station_down = $('#station');
                            station_down.empty();
                            station_down.append('<option value="">駅を選択</option>');
                            $.each(data['stations'], function (key, value) {
                                let selected_s = (selectedStation === value.name) ? ' selected' : '';
                                station_down.append('<option value="' + value.name + '"' + selected_s + '>' + value.name + '</option>');
                            });
                        } else {
                            alert('エラーが発生しました');
                        }
                    }).fail(function(){
                        alert('エラーが発生しました');
                    });
                }
                if (selectedStationLine_2) {
                    var d_lval2 = selectedStationLine_2;
                    $.ajax({
                    type: "POST",
                    url: "check_station",
                    data: { "line" : d_lval2, "type" : 2 },
                    dataType : "json"
                    }).done(function(data) {
                        if (data['stations']) {
                            let station_down_2 = $('#station_2');
                            station_down_2.empty();
                            station_down_2.append('<option value="">駅を選択</option>');
                            $.each(data['stations'], function (key, value) {
                                let selected_s_2 = (selectedStation_2 === value.name) ? ' selected' : '';
                                station_down_2.append('<option value="' + value.name + '"' + selected_s_2 + '>' + value.name + '</option>');
                            });
                        } else {
                            alert('エラーが発生しました');
                        }
                    }).fail(function(){
                        alert('エラーが発生しました');
                    });
                }
            }
        });
    </script>
@endsection
