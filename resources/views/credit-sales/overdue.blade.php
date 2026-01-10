@extends('layouts.nexora')

@section('title', 'Overdue Credit Sales')

@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <x-alert />

            <!-- Overdue Credit Sales Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="page-title">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-danger" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 9v4" />
                                    <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" />
                                    <path d="M12 16h.01" />
                                </svg>
                                Overdue Credit Sales Report
                            </h1>
                            <p class="text-muted">Credit sales that have passed their due date and require immediate attention</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('credit-sales.index') }}" class="btn btn-outline-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M9 14l-4 -4l4 -4" />
                                    <path d="M5 10h11a4 4 0 1 1 0 8h-1" />
                                </svg>
                                Back to Credit Sales
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Overdue Credit Sales Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title text-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 9v4" />
                                    <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" />
                                    <path d="M12 16h.01" />
                                </svg>
                                Overdue Credit Sales ({{ $overdueSales->total() }} found)
                            </h3>
                        </div>
                        <div class="card-body">
                            @if(safe_count($overdueSales) > 0)
                                <div class="alert alert-warning mb-4">
                                    <div class="d-flex">
                                        <div>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M12 9v4" />
                                                <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" />
                                                <path d="M12 16h.01" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="alert-title">Action Required!</h4>
                                            <div class="text-muted">These credit sales have exceeded their due dates and require immediate follow-up.
                                            Contact customers to arrange payment or settlement plans.</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-vcenter card-table">
                                        <thead>
                                            <tr>
                                                <th>Invoice</th>
                                                <th>Customer</th>
                                                <th>Due Date</th>
                                                <th>Days Overdue</th>
                                                <th>Total Amount</th>
                                                <th>Paid Amount</th>
                                                <th>Outstanding</th>
                                                <th>Status</th>
                                                <th class="w-1">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($overdueSales as $creditSale)
                                                <tr class="table-warning">
                                                    <td>
                                                        <strong>{{ $creditSale->order->invoice_no }}</strong>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <strong>{{ $creditSale->customer->name }}</strong>
                                                            @if($creditSale->customer->phone)
                                                                <br><small class="text-muted">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="14" height="14"
                                                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                                        stroke-linecap="round" stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                        <path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2"/>
                                                                    </svg>
                                                                    {{ $creditSale->customer->phone }}
                                                                </small>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="text-danger fw-bold">
                                                            {{ $creditSale->due_date->format('d/m/Y') }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-danger fs-6 px-3 py-2">
                                                            {{ abs($creditSale->days_overdue) }} days
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="fw-bold">LKR {{ $creditSale->total_amount_formatted }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="text-success fw-bold">LKR {{ $creditSale->paid_amount_formatted }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="text-danger fw-bold fs-5">LKR {{ $creditSale->due_amount_formatted }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $creditSale->status->color() }}">
                                                            {{ $creditSale->status->label() }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-list flex-nowrap">
                                                            <a href="{{ route('credit-sales.show', $creditSale) }}"
                                                               class="btn btn-sm btn-outline-primary" title="View Details">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16"
                                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                                    stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                                    <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                                </svg>
                                                            </a>
                                                            @if($creditSale->customer->phone)
                                                                <a href="tel:{{ $creditSale->customer->phone }}"
                                                                   class="btn btn-sm btn-success" title="Call Customer">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16"
                                                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                                        stroke-linecap="round" stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                        <path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2"/>
                                                                    </svg>
                                                                </a>
                                                            @endif
                                                            @if($creditSale->status !== \App\Enums\CreditStatus::PAID)
                                                                <button type="button"
                                                                        class="btn btn-sm btn-warning"
                                                                        title="Record Payment"
                                                                        onclick="showPaymentModal({{ $creditSale->id }}, '{{ $creditSale->customer->name }}', {{ $creditSale->due_amount }})">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16"
                                                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                                        stroke-linecap="round" stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                        <path d="M9 11l3 3l8 -8" />
                                                                        <path d="M20 12c-.9 4.4 -4.7 8 -9 8c-4.4 0 -8.1 -3.6 -9 -8c.9 -4.4 4.6 -8 9 -8c.4 0 .8 0 1.2 .1" />
                                                                    </svg>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $overdueSales->links() }}
                                </div>
                            @else
                                <div class="empty">
                                    <div class="empty-img">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-success" width="128" height="128"
                                            viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M9 11l3 3l8 -8" />
                                            <path d="M21 12c-1 4.4 -6.1 8 -10 8c-5 0 -10 -3.6 -10 -8s5 -8 10 -8c1.5 0 2.9 .3 4.2 .7" />
                                        </svg>
                                    </div>
                                    <p class="empty-title text-success">Great! No overdue credit sales</p>
                                    <p class="empty-subtitle text-muted">
                                        All credit sales are within their payment terms or have been paid.
                                    </p>
                                    <div class="empty-action">
                                        <a href="{{ route('credit-sales.index') }}" class="btn btn-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M7 9m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" />
                                                <path d="M14 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                <path d="M17 9v-2a2 2 0 0 0 -2 -2h-10a2 2 0 0 0 -2 2v6a2 2 0 0 0 2 2h2" />
                                            </svg>
                                            View All Credit Sales
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Record Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="paymentForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="customerName" class="form-label">Customer</label>
                            <input type="text" class="form-control" id="customerName" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="maxAmount" class="form-label">Maximum Payable Amount</label>
                            <input type="text" class="form-control" id="maxAmount" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="payment_amount" class="form-label">Payment Amount (LKR)</label>
                            <input type="number" class="form-control" id="payment_amount" name="payment_amount"
                                   step="0.01" min="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="Cash">Cash</option>
                                <option value="Card">Card</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes (Optional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"
                                      placeholder="Additional notes for this payment..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn">Record Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('page-scripts')
<script>
function showPaymentModal(creditSaleId, customerName, maxAmount) {
    document.getElementById('customerName').value = customerName;
    document.getElementById('maxAmount').value = 'LKR ' + maxAmount.toFixed(2);
    document.getElementById('payment_amount').max = maxAmount.toFixed(2);

    // Set form action
    document.getElementById('paymentForm').action = '/credit-sales/' + creditSaleId + '/payment';

    // Show modal
    new bootstrap.Modal(document.getElementById('paymentModal')).show();
}

// Validate payment amount
document.getElementById('payment_amount').addEventListener('input', function() {
    const maxAmount = parseFloat(document.getElementById('maxAmount').value.replace('LKR ', ''));
    const enteredAmount = parseFloat(this.value);

    if (enteredAmount > maxAmount) {
        this.setCustomValidity('Payment amount cannot exceed the due amount');
    } else {
        this.setCustomValidity('');
    }
});
</script>
@endpush
