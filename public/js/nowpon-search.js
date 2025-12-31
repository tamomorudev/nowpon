$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });

    const $pref    = $("#search_prefecture");
    const $line    = $("#search_station_line");
    const $station = $("#search_station");

    // サーバから渡された初期値
    const initialPref    = $pref.val(); // Blade で selected 済み
    const initialLine    = $line.data("initial-line") || "";
    const initialStation = $station.data("initial-station") || "";

    // 都道府県変更 → 路線一覧取得
    $pref.on("change", function () {
        const pval = $(this).val();

        $line.empty().append('<option value="">路線を選択してください</option>');
        $station.empty().append('<option value="">路線を選択してください</option>');

        if (!pval) return;

        $.ajax({
            type: "POST",
            url: "/check_station",
            data: { prefecture: pval, type: 1 },
            dataType: "json"
        })
            .done(function (data) {
                if (!data.lines) return;

                $.each(data.lines, function (idx, value) {
                    $line.append(
                        '<option value="' +
                        value.line +
                        '">' +
                        value.line +
                        "</option>"
                    );
                });

                // ★ 初期路線があればここで選択
                if (initialLine) {
                    $line.val(initialLine).trigger("change");
                }
            })
            .fail(function () {
                alert("路線情報の取得に失敗しました");
            });
    });

    // 路線変更 → 駅一覧取得
    $line.on("change", function () {
        const lval = $(this).val();

        $station.empty().append('<option value="">駅を選択してください</option>');
        if (!lval) return;

        $.ajax({
            type: "POST",
            url: "/check_station",
            data: { line: lval, type: 2 },
            dataType: "json"
        })
            .done(function (data) {
                if (!data.stations) return;

                $.each(data.stations, function (idx, value) {
                    $station.append(
                        '<option value="' +
                        value.name +
                        '">' +
                        value.name +
                        "</option>"
                    );
                });

                // ★ 初期駅があればここで選択
                if (initialStation) {
                    $station.val(initialStation);
                }
            })
            .fail(function () {
                alert("駅情報の取得に失敗しました");
            });
    });

    // ★ ページ初期表示時：prefecture が入っていたら Ajax を走らせる
    if (initialPref) {
        // ここで change を叩くことで、
        // 1. 路線一覧 Ajax
        // 2. その done コールバックで initialLine を選択 → change → 駅一覧 Ajax
        // という流れが一気に走る
        $pref.trigger("change");
    }
});
