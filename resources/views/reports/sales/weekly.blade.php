@extends('layouts.nexora')

@section('title', 'Weekly Sales Report')

@section('content')
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
                                <rect x="8" y="15" width="2" height="2"/>
                            </svg>
                            Weekly Sales Report - Week of {{ $selectedWeek->format('M j, Y') }}
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
                                <label class="form-label">Select Week Starting</label>
                                <input type="date" class="form-control" name="week" value="{{ $selectedWeek->format('Y-m-d') }}" onchange="this.form.submit()">
                            </div>
                            <div class="col-md-8 d-flex align-items-end">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('reports.sales.weekly', ['week' => $selectedWeek->copy()->subWeek()->format('Y-m-d')]) }}" class="btn btn-outline-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <polyline points="15,6 9,12 15,18"/>
                                        </svg>
                                        Previous Week
                                    </a>
                                    <a href="{{ route('reports.sales.weekly', ['week' => now()->startOfWeek()->format('Y-m-d')]) }}" class="btn btn-success">This Week</a>
                                    <a href="{{ route('reports.sales.weekly', ['week' => $selectedWeek->copy()->addWeek()->format('Y-m-d')]) }}" class="btn btn-outline-success">
                                        Next Week
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

            <!-- Week Period Info -->
            <div class="col-12">
                <div class="alert alert-info">
                    <h4 class="alert-title">Week Period</h4>
                    <div class="text-muted">
                        <strong>{{ $selectedWeek->format('M j, Y') }}</strong> to <strong>{{ $selectedWeek->copy()->endOfWeek()->format('M j, Y') }}</strong>
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
                            <div class="h1 m-0 text-warning">{{ $salesData['total_items_sold'] }}</div>
                            <div class="text-muted mb-3">Items Sold</div>
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
                        <h3 class="card-title">Daily Sales for This Week</h3>
                    </div>
                    <div class="card-body">
                        <div id="daily-chart" style="height: 350px;"></div>
                    </div>
                </div>
            </div>

            <!-- Daily Sales Table -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daily Breakdown</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Day</th>
                                        <th>Date</th>
                                        <th class="text-center">Orders</th>
                                        <th class="text-end">Total Sales</th>
                                        <th class="text-end">Avg per Order</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for($i = 0; $i < 7; $i++)
                                        @php
                                            $currentDate = $selectedWeek->copy()->addDays($i);
                                            $dateKey = $currentDate->format('Y-m-d');
                                            $dayData = $dailyData->get($dateKey);
                                            $sales = $dayData ? $dayData->total_sales : 0;
                                            $orders = $dayData ? $dayData->order_count : 0;
                                            $avg = $orders > 0 ? $sales / $orders : 0;
                                            $isToday = $currentDate->isToday();
                                            $isPast = $currentDate->isPast() && !$isToday;
                                        @endphp
                                        <tr class="{{ $isToday ? 'table-info' : ($isPast && $orders == 0 ? 'text-muted' : '') }}">
                                            <td>
                                                <strong>{{ $currentDate->format('l') }}</strong>
                                                @if($isToday)
                                                    <span class="badge bg-primary ms-1">Today</span>
                                                @endif
                                            </td>
                                            <td>{{ $currentDate->format('M j, Y') }}</td>
                                            <td class="text-center">
                                                @if($orders > 0)
                                                    <span class="badge bg-primary">{{ $orders }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if($sales > 0)
                                                    <strong>LKR {{ number_format($sales) }}</strong>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if($avg > 0)
                                                    LKR {{ number_format($avg) }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($isToday)
                                                    <span class="badge bg-info">Today</span>
                                                @elseif($sales > 0)
                                                    <span class="badge bg-success">Active</span>
                                                @elseif($isPast)
                                                    <span class="badge bg-secondary">No Sales</span>
                                                @else
                                                    <span class="badge bg-warning">Future</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endfor
                                </tbody>
                                <tfoot>
                                    <tr class="table-dark">
                                        <th colspan="2"><strong>Week Total</strong></th>
                                        <th class="text-center"><strong>{{ $salesData['total_orders'] }}</strong></th>
                                        <th class="text-end"><strong>LKR {{ number_format($salesData['total_sales']) }}</strong></th>
                                        <th class="text-end"><strong>LKR {{ number_format($salesData['average_order_value']) }}</strong></th>
                                        <th class="text-center">-</th>
                                    </tr>
                                </tfoot>
                            </table>
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
    // Prepare daily data for chart
    const dailyData = @json($dailyData);
    const selectedWeek = @json($selectedWeek->format('Y-m-d'));
    const days = [];
    const sales = [];
    const orders = [];
    
    // Generate 7 days starting from selected week
    const startDate = new Date(selectedWeek);
    for (let i = 0; i < 7; i++) {
        const currentDate = new Date(startDate);
        currentDate.setDate(startDate.getDate() + i);
        const dateKey = currentDate.toISOString().split('T')[0];
        
        days.push(currentDate.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' }));
        
        const dayData = dailyData[dateKey];
        sales.push(dayData ? parseFloat(dayData.total_sales) : 0);
        orders.push(dayData ? parseInt(dayData.order_count) : 0);
    }

    // Create daily sales chart
    const options = {
        series: [{
            name: 'Sales (LKR)',
            type: 'column',
            data: sales
        }, {
            name: 'Orders',
            type: 'line',
            data: orders
        }],
        chart: {
            height: 350,
            type: 'line',
            toolbar: {
                show: true,
                tools: {
                    download: true,
                    selection: false,
                    zoom: false,
                    zoomin: false,
                    zoomout: false,
                    pan: false,
                    reset: false
                }
            }
        },
        colors: ['#28a745', '#206bc4'],
        stroke: {
            width: [0, 2]
        },
        dataLabels: {
            enabled: true,
            enabledOnSeries: [0]
        },
        fill: {
            opacity: [0.85, 1]
        },
        xaxis: {
            categories: days,
            title: {
                text: 'Day of Week'
            }
        },
        yaxis: [{
            title: {
                text: 'Sales (LKR)'
            },
            labels: {
                formatter: function (value) {
                    return 'LKR ' + Math.round(value).toLocaleString();
                }
            }
        }, {
            opposite: true,
            title: {
                text: 'Number of Orders'
            }
        }],
        tooltip: {
            shared: true,
            intersect: false,
            y: [{
                formatter: function (value) {
                    return 'LKR ' + Math.round(value).toLocaleString();
                }
            }, {
                formatter: function (value) {
                    return value + ' orders';
                }
            }]
        },
        grid: {
            borderColor: '#e9ecef',
            strokeDashArray: 4
        }
    };

    const chart = new ApexCharts(document.querySelector("#daily-chart"), options);
    chart.render();
});
</script>
@endpush