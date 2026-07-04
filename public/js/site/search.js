/*
 * 表側の詳細検索フォーム用処理。
 * 都道府県の選択に応じて路線を取得し、路線の選択に応じて駅を取得する。
 */
$(function () {
    // LaravelのCSRFトークンをAjaxリクエストに付与する。
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

    // 都道府県変更時に、選択された都道府県の路線一覧を取得する。
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

                // 検索結果ページなどで初期路線がある場合は、路線を選択して駅一覧も取得する。
                if (initialLine) {
                    $line.val(initialLine).trigger("change");
                }
            })
            .fail(function () {
                alert("路線情報の取得に失敗しました");
            });
    });

    // 路線変更時に、選択された路線の駅一覧を取得する。
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

                // 検索結果ページなどで初期駅がある場合は、駅を選択済みに戻す。
                if (initialStation) {
                    $station.val(initialStation);
                }
            })
            .fail(function () {
                alert("駅情報の取得に失敗しました");
            });
    });

    // ページ初期表示時に都道府県が選択済みなら、路線・駅の復元処理を走らせる。
    if (initialPref) {
        $pref.trigger("change");
    }
});
