@extends('layouts.nexora')

@section('title', 'Yearly Sales Report')

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
                                <path d="M8 15h2v4H8z"/>
                                <path d="M14 15h2v4h-2z"/>
                            </svg>
                            Yearly Sales Report - {{ $selectedYear }}
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
                                <label class="form-label">Select Year</label>
                                <select class="form-select" name="year" onchange="this.form.submit()">
                                    @for($year = now()->year; $year >= 2020; $year--)
                                        <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-8 d-flex align-items-end">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('reports.sales.yearly', ['year' => $selectedYear - 1]) }}" class="btn btn-outline-info">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <polyline points="15,6 9,12 15,18"/>
                                        </svg>
                                        {{ $selectedYear - 1 }}
                                    </a>
                                    <a href="{{ route('reports.sales.yearly', ['year' => now()->year]) }}" class="btn btn-info">This Year</a>
                                    @if($selectedYear < now()->year)
                                    <a href="{{ route('reports.sales.yearly', ['year' => $selectedYear + 1]) }}" class="btn btn-outline-info">
                                        {{ $selectedYear + 1 }}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <polyline points="9,6 15,12 9,18"/>
                                        </svg>
                                    </a>
                                    @endif
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
                            <div class="text-muted mb-3">Annual Sales</div>
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
                            <div class="h1 m-0 text-primary">{{ number_format($salesData['total_orders']) }}</div>
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
                            <div class="text-muted mb-3">Annual Profit</div>
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
                            <div class="h1 m-0 text-warning">{{ number_format($salesData['profit_margin'], 1) }}%</div>
                            <div class="text-muted mb-3">Profit Margin</div>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-warning" style="width: {{ min($salesData['profit_margin'], 100) }}%" role="progressbar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Sales Chart -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Monthly Sales Performance</h3>
                        <div class="card-actions">
                            <div class="dropdown">
                                <a href="#" class="btn-action dropdown-toggle" data-bs-toggle="dropdown">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="12" cy="12" r="1"/>
                                        <circle cx="12" cy="19" r="1"/>
                                        <circle cx="12" cy="5" r="1"/>
                                    </svg>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="#" class="dropdown-item" onclick="changeChartType('area')">Area Chart</a>
                                    <a href="#" class="dropdown-item" onclick="changeChartType('bar')">Bar Chart</a>
                                    <a href="#" class="dropdown-item" onclick="changeChartType('line')">Line Chart</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="monthly-chart" style="height: 400px;"></div>
                    </div>
                </div>
            </div>

            <!-- Quarterly Analysis -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Quarterly Breakdown</h3>
                    </div>
                    <div class="card-body">
                        <div id="quarterly-chart" style="height: 300px;"></div>
                    </div>
                </div>
            </div>

            <!-- Top Performing Months -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Best Months</h3>
                    </div>
                    <div class="card-body">
                        <div class="space-y-2">
                            @php
                                $topMonths = collect($monthlyData)->sortByDesc('total_sales')->take(6);
                            @endphp
                            @foreach($topMonths as $month => $data)
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <span class="badge bg-success">{{ date('M', mktime(0, 0, 0, $month, 1)) }}</span>
                                </div>
                                <div class="flex-fill">
                                    <div class="font-weight-medium">{{ number_format($data['order_count']) }} orders</div>
                                    <div class="text-muted">LKR {{ number_format($data['total_sales']) }}</div>
                                </div>
                            </div>
                            @if(!$loop->last)
                            <hr class="my-2">
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Yearly Comparison -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Year-over-Year Comparison</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($previousYearData)
                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="subheader">Sales Growth</div>
                                    <div class="ms-auto lh-1">
                                        @php
                                            $growth = $previousYearData['total_sales'] > 0 
                                                ? (($salesData['total_sales'] - $previousYearData['total_sales']) / $previousYearData['total_sales']) * 100 
                                                : 0;
                                        @endphp
                                        <div class="strong {{ $growth >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $growth >= 0 ? '+' : '' }}{{ number_format($growth, 1) }}%
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="subheader">Order Growth</div>
                                    <div class="ms-auto lh-1">
                                        @php
                                            $orderGrowth = $previousYearData['total_orders'] > 0 
                                                ? (($salesData['total_orders'] - $previousYearData['total_orders']) / $previousYearData['total_orders']) * 100 
                                                : 0;
                                        @endphp
                                        <div class="strong {{ $orderGrowth >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $orderGrowth >= 0 ? '+' : '' }}{{ number_format($orderGrowth, 1) }}%
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="subheader">Previous Year Sales</div>
                                    <div class="ms-auto lh-1">
                                        <div class="strong">LKR {{ number_format($previousYearData['total_sales']) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="subheader">Previous Year Orders</div>
                                    <div class="ms-auto lh-1">
                                        <div class="strong">{{ number_format($previousYearData['total_orders']) }}</div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="col-12">
                                <div class="text-center text-muted">
                                    <p>No data available for {{ $selectedYear - 1 }} to compare</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Key Metrics Summary -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Key Performance Indicators</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="d-flex align-items-center">
                                    <div class="subheader">Active Months</div>
                                    <div class="ms-auto lh-1">
                                        <div class="strong">{{ count($monthlyData) }}/12</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="d-flex align-items-center">
                                    <div class="subheader">Avg Monthly Sales</div>
                                    <div class="ms-auto lh-1">
                                        <div class="strong">LKR {{ number_format($salesData['total_sales'] / 12) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="d-flex align-items-center">
                                    <div class="subheader">Best Month</div>
                                    <div class="ms-auto lh-1">
                                        @php
                                            $bestMonth = collect($monthlyData)->sortByDesc('total_sales')->first();
                                            $bestMonthKey = collect($monthlyData)->sortByDesc('total_sales')->keys()->first();
                                        @endphp
                                        <div class="strong">
                                            {{ $bestMonth ? date('M', mktime(0, 0, 0, $bestMonthKey, 1)) : 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="d-flex align-items-center">
                                    <div class="subheader">Peak Sales</div>
                                    <div class="ms-auto lh-1">
                                        <div class="strong">LKR {{ number_format(collect($monthlyData)->max('total_sales') ?? 0) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="d-flex align-items-center">
                                    <div class="subheader">Avg Order Value</div>
                                    <div class="ms-auto lh-1">
                                        <div class="strong">LKR {{ number_format($salesData['average_order_value']) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="d-flex align-items-center">
                                    <div class="subheader">Revenue per Day</div>
                                    <div class="ms-auto lh-1">
                                        <div class="strong">LKR {{ number_format($salesData['total_sales'] / 365) }}</div>
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
    const monthlyData = @json($monthlyData);
    const quarterlyData = @json($quarterlyData);
    
    let monthlyChart;
    let quarterlyChart;
    
    // Prepare monthly data
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const monthlySales = [];
    const monthlyOrders = [];
    
    for (let i = 1; i <= 12; i++) {
        const monthData = monthlyData[i];
        monthlySales.push(monthData ? parseFloat(monthData.total_sales) : 0);
        monthlyOrders.push(monthData ? parseInt(monthData.order_count) : 0);
    }

    // Monthly chart
    function renderMonthlyChart(type = 'area') {
        if (monthlyChart) {
            monthlyChart.destroy();
        }
        
        const monthlyOptions = {
            series: [{
                name: 'Sales (LKR)',
                data: monthlySales
            }, {
                name: 'Orders',
                data: monthlyOrders
            }],
            chart: {
                type: type,
                height: 400,
                toolbar: {
                    show: true
                }
            },
            colors: ['#28a745', '#17a2b8'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            fill: {
                type: type === 'area' ? 'gradient' : 'solid',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.1
                }
            },
            xaxis: {
                categories: months,
                title: {
                    text: 'Month'
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
                    text: 'Orders'
                },
                labels: {
                    formatter: function (value) {
                        return Math.round(value);
                    }
                }
            }],
            tooltip: {
                y: [{
                    formatter: function (value) {
                        return 'LKR ' + Math.round(value).toLocaleString();
                    }
                }, {
                    formatter: function (value) {
                        return Math.round(value) + ' orders';
                    }
                }]
            },
            legend: {
                position: 'top'
            }
        };

        monthlyChart = new ApexCharts(document.querySelector("#monthly-chart"), monthlyOptions);
        monthlyChart.render();
    }

    // Initial render
    renderMonthlyChart('area');

    // Chart type changer
    window.changeChartType = function(type) {
        renderMonthlyChart(type);
    };

    // Quarterly chart
    const quarterNames = ['Q1', 'Q2', 'Q3', 'Q4'];
    const quarterlySales = [];
    
    quarterNames.forEach((quarter, index) => {
        const quarterNum = index + 1;
        const quarterData = quarterlyData[quarterNum];
        quarterlySales.push(quarterData ? parseFloat(quarterData.total_sales) : 0);
    });

    const quarterlyOptions = {
        series: [{
            data: quarterlySales
        }],
        chart: {
            type: 'donut',
            height: 300
        },
        colors: ['#28a745', '#17a2b8', '#ffc107', '#dc3545'],
        labels: quarterNames,
        dataLabels: {
            enabled: true,
            formatter: function (val, opts) {
                return Math.round(val) + '%';
            }
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '70%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total Sales',
                            formatter: function (w) {
                                const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                return 'LKR ' + Math.round(total).toLocaleString();
                            }
                        }
                    }
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
        legend: {
            position: 'bottom'
        }
    };

    quarterlyChart = new ApexCharts(document.querySelector("#quarterly-chart"), quarterlyOptions);
    quarterlyChart.render();
});
</script>
@endpush