@extends('layouts.nexora')

@section('title', 'Monthly Sales Report')

@section('content')
<div class="page-header d-print-none">
    <div class="container-fluid">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Sales Reports
                </div>
                <h2 class="page-title">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <rect x="4" y="5" width="16" height="16" rx="2"/>
                        <line x1="16" y1="3" x2="16" y2="7"/>
                        <line x1="8" y1="3" x2="8" y2="7"/>
                        <line x1="4" y1="11" x2="20" y2="11"/>
                        <path d="M11 15h1v4h-1z"/>
                    </svg>
                    Monthly Sales Report
                </h2>
                <p class="text-muted">{{ $selectedMonth->format('F Y') }} - Comprehensive monthly performance analysis</p>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('reports.sales.index') }}" class="btn btn-outline-secondary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1"/>
                        </svg>
                        Back to Reports
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-fluid">
        <div class="row row-deck row-cards">
            <!-- Page Header -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <rect x="4" y="5" width="16" height="16" rx="2"/>
                                <line x1="16" y1="3" x2="16" y2="7"/>
                                <line x1="8" y1="3" x2="8" y2="7"/>
                                <line x1="4" y1="11" x2="20" y2="11"/>
                                <path d="M11 15h1v4h-1z"/>
                            </svg>
                            Monthly Sales Report - {{ $selectedMonth->format('F Y') }}
                        </h3>
                        <div class="card-actions">
                            <a href="{{ route('reports.sales.index') }}" class="btn btn-outline-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1"/>
                                </svg>
                                Back to Reports
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Select Month</label>
                                <input type="month" class="form-control" name="month" value="{{ $selectedMonth->format('Y-m') }}" onchange="this.form.submit()">
                            </div>
                            <div class="col-md-8 d-flex align-items-end">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('reports.sales.monthly', ['month' => $selectedMonth->copy()->subMonth()->format('Y-m')]) }}" class="btn btn-outline-info">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <polyline points="15,6 9,12 15,18"/>
                                        </svg>
                                        Previous Month
                                    </a>
                                    <a href="{{ route('reports.sales.monthly', ['month' => now()->format('Y-m')]) }}" class="btn">This Month</a>
                                    <a href="{{ route('reports.sales.monthly', ['month' => $selectedMonth->copy()->addMonth()->format('Y-m')]) }}" class="btn btn-outline-info">
                                        Next Month
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <polyline points="9,6 15,12 9,18"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sales Summary Cards -->
            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body p-4 text-center">
                        <div class="text-right">
                            <div class="h1 m-0 text-success">LKR {{ number_format($salesData['total_sales']) }}</div>
                            <div class="text-muted mb-3">Total Sales</div>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-success" style="width: 100%" role="progressbar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body p-4 text-center">
                        <div class="text-right">
                            <div class="h1 m-0 text-primary">{{ $salesData['total_orders'] }}</div>
                            <div class="text-muted mb-3">Total Orders</div>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-primary" style="width: 85%" role="progressbar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body p-4 text-center">
                        <div class="text-right">
                            <div class="h1 m-0 text-info">LKR {{ number_format($salesData['gross_profit']) }}</div>
                            <div class="text-muted mb-3">Gross Profit</div>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-info" style="width: 75%" role="progressbar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body p-4 text-center">
                        <div class="text-right">
                            <div class="h1 m-0 text-warning">LKR {{ number_format($salesData['average_order_value']) }}</div>
                            <div class="text-muted mb-3">Avg Order Value</div>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-warning" style="width: 60%" role="progressbar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daily Sales Chart -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daily Sales Trend</h3>
                    </div>
                    <div class="card-body">
                        <div id="daily-trend-chart" style="height: 350px;"></div>
                    </div>
                </div>
            </div>

            <!-- Weekly Summary -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Weekly Summary</h3>
                    </div>
                    <div class="card-body">
                        <div id="weekly-chart" style="height: 300px;"></div>
                    </div>
                </div>
            </div>

            <!-- Top Performing Days -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Top Performing Days</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th class="text-center">Orders</th>
                                        <th class="text-end">Sales</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $topDays = $dailyData->sortByDesc('total_sales')->take(10);
                                    @endphp
                                    @foreach($topDays as $day)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($day->date)->format('M j, Y') }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-primary">{{ $day->order_count }}</span>
                                        </td>
                                        <td class="text-end">
                                            <strong>LKR {{ number_format($day->total_sales) }}</strong>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Statistics -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Monthly Statistics</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="subheader">Days in Month</div>
                                    <div class="ms-auto lh-1">
                                        <div class="strong">{{ $selectedMonth->daysInMonth }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="subheader">Active Sales Days</div>
                                    <div class="ms-auto lh-1">
                                        <div class="strong">{{ safe_count($dailyData) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="subheader">Avg Daily Sales</div>
                                    <div class="ms-auto lh-1">
                                        <div class="strong">LKR {{ number_format($salesData['total_sales'] / max($selectedMonth->daysInMonth, 1)) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="subheader">Best Day Sales</div>
                                    <div class="ms-auto lh-1">
                                        <div class="strong">LKR {{ number_format($dailyData->max('total_sales') ?? 0) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page-scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dailyData = @json($dailyData);
    const weeklyData = @json($weeklyData);
    const selectedMonth = @json($selectedMonth->format('Y-m'));

    // Prepare daily trend data
    const dailyDates = [];
    const dailySales = [];
    const dailyOrders = [];

    // Get all days in the month
    const startDate = new Date(selectedMonth + '-01');
    const endDate = new Date(startDate.getFullYear(), startDate.getMonth() + 1, 0);

    for (let day = 1; day <= endDate.getDate(); day++) {
        const dateStr = selectedMonth + '-' + String(day).padStart(2, '0');
        const dayData = dailyData[dateStr];

        dailyDates.push(day);
        dailySales.push(dayData ? parseFloat(dayData.total_sales) : 0);
        dailyOrders.push(dayData ? parseInt(dayData.order_count) : 0);
    }

    // Daily trend chart
    const dailyOptions = {
        series: [{
            name: 'Sales (LKR)',
            data: dailySales
        }],
        chart: {
            type: 'area',
            height: 350,
            toolbar: {
                show: true
            }
        },
        colors: ['#17a2b8'],
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.1
            }
        },
        xaxis: {
            categories: dailyDates,
            title: {
                text: 'Day of Month'
            }
        },
        yaxis: {
            title: {
                text: 'Sales (LKR)'
            },
            labels: {
                formatter: function (value) {
                    return 'LKR ' + Math.round(value).toLocaleString();
                }
            }
        },
        tooltip: {
            y: {
                formatter: function (value) {
                    return 'LKR ' + Math.round(value).toLocaleString();
                }
            }
        }
    };

    const dailyChart = new ApexCharts(document.querySelector("#daily-trend-chart"), dailyOptions);
    dailyChart.render();

    // Weekly breakdown chart
    const weeklyDates = [];
    const weeklySales = [];

    Object.keys(weeklyData).forEach(week => {
        weeklyDates.push('Week ' + week);
        weeklySales.push(parseFloat(weeklyData[week].total_sales));
    });

    const weeklyOptions = {
        series: [{
            data: weeklySales
        }],
        chart: {
            type: 'bar',
            height: 300
        },
        colors: ['#fd7e14'],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                endingShape: 'rounded'
            }
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            categories: weeklyDates,
            title: {
                text: 'Week'
            }
        },
        yaxis: {
            title: {
                text: 'Sales (LKR)'
            },
            labels: {
                formatter: function (value) {
                    return 'LKR ' + Math.round(value).toLocaleString();
                }
            }
        },
        tooltip: {
            y: {
                formatter: function (value) {
                    return 'LKR ' + Math.round(value).toLocaleString();
                }
            }
        }
    };

    const weeklyChart = new ApexCharts(document.querySelector("#weekly-chart"), weeklyOptions);
    weeklyChart.render();
});
</script>
@endpush
