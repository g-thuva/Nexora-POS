@extends('layouts.nexora')

@section('title', 'Return Details #' . $returnSale->id)

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
                            Return #{{ $returnSale->id }}
                        </h1>
                        <p class="text-muted">Detailed view of product return record</p>
                    </div>
                    <div class="btn-list">
                        <a href="{{ route('returns.index') }}" class="btn btn-ghost-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <polyline points="15 6 9 12 15 18"/>
                            </svg>
                            Back to List
                        </a>
                        <a href="{{ route('returns.edit', $returnSale) }}" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                <path d="M16 5l3 3"/>
                            </svg>
                            Edit Return
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Return Overview -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Return Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small">Return Date</label>
                                <div class="h4 mb-0">
                                    {{ $returnSale->return_date ? $returnSale->return_date->format('d M Y') : 'N/A' }}
                                </div>
                                @if($returnSale->return_date)
                                <div class="text-muted small">{{ $returnSale->return_date->format('l, g:i A') }}</div>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small">Customer</label>
                                <div class="h4 mb-0">
                                    @if($returnSale->customer)
                                        {{ $returnSale->customer->name }}
                                    @else
                                        <span class="text-muted">Walk-in Customer</span>
                                    @endif
                                </div>
                                @if($returnSale->customer && $returnSale->customer->phone)
                                <div class="text-muted small">{{ $returnSale->customer->phone }}</div>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small">Original Order</label>
                                <div class="h4 mb-0">
                                    @if($returnSale->order)
                                        <a href="{{ route('orders.show', $returnSale->order_id) }}" class="text-primary">
                                            Order #{{ $returnSale->order_id }}
                                        </a>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small">Processed By</label>
                                <div class="h4 mb-0">
                                    @if($returnSale->createdBy)
                                        {{ $returnSale->createdBy->name }}
                                    @else
                                        <span class="text-muted">Unknown</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($returnSale->notes)
                        <div class="row mt-2">
                            <div class="col-12">
                                <label class="form-label text-muted small">Notes</label>
                                <div class="alert alert-info mb-0">
                                    {{ $returnSale->notes }}
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Returned Items -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Returned Items</h3>
                        <div class="ms-auto">
                            <span class="badge bg-info">{{ $returnSale->items->count() }} Items</span>
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
                                    <td colspan="4" class="text-center text-muted py-4">
                                        No items in this return
                                    </td>
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
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Summary -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Summary</h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-6">
                                <div class="text-muted small">Return ID</div>
                                <div class="h3 mb-0">#{{ $returnSale->id }}</div>
                            </div>
                            <div class="col-6 text-end">
                                <div class="text-muted small">Items Count</div>
                                <div class="h3 mb-0">{{ $returnSale->items->count() }}</div>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-12">
                                <div class="text-muted small">Total Quantity</div>
                                <div class="h3 mb-0">{{ $returnSale->items->sum('quantity') }} Units</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Actions</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('returns.edit', $returnSale) }}" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                    <path d="M16 5l3 3"/>
                                </svg>
                                Edit Return
                            </a>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <line x1="4" y1="7" x2="20" y2="7"/>
                                    <line x1="10" y1="11" x2="10" y2="17"/>
                                    <line x1="14" y1="11" x2="14" y2="17"/>
                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/>
                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/>
                                </svg>
                                Delete Return
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Metadata -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Metadata</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <div class="text-muted small">Created</div>
                            <div>{{ $returnSale->created_at->format('d M Y, g:i A') }}</div>
                        </div>
                        <div class="mb-2">
                            <div class="text-muted small">Last Updated</div>
                            <div>{{ $returnSale->updated_at->format('d M Y, g:i A') }}</div>
                        </div>
                        @if($returnSale->shop_id)
                        <div>
                            <div class="text-muted small">Shop ID</div>
                            <div>{{ $returnSale->shop_id }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal modal-blur fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-danger"></div>
            <div class="modal-body text-center py-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <circle cx="12" cy="12" r="9"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <h3>Are you sure?</h3>
                <div class="text-muted">Do you really want to delete this return record? This action cannot be undone.</div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn w-100" data-bs-dismiss="modal">Cancel</button>
                        </div>
                        <div class="col">
                            <form action="{{ route('returns.destroy', $returnSale) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
