@extends('layouts.nexora')

@section('title', 'Edit Return #' . $returnSale->id)

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <x-alert />

        <!-- Page Header -->
        <div class="row mb-3">
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
                            Edit Return #{{ $returnSale->id }}
                        </h1>
                        <p class="text-muted">Update return information and details</p>
                    </div>
                    <div class="btn-list">
                        <a href="{{ route('returns.index') }}" class="btn btn-outline-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <polyline points="15 6 9 12 15 18"/>
                            </svg>
                            Back to List
                        </a>
                        <a href="{{ route('returns.show', $returnSale) }}" class="btn btn-info">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <circle cx="12" cy="12" r="2"/>
                                <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7"/>
                            </svg>
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Info -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-info" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 9h.01"/>
                                <path d="M11 12h1v4h1"/>
                                <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z"/>
                            </svg>
                            Quick Info
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted small">Return ID</label>
                            <div class="h3 mb-0">#{{ $returnSale->id }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Total Items</label>
                            <div class="h3 mb-0">{{ $returnSale->items->count() }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Total Amount</label>
                            <div class="h3 mb-0 text-warning">LKR {{ number_format($returnSale->total, 2) }}</div>
                        </div>
                        @if($returnSale->customer)
                        <div class="mb-0">
                            <label class="form-label text-muted small">Customer</label>
                            <div class="h4 mb-0">{{ $returnSale->customer->name }}</div>
                            @if($returnSale->customer->phone)
                            <div class="text-muted small">{{ $returnSale->customer->phone }}</div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Tips -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-primary" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 9h.01"/>
                                <path d="M11 12h1v4h1"/>
                                <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z"/>
                            </svg>
                            Edit Guidelines
                        </h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1 text-warning" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M12 9v2m0 4v.01"/>
                                    <path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75"/>
                                </svg>
                                <strong>Note:</strong> Items cannot be modified after creation
                            </li>
                            <li class="mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1 text-info" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <polyline points="9 11 12 14 20 6"/>
                                    <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"/>
                                </svg>
                                Update return date if needed
                            </li>
                            <li class="mb-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1 text-success" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <polyline points="9 11 12 14 20 6"/>
                                    <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"/>
                                </svg>
                                Add detailed notes about the return
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                <path d="M16 5l3 3"/>
                            </svg>
                            Return Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('returns.update', $returnSale) }}">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
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
                                    <input type="date" name="return_date" id="return_date" value="{{ old('return_date', $returnSale->return_date?->format('Y-m-d')) }}" class="form-control @error('return_date') is-invalid @enderror" required>
                                    @error('return_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="notes" class="form-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
                                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/>
                                        <line x1="9" y1="9" x2="10" y2="9"/>
                                        <line x1="9" y1="13" x2="15" y2="13"/>
                                        <line x1="9" y1="17" x2="15" y2="17"/>
                                    </svg>
                                    Return Reason / Notes
                                </label>
                                <textarea name="notes" id="notes" rows="4" class="form-control @error('notes') is-invalid @enderror" placeholder="Add details about the return, product condition, refund status, etc...">{{ old('notes', $returnSale->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-3">

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('returns.show', $returnSale) }}" class="btn btn-ghost-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <line x1="18" y1="6" x2="6" y2="18"/>
                                        <line x1="6" y1="6" x2="18" y2="18"/>
                                    </svg>
                                    Cancel
                                </a>
                                <button class="btn btn-primary" type="submit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"/>
                                        <circle cx="12" cy="14" r="2"/>
                                        <polyline points="14 4 14 8 8 8 8 4"/>
                                    </svg>
                                    Update Return
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Returned Items (Read-only) -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-info" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3"/>
                                <line x1="12" y1="12" x2="20" y2="7.5"/>
                                <line x1="12" y1="12" x2="12" y2="21"/>
                                <line x1="12" y1="12" x2="4" y2="7.5"/>
                            </svg>
                            Returned Items
                        </h3>
                        <div class="ms-auto">
                            <span class="badge bg-azure">{{ $returnSale->items->count() }} Items</span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Unit Cost</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($returnSale->items as $item)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $item->product->name ?? 'Unknown Product' }}</div>
                                        @if($item->product && $item->product->code)
                                        <div class="text-muted small">Code: {{ $item->product->code }}</div>
                                        @endif
                                        @if($item->serial_number)
                                        <div class="text-muted small">S/N: {{ $item->serial_number }}</div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-azure-lt">{{ $item->quantity }}</span>
                                    </td>
                                    <td class="text-end">
                                        LKR {{ number_format($item->unitcost, 2) }}
                                    </td>
                                    <td class="text-end fw-bold">
                                        LKR {{ number_format($item->total, 2) }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">No items in this return</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Subtotal:</td>
                                    <td class="text-end fw-bold">LKR {{ number_format($returnSale->sub_total, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold fs-3">Total Return Amount:</td>
                                    <td class="text-end fw-bold fs-3 text-warning">LKR {{ number_format($returnSale->total, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="card-footer">
                        <div class="alert alert-info mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <circle cx="12" cy="12" r="9"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            <strong>Note:</strong> Items cannot be modified after a return is created. To change items, please create a new return record.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
