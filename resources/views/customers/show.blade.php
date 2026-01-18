@extends('layouts.nexora')

@section('content')
<div class="page-header d-print-none">
    <div class="container-fluid">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Customer Relations
                </div>
                <h2 class="page-title">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/>
                    </svg>
                    {{ $customer->name }}
                </h2>
                <p class="text-muted">{{ $customer->phone ?? 'No phone provided' }}</p>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('customers.edit', $customer) }}" class="btn btn-primary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                            <path d="M16 5l3 3"/>
                        </svg>
                        Edit
                    </a>
                    <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <polyline points="15 6 9 12 15 18"/>
                        </svg>
                        Back
                    </a>
                </div>
            </div>
        </div>
        @include('partials._breadcrumbs', ['model' => $customer])
    </div>
</div>

<div class="page-body">
    <div class="container-fluid">
        <!-- Top Stats Row - Full Width -->
        <div class="row row-cards mb-3">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <div class="h3 mb-0">{{ $customer->orders->count() }}</div>
                                <div class="text-muted">Total Orders</div>
                            </div>
                            <div class="ms-auto">
                                <div class="bg-primary text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="9" cy="6" r="3"/>
                                        <path d="M3 9a6 6 0 1 0 12 0"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <div class="h3 mb-0">LKR {{ number_format($customer->orders->sum('total'), 0) }}</div>
                                <div class="text-muted">Total Amount</div>
                            </div>
                            <div class="ms-auto">
                                <div class="bg-success text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 3l20 7l-20 7l-20 -7l20 -7"/>
                                        <polyline points="12 12 12 21"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <div class="h3 mb-0">@if($customer->account_holder) Premium @else Regular @endif</div>
                                <div class="text-muted">Account Type</div>
                            </div>
                            <div class="ms-auto">
                                <div class="bg-warning text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 3l7 4v7a6 6 0 0 1 -7 6a6 6 0 0 1 -7 -6v-7l7 -4"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <div class="h3 mb-0">{{ $customer->created_at->format('M Y') }}</div>
                                <div class="text-muted">Join Date</div>
                            </div>
                            <div class="ms-auto">
                                <div class="bg-info text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3"/>
                                        <line x1="12" y1="12" x2="20" y2="7.5"/>
                                        <line x1="12" y1="12" x2="12" y2="21"/>
                                        <line x1="12" y1="12" x2="4" y2="7.5"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Left: Customer Info, Right: Purchase History -->
        <div class="row row-cards">
            <!-- Left Column: Customer Information -->
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Customer Information</h3>
                    </div>
                    <div class="card-body">
                        <!-- Customer Header with Avatar -->
                        <div class="d-flex mb-3 pb-3" style="border-bottom: 1px solid #e3e6f0;">
                            <div class="avatar avatar-lg me-3" style="background-color: {{ '#' . substr(md5($customer->name), 0, 6) }}; width: 3.5rem; height: 3.5rem; font-size: 1.5rem;">
                                {{ substr($customer->name, 0, 1) }}
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $customer->name }}</div>
                                <div class="text-muted small">@if($customer->account_holder) Account Holder @else Regular Customer @endif</div>
                            </div>
                        </div>

                        <!-- Customer Details -->
                        <div class="mb-3">
                            <span class="form-label">Email</span>
                            <div>{{ $customer->email ?? 'N/A' }}</div>
                        </div>

                        <div class="mb-3">
                            <span class="form-label">Phone</span>
                            <div>{{ $customer->phone ?? 'N/A' }}</div>
                        </div>

                        <div class="mb-3">
                            <span class="form-label">Address</span>
                            <div>{{ $customer->address ?? 'N/A' }}</div>
                        </div>

                        @if($customer->account_holder)
                            <hr class="my-3">
                            <div class="mb-3">
                                <span class="form-label">Account Holder</span>
                                <div>{{ $customer->account_holder }}</div>
                            </div>

                            <div class="mb-3">
                                <span class="form-label">Account Number</span>
                                <div>{{ $customer->account_number }}</div>
                            </div>

                            <div class="mb-0">
                                <span class="form-label">Bank Name</span>
                                <div>{{ $customer->bank_name }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Purchase History -->
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Purchase History</h3>
                    </div>
                    @if($customer->orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th class="w-1">#</th>
                                        <th>Invoice No</th>
                                        <th>Order Date</th>
                                        <th>Items</th>
                                        <th>Amount</th>
                                        <th>Payment Type</th>
                                        <th class="w-1">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customer->orders as $order)
                                        <tr>
                                            <td class="text-muted">{{ $loop->iteration }}</td>
                                            <td>
                                                <span class="badge bg-blue-lt">{{ $order->invoice_no ?? 'ORD' . str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                                            </td>
                                            <td>{{ $order->created_at->format('d M Y') }}</td>
                                            <td>
                                                <span class="badge bg-light text-dark">{{ $order->details->count() }} items</span>
                                            </td>
                                            <td class="fw-bold">LKR {{ number_format($order->total, 2) }}</td>
                                            <td>
                                                @php
                                                    $ptype = strtolower($order->payment_type ?? '');
                                                @endphp
                                                @if($ptype === 'credit sales' || $ptype === 'credit')
                                                    <span class="badge bg-warning">Credit</span>
                                                @elseif($ptype === 'card')
                                                    <span class="badge bg-purple">Card</span>
                                                @elseif($ptype === 'bank transfer')
                                                    <span class="badge bg-info text-dark">Bank</span>
                                                @else
                                                    <span class="badge bg-success">Cash</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-list flex-nowrap">
                                                    <button type="button" class="btn btn-white btn-icon btn-sm" onclick="viewOrderDetails({{ $order->id }})" title="View Details">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                            <circle cx="12" cy="12" r="2"/>
                                                            <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7"/>
                                                        </svg>
                                                    </button>
                                                    <a href="{{ route('orders.download-pdf-bill', $order->id) }}" class="btn btn-white btn-icon btn-sm" title="Download PDF">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                            <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
                                                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/>
                                                            <line x1="9" y1="9" x2="10" y2="9"/>
                                                            <line x1="9" y1="13" x2="15" y2="13"/>
                                                            <line x1="9" y1="17" x2="15" y2="17"/>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty">
                            <div class="empty-img">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="64" height="64" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <circle cx="12" cy="12" r="9"/>
                                    <line x1="9" y1="9" x2="9.01" y2="9"/>
                                    <line x1="15" y1="9" x2="15.01" y2="9"/>
                                    <path d="M8 13a4 4 0 1 0 8 0"/>
                                </svg>
                            </div>
                            <p class="empty-title">No purchases yet</p>
                            <p class="empty-subtitle text-muted">This customer hasn't made any purchases</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page-styles')
<style>
    .page-body {
        padding: 1.5rem 0;
        background-color: #f5f7fb;
    }

    .card {
        border: 1px solid #e3e6f0;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e3e6f0;
        padding: 1.25rem;
    }

    .card-body {
        padding: 1.25rem;
    }

    .card-title {
        font-size: 1.125rem;
        font-weight: 600;
        margin: 0;
    }

    .card-sm {
        display: flex;
        flex-direction: column;
    }

    .form-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
        display: block;
    }

    .avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.375rem;
        flex-shrink: 0;
        color: white;
        font-weight: 600;
    }

    .avatar-lg {
        width: 3rem;
        height: 3rem;
    }

    .table {
        margin-bottom: 0;
    }

    .table thead {
        background-color: #f8f9fa;
    }

    .table th {
        border-bottom: 2px solid #e3e6f0;
        padding: 1rem;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table td {
        padding: 1rem;
        border-bottom: 1px solid #e3e6f0;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .btn-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2rem;
        height: 2rem;
        padding: 0;
        border-radius: 0.25rem;
    }

    @media (max-width: 992px) {
        .col-lg-4,
        .col-lg-8 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }

    @media (max-width: 768px) {
        .table th,
        .table td {
            padding: 0.75rem;
            font-size: 0.85rem;
        }
    }

    @media (max-width: 576px) {
        .table th:nth-child(n+6),
        .table td:nth-child(n+6) {
            display: none;
        }
    }
</style>
@endpush

@push('page-scripts')
<script>
function viewOrderDetails(orderId) {
    const url = "{{ url('/orders') }}" + "/" + orderId + "?ajax=1";
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data && data.success && data.order) {
            showOrderReceiptModal(data.order);
        } else {
            alert('Failed to load order details');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while loading order details');
    });
}

function showOrderReceiptModal(orderData) {
    const modalHtml = `
        <div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Order Receipt - ${orderData.invoice_no}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        ${buildReceiptHtml(orderData)}
                    </div>
                </div>
            </div>
        </div>
    `;

    const existingModal = document.getElementById('orderModal');
    if (existingModal) {
        existingModal.remove();
    }

    document.body.insertAdjacentHTML('beforeend', modalHtml);
    const modal = new bootstrap.Modal(document.getElementById('orderModal'));
    modal.show();
}

function buildReceiptHtml(orderData) {
    let itemsHtml = '';
    if (orderData.details && orderData.details.length > 0) {
        itemsHtml = orderData.details.map(item => `
            <tr>
                <td>${item.product_name}</td>
                <td class="text-end">${item.qty}</td>
                <td class="text-end">LKR ${parseFloat(item.unit_price).toFixed(2)}</td>
                <td class="text-end">LKR ${parseFloat(item.line_total).toFixed(2)}</td>
            </tr>
        `).join('');
    }

    return `
        <div style="font-size: 14px; line-height: 1.6;">
            <div class="mb-3">
                <strong>${orderData.customer_name}</strong><br>
                ${orderData.customer_email || ''}<br>
                ${orderData.customer_phone || ''}
            </div>
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th class="text-end">Qty</th>
                        <th class="text-end">Unit Price</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    ${itemsHtml}
                </tbody>
            </table>
            <div class="d-flex justify-content-end mb-3">
                <div style="width: 300px;">
                    <div class="d-flex justify-content-between py-2 border-top">
                        <strong>Grand Total:</strong>
                        <strong>LKR ${parseFloat(orderData.total).toFixed(2)}</strong>
                    </div>
                </div>
            </div>
        </div>
    `;
}
</script>
@endpush
