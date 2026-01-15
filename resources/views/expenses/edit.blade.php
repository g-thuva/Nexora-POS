@extends('layouts.nexora')

@section('title', 'Edit Expense')

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
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-primary" width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                <path d="M16 5l3 3"/>
                            </svg>
                            {{ __('Edit Expense') }} #{{ $expense->id }}
                        </h1>
                        <p class="text-muted">Update expense details and notes</p>
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
                        <a href="{{ route('expenses.create') }}" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            New Expense
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Expense Details Card -->
            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12"/>
                                <path d="M20 12v4h-4a2 2 0 0 1 0 -4h4"/>
                            </svg>
                            Expense Details
                        </h3>
                    </div>
                    <div class="card-body p-3">
                        <form method="POST" action="{{ route('expenses.update', $expense) }}">
                            @csrf
                            @method('PUT')

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
                                    <option value="" disabled>Select expense type...</option>
                                    <option value="Rent" {{ old('type', $expense->type) == 'Rent' ? 'selected' : '' }}>Rent</option>
                                    <option value="Electricity" {{ old('type', $expense->type) == 'Electricity' ? 'selected' : '' }}>Electricity</option>
                                    <option value="Repairs" {{ old('type', $expense->type) == 'Repairs' ? 'selected' : '' }}>Repairs</option>
                                    <option value="Supplies" {{ old('type', $expense->type) == 'Supplies' ? 'selected' : '' }}>Supplies</option>
                                    <option value="Internet" {{ old('type', $expense->type) == 'Internet' ? 'selected' : '' }}>Internet</option>
                                    <option value="Transport" {{ old('type', $expense->type) == 'Transport' ? 'selected' : '' }}>Transport</option>
                                    <option value="Office Supplies" {{ old('type', $expense->type) == 'Office Supplies' ? 'selected' : '' }}>Office Supplies</option>
                                    <option value="Salaries" {{ old('type', $expense->type) == 'Salaries' ? 'selected' : '' }}>Salaries</option>
                                    <option value="Marketing designing & Video Editing" {{ old('type', $expense->type) == 'Marketing designing & Video Editing' ? 'selected' : '' }}>Marketing designing & Video Editing</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                               value="{{ old('amount', number_format($expense->amount, 2, '.', '')) }}"
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
                                           value="{{ old('expense_date', optional($expense->expense_date)->format('Y-m-d')) }}"
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
                                            <input type="text" name="details[property]" class="form-control" placeholder="e.g., Office Building, Warehouse" value="{{ old('details.property', $expense->details['property'] ?? '') }}">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Period</label>
                                            <input type="text" name="details[period]" class="form-control" placeholder="e.g., January 2026" value="{{ old('details.period', $expense->details['period'] ?? '') }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Electricity Details -->
                                <div class="detail-section" data-type="Electricity" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Account Number</label>
                                            <input type="text" name="details[account_number]" class="form-control" placeholder="Electricity account number" value="{{ old('details.account_number', $expense->details['account_number'] ?? '') }}">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Units Consumed</label>
                                            <input type="number" name="details[units]" class="form-control" placeholder="e.g., 450 kWh" value="{{ old('details.units', $expense->details['units'] ?? '') }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Repairs Details -->
                                <div class="detail-section" data-type="Repairs" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Item/Equipment</label>
                                            <input type="text" name="details[item]" class="form-control" placeholder="What was repaired?" value="{{ old('details.item', $expense->details['item'] ?? '') }}">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Vendor/Technician</label>
                                            <input type="text" name="details[vendor]" class="form-control" placeholder="Service provider name" value="{{ old('details.vendor', $expense->details['vendor'] ?? '') }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Supplies Details -->
                                <div class="detail-section" data-type="Supplies" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Supplier</label>
                                            <input type="text" name="details[supplier]" class="form-control" placeholder="Supplier name" value="{{ old('details.supplier', $expense->details['supplier'] ?? '') }}">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Quantity</label>
                                            <input type="text" name="details[quantity]" class="form-control" placeholder="e.g., 50 units" value="{{ old('details.quantity', $expense->details['quantity'] ?? '') }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Internet Details -->
                                <div class="detail-section" data-type="Internet" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Provider</label>
                                            <input type="text" name="details[provider]" class="form-control" placeholder="Internet service provider" value="{{ old('details.provider', $expense->details['provider'] ?? '') }}">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Plan/Speed</label>
                                            <input type="text" name="details[plan]" class="form-control" placeholder="e.g., 100 Mbps Fiber" value="{{ old('details.plan', $expense->details['plan'] ?? '') }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Transport Details -->
                                <div class="detail-section" data-type="Transport" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Vehicle/Mode</label>
                                            <input type="text" name="details[vehicle]" class="form-control" placeholder="e.g., Van #123, Taxi" value="{{ old('details.vehicle', $expense->details['vehicle'] ?? '') }}">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Distance/Route</label>
                                            <input type="text" name="details[route]" class="form-control" placeholder="e.g., 45 km, Office to Client" value="{{ old('details.route', $expense->details['route'] ?? '') }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Office Supplies Details -->
                                <div class="detail-section" data-type="Office Supplies" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Store/Vendor</label>
                                            <input type="text" name="details[store]" class="form-control" placeholder="Where purchased" value="{{ old('details.store', $expense->details['store'] ?? '') }}">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Items</label>
                                            <input type="text" name="details[items]" class="form-control" placeholder="e.g., Stationery, Printer ink" value="{{ old('details.items', $expense->details['items'] ?? '') }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Salaries Details -->
                                <div class="detail-section" data-type="Salaries" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Employee/Department</label>
                                            <input type="text" name="details[employee]" class="form-control" placeholder="Name or Department" value="{{ old('details.employee', $expense->details['employee'] ?? '') }}">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Period</label>
                                            <input type="text" name="details[salary_period]" class="form-control" placeholder="e.g., January 2026" value="{{ old('details.salary_period', $expense->details['salary_period'] ?? '') }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Marketing designing & Video Editing Details -->
                                <div class="detail-section" data-type="Marketing designing & Video Editing" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Project/Campaign</label>
                                            <input type="text" name="details[project]" class="form-control" placeholder="Project or campaign name" value="{{ old('details.project', $expense->details['project'] ?? '') }}">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Service Provider</label>
                                            <input type="text" name="details[designer]" class="form-control" placeholder="Designer or agency name" value="{{ old('details.designer', $expense->details['designer'] ?? '') }}">
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
                                          placeholder="Add any additional details...">{{ old('notes', $expense->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('expenses.create') }}" class="btn btn-outline-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1"/>
                                    </svg>
                                    Back to Create
                                </a>
                                <button class="btn btn-primary" type="submit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M5 12l5 5l10 -10"/>
                                    </svg>
                                    Update Expense
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Expense Info & Recent List -->
            <div class="col-lg-4">
                <!-- Quick Info Card -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <circle cx="12" cy="12" r="9"/>
                                <line x1="12" y1="8" x2="12.01" y2="8"/>
                                <polyline points="11 12 12 12 12 16 13 16"/>
                            </svg>
                            Quick Info
                        </h3>
                    </div>
                    <div class="card-body p-3">
                        <div class="mb-2">
                            <div class="text-muted small mb-1">Expense ID</div>
                            <div class="h4 mb-0">#{{ $expense->id }}</div>
                        </div>
                        <div class="mb-2">
                            <div class="text-muted small mb-1">Created</div>
                            <div>{{ optional($expense->created_at)->format('d M Y, h:i A') }}</div>
                        </div>
                        <div class="mb-2">
                            <div class="text-muted small mb-1">Last Updated</div>
                            <div>{{ optional($expense->updated_at)->format('d M Y, h:i A') }}</div>
                        </div>
                        @if($expense->created_by)
                            <div class="mb-0">
                                <div class="text-muted small mb-1">Created By</div>
                                <div>User #{{ $expense->created_by }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Expenses Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <circle cx="12" cy="12" r="9"/>
                                <polyline points="12 7 12 12 15 15"/>
                            </svg>
                            Recent Expenses
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        @php
                            $recentExp = \App\Models\Expense::latest('expense_date')->latest()->limit(8)->get();
                        @endphp

                        @if($recentExp->isNotEmpty())
                            <div class="list-group list-group-flush">
                                @foreach($recentExp as $e)
                                    <a href="{{ route('expenses.edit', $e) }}"
                                       class="list-group-item list-group-item-action {{ $e->id == $expense->id ? 'active' : '' }}"
                                       style="border-left: 3px solid {{ $e->id == $expense->id ? '#206bc4' : 'transparent' }};">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <div class="fw-bold {{ $e->id == $expense->id ? 'text-white' : '' }}">
                                                    {{ $e->type }}
                                                </div>
                                                <div class="small {{ $e->id == $expense->id ? 'text-white-50' : 'text-muted' }}">
                                                    #{{ $e->id }} • {{ optional($e->expense_date)->format('d M Y') }}
                                                </div>
                                            </div>
                                            <div>
                                                <span class="badge {{ $e->id == $expense->id ? 'bg-white text-primary' : 'bg-danger-lt' }}">
                                                    LKR {{ number_format($e->amount, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <div class="text-muted">No other expenses found</div>
                            </div>
                        @endif
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

            // Show the selected section
            if (selectedType) {
                detailFields.style.display = 'block';
                const selectedSection = document.querySelector('.detail-section[data-type="' + selectedType + '"]');
                if (selectedSection) {
                    selectedSection.style.display = 'block';
                }
            } else {
                detailFields.style.display = 'none';
            }
        });

        // Trigger change on page load to show current expense type details
        if (typeSelect.value) {
            typeSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush
