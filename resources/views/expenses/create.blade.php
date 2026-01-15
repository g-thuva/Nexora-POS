@extends('layouts.nexora')

@section('title', 'Record Expense')

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <x-alert />

        <!-- Page Header -->
        <div class="row mb-2">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="page-title mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-danger" width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12"/>
                                <path d="M20 12v4h-4a2 2 0 0 1 0 -4h4"/>
                            </svg>
                            {{ __('Expense Management') }}
                        </h1>
                        <p class="text-muted">Record and track operating expenses efficiently</p>
                    </div>
                    <div class="btn-list">
                        <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M3 7v4a1 1 0 0 0 1 1h3"/>
                                <path d="M7 7a2 2 0 1 1 2 2h-2z"/>
                                <path d="M21 11v-4a1 1 0 0 0 -1 -1h-3"/>
                                <path d="M17 7a2 2 0 1 0 -2 2h2z"/>
                                <path d="M7 21v-4a1 1 0 0 1 1 -1h3"/>
                                <path d="M7 17a2 2 0 1 0 2 -2h-2z"/>
                                <path d="M21 17v4a1 1 0 0 1 -1 1h-3"/>
                                <path d="M17 21a2 2 0 1 1 -2 -2h2z"/>
                            </svg>
                            All Expenses
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row row-cards mb-3">
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-primary text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12"/>
                                        <path d="M20 12v4h-4a2 2 0 0 1 0 -4h4"/>
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium text-muted small text-uppercase">Total Expenses</div>
                                <div class="h3 mb-0">LKR {{ number_format(($totalExpenses ?? 0) / 100, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-danger text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <rect x="4" y="5" width="16" height="16" rx="2"/>
                                        <line x1="16" y1="3" x2="16" y2="7"/>
                                        <line x1="8" y1="3" x2="8" y2="7"/>
                                        <line x1="4" y1="11" x2="20" y2="11"/>
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium text-muted small text-uppercase">This Month</div>
                                <div class="h3 mb-0">LKR {{ number_format(($monthTotal ?? 0) / 100, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-warning text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="12" cy="12" r="9"/>
                                        <polyline points="12 7 12 12 15 15"/>
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium text-muted small text-uppercase">Last 7 Days</div>
                                <div class="h3 mb-0">LKR {{ number_format(($weekTotal ?? 0) / 100, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-info text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"/>
                                        <rect x="9" y="3" width="6" height="4" rx="2"/>
                                        <line x1="9" y1="12" x2="9.01" y2="12"/>
                                        <line x1="13" y1="12" x2="15" y2="12"/>
                                        <line x1="9" y1="16" x2="9.01" y2="16"/>
                                        <line x1="13" y1="16" x2="15" y2="16"/>
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium text-muted small text-uppercase">Expense Types</div>
                                <div class="h3 mb-0">{{ $typesCount ?? 0 }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Tips & Guidelines -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-primary" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 9h.01"/>
                                <path d="M11 12h1v4h1"/>
                                <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z"/>
                            </svg>
                            Tips & Guidelines
                        </h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3">
                                <div class="d-flex align-items-start">
                                    <span class="badge bg-primary me-2">1</span>
                                    <div>
                                        <div class="fw-bold">Categorize Properly</div>
                                        <div class="text-muted">Choose the correct expense type for accurate tracking</div>
                                    </div>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="d-flex align-items-start">
                                    <span class="badge bg-info me-2">2</span>
                                    <div>
                                        <div class="fw-bold">Detailed Notes</div>
                                        <div class="text-muted">Include vendor name, invoice number, and purpose</div>
                                    </div>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="d-flex align-items-start">
                                    <span class="badge bg-warning me-2">3</span>
                                    <div>
                                        <div class="fw-bold">Accurate Dates</div>
                                        <div class="text-muted">Use the actual expense date, not recording date</div>
                                    </div>
                                </div>
                            </li>
                            <li class="mb-0">
                                <div class="d-flex align-items-start">
                                    <span class="badge bg-success me-2">4</span>
                                    <div>
                                        <div class="fw-bold">Regular Reviews</div>
                                        <div class="text-muted">Review expenses monthly for budget tracking</div>
                                    </div>
                                </div>
                            </li>
                        </ul>

                        <hr class="my-3">

                        <div class="fw-bold mb-3 text-success">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"/>
                                <rect x="9" y="3" width="6" height="4" rx="2"/>
                                <line x1="9" y1="12" x2="9.01" y2="12"/>
                                <line x1="13" y1="12" x2="15" y2="12"/>
                                <line x1="9" y1="16" x2="9.01" y2="16"/>
                                <line x1="13" y1="16" x2="15" y2="16"/>
                            </svg>
                            Common Expense Types
                        </div>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1 text-primary" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <polyline points="9 11 12 14 20 6"/>
                                    <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"/>
                                </svg>
                                <strong>Rent:</strong> Office/Warehouse space
                            </li>
                            <li class="mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1 text-info" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <polyline points="9 11 12 14 20 6"/>
                                    <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"/>
                                </svg>
                                <strong>Supplies:</strong> Materials & stock
                            </li>
                            <li class="mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1 text-warning" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <polyline points="9 11 12 14 20 6"/>
                                    <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"/>
                                </svg>
                                <strong>Repairs:</strong> Maintenance work
                            </li>
                            <li class="mb-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1 text-success" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <polyline points="9 11 12 14 20 6"/>
                                    <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"/>
                                </svg>
                                <strong>Transport:</strong> Fuel & travel costs
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Expense Form -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            New Expense Record
                        </h3>
                    </div>
                    <div class="card-body p-3">
                        <form id="expense-form" method="POST" action="{{ route('expenses.store') }}">
                            @csrf

                            <div class="mb-2">
                                <label for="type" class="form-label required">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"/>
                                        <rect x="9" y="3" width="6" height="4" rx="2"/>
                                    </svg>
                                    Expense Type
                                </label>
                                <select name="type" id="type"
                                        class="form-select @error('type') is-invalid @enderror"
                                        required>
                                    <option value="" disabled {{ old('type') ? '' : 'selected' }}>Select expense type...</option>
                                    <option value="Rent" {{ old('type') == 'Rent' ? 'selected' : '' }}>Rent</option>
                                    <option value="Electricity" {{ old('type') == 'Electricity' ? 'selected' : '' }}>Electricity</option>
                                    <option value="Repairs" {{ old('type') == 'Repairs' ? 'selected' : '' }}>Repairs</option>
                                    <option value="Supplies" {{ old('type') == 'Supplies' ? 'selected' : '' }}>Supplies</option>
                                    <option value="Internet" {{ old('type') == 'Internet' ? 'selected' : '' }}>Internet</option>
                                    <option value="Transport" {{ old('type') == 'Transport' ? 'selected' : '' }}>Transport</option>
                                    <option value="Office Supplies" {{ old('type') == 'Office Supplies' ? 'selected' : '' }}>Office Supplies</option>
                                    <option value="Salaries" {{ old('type') == 'Salaries' ? 'selected' : '' }}>Salaries</option>
                                    <option value="Marketing designing & Video Editing" {{ old('type') == 'Marketing designing & Video Editing' ? 'selected' : '' }}>Marketing designing & Video Editing</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-hint">Select the category or type of expense</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label for="amount" class="form-label required">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2"/>
                                            <path d="M12 3v3m0 12v3"/>
                                        </svg>
                                        Amount (LKR)
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">LKR</span>
                                        <input type="number" step="0.01" min="0" name="amount" id="amount"
                                               class="form-control @error('amount') is-invalid @enderror"
                                               placeholder="0.00"
                                               value="{{ old('amount') }}"
                                               required>
                                        @error('amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-2">
                                    <label for="expense_date" class="form-label required">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <rect x="4" y="5" width="16" height="16" rx="2"/>
                                            <line x1="16" y1="3" x2="16" y2="7"/>
                                            <line x1="8" y1="3" x2="8" y2="7"/>
                                            <line x1="4" y1="11" x2="20" y2="11"/>
                                        </svg>
                                        Date
                                    </label>
                                    <input type="date" name="expense_date" id="expense_date"
                                           class="form-control @error('expense_date') is-invalid @enderror"
                                           value="{{ old('expense_date', now()->toDateString()) }}"
                                           max="{{ now()->toDateString() }}"
                                           required>
                                    @error('expense_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Dynamic Detail Fields -->
                            <div id="detail-fields" class="mb-2" style="display: none;">
                                <!-- Rent Details -->
                                <div class="detail-section" data-type="Rent" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Property/Location</label>
                                            <input type="text" name="details[property]" class="form-control" placeholder="e.g., Office Building, Warehouse">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Period</label>
                                            <input type="text" name="details[period]" class="form-control" placeholder="e.g., January 2026">
                                        </div>
                                    </div>
                                </div>

                                <!-- Electricity Details -->
                                <div class="detail-section" data-type="Electricity" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Account Number</label>
                                            <input type="text" name="details[account_number]" class="form-control" placeholder="Electricity account number">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Units Consumed</label>
                                            <input type="number" name="details[units]" class="form-control" placeholder="e.g., 450 kWh">
                                        </div>
                                    </div>
                                </div>

                                <!-- Repairs Details -->
                                <div class="detail-section" data-type="Repairs" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Item/Equipment</label>
                                            <input type="text" name="details[item]" class="form-control" placeholder="What was repaired?">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Vendor/Technician</label>
                                            <input type="text" name="details[vendor]" class="form-control" placeholder="Service provider name">
                                        </div>
                                    </div>
                                </div>

                                <!-- Supplies Details -->
                                <div class="detail-section" data-type="Supplies" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Supplier</label>
                                            <input type="text" name="details[supplier]" class="form-control" placeholder="Supplier name">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Quantity</label>
                                            <input type="text" name="details[quantity]" class="form-control" placeholder="e.g., 50 units">
                                        </div>
                                    </div>
                                </div>

                                <!-- Internet Details -->
                                <div class="detail-section" data-type="Internet" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Provider</label>
                                            <input type="text" name="details[provider]" class="form-control" placeholder="Internet service provider">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Plan/Speed</label>
                                            <input type="text" name="details[plan]" class="form-control" placeholder="e.g., 100 Mbps Fiber">
                                        </div>
                                    </div>
                                </div>

                                <!-- Transport Details -->
                                <div class="detail-section" data-type="Transport" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Vehicle/Mode</label>
                                            <input type="text" name="details[vehicle]" class="form-control" placeholder="e.g., Van #123, Taxi">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Distance/Route</label>
                                            <input type="text" name="details[route]" class="form-control" placeholder="e.g., 45 km, Office to Client">
                                        </div>
                                    </div>
                                </div>

                                <!-- Office Supplies Details -->
                                <div class="detail-section" data-type="Office Supplies" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Store/Vendor</label>
                                            <input type="text" name="details[store]" class="form-control" placeholder="Where purchased">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Items</label>
                                            <input type="text" name="details[items]" class="form-control" placeholder="e.g., Stationery, Printer ink">
                                        </div>
                                    </div>
                                </div>

                                <!-- Salaries Details -->
                                <div class="detail-section" data-type="Salaries" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Employee/Department</label>
                                            <input type="text" name="details[employee]" class="form-control" placeholder="Name or Department">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Period</label>
                                            <input type="text" name="details[salary_period]" class="form-control" placeholder="e.g., January 2026">
                                        </div>
                                    </div>
                                </div>

                                <!-- Marketing designing & Video Editing Details -->
                                <div class="detail-section" data-type="Marketing designing & Video Editing" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Project/Campaign</label>
                                            <input type="text" name="details[project]" class="form-control" placeholder="Project or campaign name">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Service Provider</label>
                                            <input type="text" name="details[designer]" class="form-control" placeholder="Designer or agency name">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <line x1="3" y1="19" x2="21" y2="19"/>
                                        <polyline points="6 19 6 7 18 7 18 19"/>
                                        <line x1="9" y1="10" x2="15" y2="10"/>
                                        <line x1="9" y1="13" x2="15" y2="13"/>
                                        <line x1="9" y1="16" x2="15" y2="16"/>
                                    </svg>
                                    Notes (Optional)
                                </label>
                                <textarea name="notes" id="notes" rows="2"
                                          class="form-control @error('notes') is-invalid @enderror"
                                          placeholder="Add any additional details or description...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <button class="btn btn-outline-secondary" type="reset">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"/>
                                        <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"/>
                                    </svg>
                                    Reset
                                </button>
                                <button class="btn btn-primary" type="submit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M5 12l5 5l10 -10"/>
                                    </svg>
                                    Save Expense
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const detailFields = document.getElementById('detail-fields');
        const detailSections = document.querySelectorAll('.detail-section');

        typeSelect.addEventListener('change', function() {
            const selectedType = this.value;

            // Hide all detail sections first
            detailSections.forEach(section => {
                section.style.display = 'none';
            });

            // Show the detail fields container and selected section
            if (selectedType && selectedType !== '') {
                detailFields.style.display = 'block';
                const selectedSection = document.querySelector('.detail-section[data-type="' + selectedType + '"]');
                if (selectedSection) {
                    selectedSection.style.display = 'block';
                }
            } else {
                detailFields.style.display = 'none';
            }
        });

        // Trigger change on page load if there's a selected value
        if (typeSelect.value && typeSelect.value !== '') {
            typeSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush
