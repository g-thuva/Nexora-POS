@extends('layouts.nexora')

@section('title', 'Daily Sales Report')

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
                    </svg>
                    Daily Sales Report
                </h2>
                <p class="text-muted">{{ $selectedDate->format('F j, Y') }} - View detailed daily sales performance</p>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('reports.sales.index') }}" class="btn btn-outline-secondary d-none d-sm-inline-block">
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
            <!-- Date Selection Card -->
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Select Date</label>
                                <input type="date" class="form-control" name="date" value="{{ $selectedDate->format('Y-m-d') }}" onchange="this.form.submit()">
                            </div>
                            <div class="col-md-8 d-flex align-items-end">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('reports.sales.daily', ['date' => $selectedDate->copy()->subDay()->format('Y-m-d')]) }}" class="btn btn-outline-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <polyline points="15,6 9,12 15,18"/>
                                        </svg>
                                        Previous Day
                                    </a>
                                    <a href="{{ route('reports.sales.daily', ['date' => now()->format('Y-m-d')]) }}" class="btn btn-primary">Today</a>
                                    <a href="{{ route('reports.sales.daily', ['date' => $selectedDate->copy()->addDay()->format('Y-m-d')]) }}" class="btn btn-outline-primary" {{ $selectedDate->isToday() ? 'disabled' : '' }}>
                                        Next Day
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
                            <div class="h1 m-0 text-info">LKR {{ number_format($salesData['average_order_value']) }}</div>
                            <div class="text-muted mb-3">Avg Order Value</div>
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

            <!-- Hourly Sales Chart -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Hourly Sales Breakdown</h3>
                    </div>
                    <div class="card-body">
                        <div id="hourly-chart" style="height: 300px;"></div>
                    </div>
                </div>
            </div>

            <!-- Hourly Sales Table -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Hourly Sales Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Hour</th>
                                        <th class="text-center">Orders</th>
                                        <th class="text-end">Total Sales</th>
                                        <th class="text-end">Avg per Order</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for($hour = 0; $hour < 24; $hour++)
                                        @php
                                            $hourData = $hourlyData->get($hour);
                                            $sales = $hourData ? $hourData->total_sales : 0;
                                            $orders = $hourData ? $hourData->order_count : 0;
                                            $avg = $orders > 0 ? $sales / $orders : 0;
                                        @endphp
                                        <tr class="{{ $hourData ? '' : 'text-muted' }}">
                                            <td>{{ sprintf('%02d:00 - %02d:59', $hour, $hour) }}</td>
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
                                        </tr>
                                    @endfor
                                </tbody>
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
    // Prepare hourly data for chart
    const hourlyData = @json($hourlyData);
    const hours = [];
    const sales = [];
    
    for (let hour = 0; hour < 24; hour++) {
        hours.push(hour + ':00');
        const hourData = hourlyData[hour];
        sales.push(hourData ? parseFloat(hourData.total_sales) : 0);
    }

    // Create hourly sales chart
    const options = {
        series: [{
            name: 'Sales (LKR)',
            data: sales
        }],
        chart: {
            type: 'area',
            height: 300,
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
        colors: ['#206bc4'],
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
                opacityTo: 0.1,
                stops: [0, 90, 100]
            }
        },
        xaxis: {
            categories: hours,
            title: {
                text: 'Hour of Day'
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
        },
        grid: {
            borderColor: '#e9ecef',
            strokeDashArray: 4,
            yaxis: {
                lines: {
                    show: true
                }
            }
        }
    };

    const chart = new ApexCharts(document.querySelector("#hourly-chart"), options);
    chart.render();
});
</script>
@endpush