@extends('layouts.nexora')

@section('title', 'Credit Sale Details')

@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <x-alert />

            <!-- Credit Sale Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h1 class="page-title">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M7 9m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" />
                                    <path d="M14 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M17 9v-2a2 2 0 0 0 -2 -2h-10a2 2 0 0 0 -2 2v6a2 2 0 0 0 2 2h2" />
                                </svg>
                                Credit Sale Details
                            </h1>
                            <div class="text-muted">
                                Invoice: <strong>{{ $creditSale->order->invoice_no }}</strong> |
                                Customer: <strong>{{ $creditSale->customer->name }}</strong>
                            </div>
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
                                Back to List
                            </a>
                            <a href="{{ route('credit-sales.download-pdf', $creditSale) }}" class="btn btn-danger" title="Download PDF Receipt">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                    <path d="M12 17v-6" />
                                    <path d="M9.5 14.5l2.5 2.5l2.5 -2.5" />
                                </svg>
                                Download PDF
                            </a>
                            @if($creditSale->status !== \App\Enums\CreditStatus::PAID)
                                <button type="button"
                                        class="btn btn-success"
                                        onclick="showPaymentModal({{ $creditSale->id }}, '{{ $creditSale->customer->name }}', {{ $creditSale->due_amount }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M9 11l3 3l8 -8" />
                                        <path d="M20 12c-.9 4.4 -4.7 8 -9 8c-4.4 0 -8.1 -3.6 -9 -8c.9 -4.4 4.6 -8 9 -8c.4 0 .8 0 1.2 .1" />
                                    </svg>
                                    Record Payment
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Credit Sale Summary -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Credit Sale Summary</h3>
                            <div class="card-actions">
                                <span class="badge bg-{{ $creditSale->status->color() }} badge-lg">
                                    {{ $creditSale->status->label() }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Customer Information</h4>
                                    <div class="mb-3">
                                        <strong>{{ $creditSale->customer->name }}</strong>
                                        @if($creditSale->customer->phone)
                                            <br><span class="text-muted">{{ $creditSale->customer->phone }}</span>
                                        @endif
                                        @if($creditSale->customer->email)
                                            <br><span class="text-muted">{{ $creditSale->customer->email }}</span>
                                        @endif
                                        @if($creditSale->customer->address)
                                            <br><span class="text-muted">{{ $creditSale->customer->address }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h4>Credit Terms</h4>
                                    <div class="mb-3">
                                        <div><strong>Sale Date:</strong> {{ $creditSale->sale_date->format('d/m/Y') }}</div>
                                        <div><strong>Due Date:</strong>
                                            <span class="{{ $creditSale->is_overdue ? 'text-danger fw-bold' : '' }}">
                                                {{ $creditSale->due_date->format('d/m/Y') }}
                                            </span>
                                        </div>
                                        <div><strong>Credit Period:</strong> {{ $creditSale->credit_days }} days</div>
                                        @if($creditSale->is_overdue)
                                            <div class="text-danger fw-bold">
                                                <strong>Overdue by:</strong> {{ abs($creditSale->days_overdue) }} days
                                            </div>
                                        @else
                                            <div class="text-muted">
                                                <strong>Days Remaining:</strong> {{ $creditSale->days_overdue }} days
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <h5 class="text-muted">Total Amount</h5>
                                        <h3 class="fw-bold">LKR {{ $creditSale->total_amount_formatted }}</h3>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <h5 class="text-success">Paid Amount</h5>
                                        <h3 class="fw-bold text-success">LKR {{ $creditSale->paid_amount_formatted }}</h3>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <h5 class="text-warning">Due Amount</h5>
                                        <h3 class="fw-bold text-warning">LKR {{ $creditSale->due_amount_formatted }}</h3>
                                    </div>
                                </div>
                            </div>

                            @if($creditSale->notes)
                                <hr>
                                <div>
                                    <h5>Notes</h5>
                                    <p class="text-muted">{{ $creditSale->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Order Details -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Order Details</h3>
                            <div class="card-actions">
                                <a href="{{ route('orders.show', $creditSale->order) }}" class="btn btn-sm btn-outline-primary">
                                    View Full Order
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-vcenter">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($creditSale->order->details as $detail)
                                            <tr>
                                                <td>
                                                    <div>
                                                        <strong>{{ $detail->product->name }}</strong>
                                                        @if($detail->serial_number)
                                                            <br><small class="text-muted">S/N: {{ $detail->serial_number }}</small>
                                                        @endif
                                                        @if($detail->warranty_years)
                                                            <br><small class="text-info">Warranty: {{ $detail->warranty_years }} year(s)</small>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>{{ $detail->quantity }}</td>
                                                <td>LKR {{ number_format($detail->unitcost, 2) }}</td>
                                                <td>LKR {{ number_format($detail->total, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6 offset-md-6">
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Subtotal:</strong></td>
                                            <td class="text-end"><strong>LKR {{ number_format($creditSale->order->sub_total, 2) }}</strong></td>
                                        </tr>
                                        @if($creditSale->order->discount_amount > 0)
                                            <tr>
                                                <td><strong>Discount:</strong></td>
                                                <td class="text-end"><strong>-LKR {{ number_format($creditSale->order->discount_amount, 2) }}</strong></td>
                                            </tr>
                                        @endif
                                        @if($creditSale->order->service_charges > 0)
                                            <tr>
                                                <td><strong>Service Charges:</strong></td>
                                                <td class="text-end"><strong>+LKR {{ number_format($creditSale->order->service_charges, 2) }}</strong></td>
                                            </tr>
                                        @endif
                                        <tr class="fw-bold">
                                            <td><strong>Total:</strong></td>
                                            <td class="text-end"><strong>LKR {{ number_format($creditSale->order->total, 2) }}</strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment History -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Payment History</h3>
                        </div>
                        <div class="card-body">
                            @if(safe_count($creditSale->payments) > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($creditSale->payments as $payment)
                                        <div class="list-group-item px-0">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <div class="fw-bold text-success">
                                                        LKR {{ $payment->payment_amount_formatted }}
                                                    </div>
                                                    <div class="text-muted small">
                                                        {{ $payment->payment_date->format('d/m/Y') }}
                                                    </div>
                                                    <div class="text-muted small">
                                                        Method: {{ $payment->payment_method_label }}
                                                    </div>
                                                    @if($payment->notes)
                                                        <div class="text-muted small mt-1">
                                                            {{ $payment->notes }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="badge bg-success">Paid</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-muted mb-3" width="48" height="48"
                                        viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M7 9m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" />
                                        <path d="M14 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                        <path d="M17 9v-2a2 2 0 0 0 -2 -2h-10a2 2 0 0 0 -2 2v6a2 2 0 0 0 2 2h2" />
                                    </svg>
                                    <p class="text-muted">No payments recorded yet</p>
                                    @if($creditSale->status !== \App\Enums\CreditStatus::PAID)
                                        <button type="button"
                                                class="btn btn-sm btn-success"
                                                onclick="showPaymentModal({{ $creditSale->id }}, '{{ $creditSale->customer->name }}', {{ $creditSale->due_amount }})">
                                            Record First Payment
                                        </button>
                                    @endif
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
                        <button type="submit" class="btn btn-success">Record Payment</button>
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
