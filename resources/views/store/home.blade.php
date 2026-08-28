@extends('layouts.store.app', ['authgroup'=>'store_user'])

@section('title')
ナウポンストア管理
@endsection

@section('content')

    <div class="container-fluid dashboard-wrap">

        <!-- ヘッダー：タイトル＋期間切り替え -->
        <div class="dashboard-header d-flex align-items-center justify-content-between mb-4">
            <h1 class="dashboard-title">店舗管理ダッシュボード</h1>

            <div class="dashboard-header-actions d-flex align-items-center">
                <div class="btn-group period-switch mr-3" role="group">
                    <button type="button" class="btn btn-period active">今日</button>
                    <button type="button" class="btn btn-period">7日間</button>
                    <button type="button" class="btn btn-period">30日間</button>
                    <button type="button" class="btn btn-period">
                        期間指定 <i class="fas fa-calendar-alt ml-1"></i>
                    </button>
                </div>
                <button type="button" class="btn btn-export">
                    <i class="fas fa-download mr-1"></i> CSVエクスポート
                </button>
            </div>
        </div>

        <!-- KPIカード -->
        <div class="row mb-4">

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="kpi-card">
                    <div class="kpi-icon kpi-icon--blue">
                        <i class="fas fa-yen-sign"></i>
                    </div>
                    <div class="kpi-body">
                        <div class="kpi-label">本日の売上</div>
                        <div class="kpi-value">¥42,680</div>
                        <div class="kpi-sub kpi-sub--up">前日比 +12.4%</div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="kpi-card">
                    <div class="kpi-icon kpi-icon--green">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="kpi-body">
                        <div class="kpi-label">累計売上</div>
                        <div class="kpi-value">¥1,248,500</div>
                        <div class="kpi-sub">今月 ¥368,200</div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="kpi-card">
                    <div class="kpi-icon kpi-icon--teal">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div class="kpi-body">
                        <div class="kpi-label">本日の販売数</div>
                        <div class="kpi-value">18枚</div>
                        <div class="kpi-sub">利用済み 12枚</div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="kpi-card">
                    <div class="kpi-icon kpi-icon--orange">
                        <i class="fas fa-percentage"></i>
                    </div>
                    <div class="kpi-body">
                        <div class="kpi-label">消化率</div>
                        <div class="kpi-value">66.7%</div>
                        <div class="kpi-sub">未利用 6枚</div>
                    </div>
                </div>
            </div>

        </div>

        <!-- グラフ＋在庫アラート -->
        <div class="row mb-4">

            <div class="col-xl-8 col-lg-7 mb-4">
                <div class="dashboard-panel h-100">
                    <div class="dashboard-panel-header">
                        売上・利用数の推移
                    </div>
                    <div class="dashboard-panel-body">
                        <canvas id="salesTrendChart" height="110"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-5 mb-4">
                <div class="dashboard-panel h-100">
                    <div class="dashboard-panel-header">
                        アラート
                    </div>
                    <div class="dashboard-panel-body p-0">
                        <ul class="alert-list list-unstyled mb-0">
                            <li class="alert-item">
                                <span>トリミング予約</span>
                                <span class="alert-badge alert-badge--danger">残り 3枚</span>
                            </li>
                            <li class="alert-item">
                                <span>ゴルフレッスン</span>
                                <span class="alert-badge alert-badge--warning">残り 8枚</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>

        <!-- クーポン別実績テーブル -->
        <div class="dashboard-panel mb-4">
            <div class="dashboard-panel-header d-flex align-items-center justify-content-between">
                <span>クーポン別実績</span>
                <div class="d-flex align-items-center">
                    <div class="coupon-search mr-3">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="クーポン名を検索">
                    </div>
                    <a href="#" class="dashboard-link">すべて表示 <i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
            <div class="dashboard-panel-body p-0">
                <div class="table-responsive">
                    <table class="table dashboard-table mb-0">
                        <thead>
                            <tr>
                                <th>クーポン名</th>
                                <th>販売数</th>
                                <th>売上</th>
                                <th>利用数</th>
                                <th>消化率</th>
                                <th>発行数</th>
                                <th>残り枚数</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>トリミング予約</td>
                                <td>12枚</td>
                                <td>¥96,324</td>
                                <td>8枚</td>
                                <td>66.7%</td>
                                <td>20枚</td>
                                <td>8枚</td>
                            </tr>
                            <tr>
                                <td>ゴルフレッスン</td>
                                <td>8枚</td>
                                <td>¥71,760</td>
                                <td>6枚</td>
                                <td>75.0%</td>
                                <td>15枚</td>
                                <td>7枚</td>
                            </tr>
                            <tr>
                                <td>激安マッサージ</td>
                                <td>5枚</td>
                                <td>¥575</td>
                                <td>4枚</td>
                                <td>80.0%</td>
                                <td>30枚</td>
                                <td>25枚</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('salesTrendChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['7/20', '7/21', '7/22', '7/23', '7/24', '7/25', '7/26'],
            datasets: [
                {
                    label: '売上',
                    data: [32000, 28000, 45000, 38000, 52000, 68000, 42680],
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78,115,223,0.05)',
                    yAxisID: 'y',
                    tension: 0.3,
                    pointRadius: 3,
                },
                {
                    label: '利用数',
                    data: [8, 7, 10, 9, 12, 18, 12],
                    borderColor: '#1cc88a',
                    backgroundColor: 'rgba(28,200,138,0.05)',
                    yAxisID: 'y1',
                    tension: 0.3,
                    pointRadius: 3,
                }
            ]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    position: 'left',
                    title: { display: true, text: '円' },
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString();
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    position: 'right',
                    title: { display: true, text: '枚' },
                    grid: { drawOnChartArea: false },
                }
            }
        }
    });
});
</script>
@endpush