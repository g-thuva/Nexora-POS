@extends('layouts.nexora')

@section('title', 'Record Product Return')

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
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-warning" width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M3 12l3 3l3 -3l-3 -3z"/>
                                <path d="M21 12l-3 3l-3 -3l3 -3z"/>
                                <path d="M12 3l3 3l-3 3l-3 -3z"/>
                                <path d="M12 21l3 -3l-3 -3l-3 3z"/>
                                <path d="M12 12m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>
                            </svg>
                            {{ __('Returns Management') }}
                        </h1>
                        <p class="text-muted">Record product returns and restore inventory levels</p>
                    </div>
                    <div class="btn-list">
                        <a href="{{ route('returns.index') }}" class="btn btn-outline-secondary">
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
                            All Returns
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
                                <span class="bg-warning text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M3 12l3 3l3 -3l-3 -3z"/>
                                        <path d="M21 12l-3 3l-3 -3l3 -3z"/>
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium text-muted small text-uppercase">Total Returns</div>
                                <div class="h3 mb-0">LKR {{ number_format(($totalReturns ?? 0) / 100, 2) }}</div>
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
                                        <polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3"/>
                                        <line x1="12" y1="12" x2="20" y2="7.5"/>
                                        <line x1="12" y1="12" x2="12" y2="21"/>
                                        <line x1="12" y1="12" x2="4" y2="7.5"/>
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium text-muted small text-uppercase">Items Returned</div>
                                <div class="h3 mb-0">{{ $itemsReturned ?? 0 }}</div>
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
                                <span class="bg-azure text-white avatar">
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
                                <span class="bg-purple text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M6 4h11a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-11a1 1 0 0 1 -1 -1v-14a1 1 0 0 1 1 -1m3 0v18"/>
                                        <line x1="13" y1="8" x2="15" y2="8"/>
                                        <line x1="13" y1="12" x2="15" y2="12"/>
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium text-muted small text-uppercase">This Week</div>
                                <div class="h3 mb-0">LKR {{ number_format(($weekTotal ?? 0) / 100, 2) }}</div>
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
                                        <div class="fw-bold">Verify Returns</div>
                                        <div class="text-muted">Check product condition and match serial numbers before accepting</div>
                                    </div>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="d-flex align-items-start">
                                    <span class="badge bg-info me-2">2</span>
                                    <div>
                                        <div class="fw-bold">Customer Details</div>
                                        <div class="text-muted">Link returns to customer records for tracking and analysis</div>
                                    </div>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="d-flex align-items-start">
                                    <span class="badge bg-warning me-2">3</span>
                                    <div>
                                        <div class="fw-bold">Return Reasons</div>
                                        <div class="text-muted">Document the reason for return in notes for future reference</div>
                                    </div>
                                </div>
                            </li>
                            <li class="mb-0">
                                <div class="d-flex align-items-start">
                                    <span class="badge bg-success me-2">4</span>
                                    <div>
                                        <div class="fw-bold">Stock Restoration</div>
                                        <div class="text-muted">Inventory levels are automatically updated when returns are saved</div>
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
                            Common Return Types
                        </div>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1 text-danger" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <polyline points="9 11 12 14 20 6"/>
                                    <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"/>
                                </svg>
                                <strong>Defective:</strong> Product malfunction or damage
                            </li>
                            <li class="mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1 text-warning" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <polyline points="9 11 12 14 20 6"/>
                                    <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"/>
                                </svg>
                                <strong>Wrong Item:</strong> Incorrect product shipped
                            </li>
                            <li class="mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1 text-info" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <polyline points="9 11 12 14 20 6"/>
                                    <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"/>
                                </svg>
                                <strong>Changed Mind:</strong> Customer no longer wants item
                            </li>
                            <li class="mb-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1 text-primary" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <polyline points="9 11 12 14 20 6"/>
                                    <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"/>
                                </svg>
                                <strong>Warranty:</strong> Within warranty period
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Return Form -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <rect x="3" y="4" width="18" height="16" rx="2"/>
                                <line x1="7" y1="8" x2="17" y2="8"/>
                                <line x1="7" y1="12" x2="13" y2="12"/>
                                <line x1="7" y1="16" x2="15" y2="16"/>
                            </svg>
                            Return Details
                        </h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('returns.store') }}">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="customer_id" class="form-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <circle cx="12" cy="7" r="4"/>
                                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/>
                                        </svg>
                                        Customer (Optional)
                                    </label>
                                    <input type="number" name="customer_id" id="customer_id" class="form-control @error('customer_id') is-invalid @enderror" placeholder="Enter customer ID">
                                    @error('customer_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Leave empty for walk-in customers</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="return_date" class="form-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <rect x="4" y="5" width="16" height="16" rx="2"/>
                                            <line x1="16" y1="3" x2="16" y2="7"/>
                                            <line x1="8" y1="3" x2="8" y2="7"/>
                                            <line x1="4" y1="11" x2="20" y2="11"/>
                                        </svg>
                                        Return Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" name="return_date" id="return_date" class="form-control @error('return_date') is-invalid @enderror" value="{{ old('return_date', now()->toDateString()) }}" required>
                                    @error('return_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="mb-3">
                                <label class="form-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3"/>
                                        <line x1="12" y1="12" x2="20" y2="7.5"/>
                                        <line x1="12" y1="12" x2="12" y2="21"/>
                                        <line x1="12" y1="12" x2="4" y2="7.5"/>
                                    </svg>
                                    Returned Items <span class="text-danger">*</span>
                                </label>
                                <div id="items" class="border rounded p-3 bg-light">
                                    <div class="row item-row mb-3 g-2">
                                        <div class="col-md-5">
                                            <label class="form-label small">Product</label>
                                            <select name="items[0][product_id]" class="form-select" required>
                                                <option value="">Select product...</option>
                                                @foreach($products as $p)
                                                    <option value="{{ $p->id }}">{{ $p->name }} @if($p->code) ({{ $p->code }}) @endif</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small">Quantity</label>
                                            <input type="number" name="items[0][quantity]" value="1" class="form-control" min="1" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Serial Number</label>
                                            <input type="text" name="items[0][serial_number]" class="form-control" placeholder="Optional">
                                        </div>
                                        <div class="col-md-1 d-flex align-items-end">
                                            <button type="button" class="btn btn-icon btn-ghost-danger remove-item" style="visibility: hidden;">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <line x1="18" y1="6" x2="6" y2="18"/>
                                                    <line x1="6" y1="6" x2="18" y2="18"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="add-item" class="btn btn-sm btn-primary mt-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <line x1="12" y1="5" x2="12" y2="19"/>
                                        <line x1="5" y1="12" x2="19" y2="12"/>
                                    </svg>
                                    Add Another Item
                                </button>
                            </div>

                            <div class="mb-4">
                                <label for="notes" class="form-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <line x1="4" y1="7" x2="20" y2="7"/>
                                        <line x1="10" y1="11" x2="10" y2="17"/>
                                        <line x1="14" y1="11" x2="14" y2="17"/>
                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/>
                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/>
                                    </svg>
                                    Return Reason / Notes
                                </label>
                                <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror" placeholder="Describe the reason for return, product condition, or any other relevant details...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-3">

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('returns.index') }}" class="btn btn-ghost-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <line x1="18" y1="6" x2="6" y2="18"/>
                                        <line x1="6" y1="6" x2="18" y2="18"/>
                                    </svg>
                                    Cancel
                                </a>
                                <button class="btn btn-warning" type="submit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"/>
                                        <circle cx="12" cy="14" r="2"/>
                                        <polyline points="14 4 14 8 8 8 8 4"/>
                                    </svg>
                                    Save Return
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('page-scripts')
<script>
    (function(){
        let idx = 1;

        // Add item functionality
        document.getElementById('add-item').addEventListener('click', function(){
            const template = document.querySelector('.item-row').cloneNode(true);

            // Update names with new index
            template.querySelectorAll('select, input').forEach(function(el){
                if(el.name) {
                    el.name = el.name.replace(/items\[0\]/, 'items['+idx+']');
                    el.value = '';
                }
            });

            // Show remove button
            const removeBtn = template.querySelector('.remove-item');
            removeBtn.style.visibility = 'visible';

            document.getElementById('items').appendChild(template);
            idx++;
        });

        // Remove item functionality (using event delegation)
        document.getElementById('items').addEventListener('click', function(e){
            const removeBtn = e.target.closest('.remove-item');
            if(removeBtn && document.querySelectorAll('.item-row').length > 1){
                removeBtn.closest('.item-row').remove();
            }
        });
    })();
</script>
@endpush
@endsection
