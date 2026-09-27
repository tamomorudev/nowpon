@extends('layouts.store.app', ['authgroup'=>'store_user'])

@section('title')
ナウポンストア管理
@endsection

@section('content')

    <div class="container-fluid dashboard-wrap">

        <div class="dashboard-header mb-4">
            <h1 class="dashboard-title">店舗管理ダッシュボード</h1>
        </div>

        <div class="row mb-4">
            <div class="col-xl-6 col-lg-8">
                <div class="dashboard-panel h-100">
                    <div class="dashboard-panel-header">
                        発行中クーポン
                    </div>
                    <div class="dashboard-panel-body p-0">
                        <ul class="alert-list list-unstyled mb-0">
                            @foreach ($sell_coupons as $category_key => $sell_coupon)
                                <li class="alert-item">
                                    <span>{{$category_key}}</span>
                                    @if ($sell_coupon <= 5)
                                        <span class="alert-badge alert-badge--danger">残り {{$sell_coupon}}枚</span>
                                    @else
                                        <span class="alert-badge alert-badge--warning">残り {{$sell_coupon}}枚</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="kpi-card">
                    <div class="kpi-icon kpi-icon--blue">
                        <i class="fas fa-yen-sign"></i>
                    </div>
                    <div class="kpi-body">
                        <div class="kpi-label">本日の売上</div>
                        <div class="kpi-value">¥{{ number_format($todaySalesAmount) }}</div>
                        @if (is_null($salesChangeRate))
                            <div class="kpi-sub">前日実績なし</div>
                        @elseif ($salesChangeRate > 0)
                            <div class="kpi-sub kpi-sub--up">前日比 +{{ $salesChangeRate }}%</div>
                        @elseif ($salesChangeRate < 0)
                            <div class="kpi-sub kpi-sub--down">前日比 {{ $salesChangeRate }}%</div>
                        @else
                            <div class="kpi-sub">前日比 ±0%</div>
                        @endif
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
                        <div class="kpi-value">¥{{ number_format($allSalesAmount) }}</div>
                        <div class="kpi-sub">今月 ¥{{ number_format($monthSalesAmount) }}</div>
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
                        <div class="kpi-value">{{ number_format($todayCreatedCount) }}枚</div>
                        <div class="kpi-sub">利用済み {{ number_format($todayPurchasedCount) }}枚</div>
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
                        <div class="kpi-value">{{ number_format($digestionRate) }}%</div>
                        <div class="kpi-sub">未利用 {{ number_format($todayCreatedCount - $todayPurchasedCount) }}枚</div>
                    </div>
                </div>
            </div>

        </div>

        <div class="dashboard-period-tabs mb-2">
            <div class="btn-group period-switch" role="group">
                <a href="{{ url()->current() }}?period=today"
                class="btn btn-period {{ $period === 'today' ? 'active' : '' }}">今日</a>
                <a href="{{ url()->current() }}?period=7days"
                class="btn btn-period {{ $period === '7days' ? 'active' : '' }}">7日間</a>
                <a href="{{ url()->current() }}?period=30days"
                class="btn btn-period {{ $period === '30days' ? 'active' : '' }}">30日間</a>
                <button type="button"
                class="btn btn-period {{ $period === 'custom' ? 'active' : '' }}"
                data-toggle="modal" data-target="#customPeriodModal">
                    期間指定 <i class="fas fa-calendar-alt ml-1"></i>
                </button>
            </div>
        </div>

        @if ($period === 'custom')
        <div class="text-muted mb-4">
            表示期間: {{ $rangeStart->format('Y/m/d') }} 〜 {{ $rangeEnd->format('Y/m/d') }}
        </div>
        @endif

        <!-- 期間モーダル -->
        <div class="modal fade" id="customPeriodModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ url()->current() }}" method="GET">
                        <div class="modal-header">
                            <h5 class="modal-title">期間を指定</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="period" value="custom">
                            <div class="form-group">
                                <label for="custom_start">開始日</label>
                                <input type="date" class="form-control" id="custom_start" name="start"
                                    value="{{ $period === 'custom' ? $rangeStart->format('Y-m-d') : now()->subDays(6)->format('Y-m-d') }}"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="custom_end">終了日</label>
                                <input type="date" class="form-control" id="custom_end" name="end"
                                    value="{{ $period === 'custom' ? $rangeEnd->format('Y-m-d') : now()->format('Y-m-d') }}"
                                    required>
                            </div>
                            <small class="text-muted">最大1ヶ月間まで選択できます(超える場合は自動的に開始日から31日間に調整されます)。</small>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
                            <button type="submit" class="btn btn-primary">表示</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- グラフ -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="dashboard-panel h-100">
                    <div class="dashboard-panel-header">
                        売上・利用数の推移
                    </div>
                    <div class="dashboard-panel-body">
                        <canvas id="salesTrendChart" height="90"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- クーポン別実績 -->
        <div class="dashboard-panel mb-4">
            <div class="dashboard-panel-header d-flex align-items-center justify-content-between">
                <span>クーポン別実績</span>
            </div>
            <div class="dashboard-panel-body p-0">
                <div class="table-responsive">
                    <table class="table dashboard-table mb-0">
                        <thead>
                            <tr>
                                <th>分類名</th>
                                <th>発行数</th>
                                <th>利用数</th>
                                <th>売上</th>
                                <th>消化率</th>
                                <th>期限切れ</th>
                                <th>残り枚数</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($categoryStats) && $categoryStats)
                                @foreach($categoryStats as $sumcate_key => $categoryStat)
                                    <tr>
                                        <td>{{ $categoryStat['category_name'] }}</td>
                                        <td>{{ $categoryStat['total_count'] }}枚</td>
                                        <td>{{ $categoryStat['use_count'] }}枚</td>
                                        <td>¥{{ number_format($categoryStat['sales_amount']) }}</td>
                                        <td>{{ $categoryStat['digestion_rate'] }}%</td>
                                        <td>{{ $categoryStat['expired_count'] }}枚</td>
                                        <td>{{ $categoryStat['remaining_count'] }}枚</td>
                                    </tr>
                                @endforeach
                            @else
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('salesTrendChart').getContext('2d');
            const chartType = @json($chartType); //'bar' or 'line'

            new Chart(ctx, {
                type: chartType === 'bar' ? 'bar' : 'line',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [
                        {
                            type: chartType,
                            label: '売上',
                            data: {!! json_encode($chartSales) !!},
                            borderColor: '#4e73df',
                            backgroundColor: chartType === 'bar' ? 'rgba(78,115,223,0.6)' : 'rgba(78,115,223,0.05)',
                            yAxisID: 'y',
                            tension: 0.3,
                            pointRadius: 3,
                        },
                        {
                            type: chartType,
                            label: '利用数',
                            data: {!! json_encode($chartUsage) !!},
                            borderColor: '#1cc88a',
                            backgroundColor: chartType === 'bar' ? 'rgba(28,200,138,0.6)' : 'rgba(28,200,138,0.05)',
                            yAxisID: 'y1',
                            tension: 0.3,
                            pointRadius: 3,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    interaction: { mode: 'index', intersect: false },
                    plugins: { legend: { position: 'top', align: 'end' } },
                    scales: {
                        y: {
                            type: 'linear',
                            position: 'left',
                            min: 0,
                            title: { display: true, text: '円' },
                            ticks: { precision: 0, callback: function (value) { return value.toLocaleString(); } }
                        },
                        y1: {
                            type: 'linear',
                            position: 'right',
                            min: 0,
                            title: { display: true, text: '枚' },
                            ticks: {
                                precision: 0
                            },
                            grid: { drawOnChartArea: false },
                        }
                    }
                }
            });
        });
    </script>
@endsection