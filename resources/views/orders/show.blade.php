@extends('layouts.nexora')

@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">
                            {{ __('Order Details') }}
                        </h3>
                    </div>

                    <div class="card-actions btn-actions">
                        <div class="dropdown">
                            <a href="#" class="btn-action dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><!-- Download SVG icon from http://tabler-icons.io/i/dots-vertical -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path><path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path><path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path></svg>
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" style="">
                                @if ($order->order_status === \App\Enums\OrderStatus::PENDING)
                                    <form action="{{ route('orders.update', $order) }}" method="POST">
                                        @csrf
                                        @method('put')

                                        <button type="submit" class="dropdown-item text-success"
                                                onclick="return confirm('Are you sure you want to approve this order?')"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-nexora icon-nexora-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>

                                            {{ __('Approve Order') }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <x-action.close route="{{ route('orders.index') }}"/>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row row-cards mb-3">
                        <div class="col">
                            <label for="order_date" class="form-label required">
                                {{ __('Order Date') }}
                            </label>
                            <input type="text"
                                   id="order_date"
                                   class="form-control"
                                   value="{{ $order->order_date ? $order->order_date->format('d-m-Y') : 'N/A' }}"
                                   disabled
                            >
                        </div>

                        <div class="col">
                            <label for="invoice_no" class="form-label required">
                                {{ __('Invoice No.') }}
                            </label>
                            <input type="text"
                                   id="invoice_no"
                                   class="form-control"
                                   value="{{ $order->invoice_no }}"
                                   disabled
                            >
                        </div>

                        <div class="col">
                            <label for="customer" class="form-label required">
                                {{ __('Customer') }}
                            </label>
                            <input type="text"
                                   id="customer"
                                   class="form-control"
                                   value="{{ $order->customer->name }}"
                                   disabled
                            >
                        </div>

                        <div class="col">
                            <label for="payment_type" class="form-label required">
                                {{ __('Payment Type') }}
                            </label>

                            <input type="text" id="payment_type" class="form-control" value="{{ $order->payment_type }}" disabled>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered align-middle">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col" class="align-middle text-center">No.</th>
                                <th scope="col" class="align-middle text-center">Photo</th>
                                <th scope="col" class="align-middle text-center">Product Name</th>
                                <th scope="col" class="align-middle text-center">Product Code</th>
                                <th scope="col" class="align-middle text-center">Serial Number</th>
                                <th scope="col" class="align-middle text-center">Warranty</th>
                                <th scope="col" class="align-middle text-center">Quantity</th>
                                <th scope="col" class="align-middle text-center">Price</th>
                                <th scope="col" class="align-middle text-center">Total</th>
                                <th scope="col" class="align-middle text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($order->details as $item)
                                <tr>
                                    <td class="align-middle text-center">
                                        {{ $loop->iteration  }}
                                    </td>
                                    <td class="align-middle text-center">
                                        <div style="max-height: 80px; max-width: 80px;">
                                            <img class="img-fluid"  src="{{ $item->product->product_image ? asset('storage/products/'.$item->product->product_image) : asset('assets/img/products/default.webp') }}">
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        {{ $item->product->name }}
                                    </td>
                                    <td class="align-middle text-center">
                                        {{ $item->product->code }}
                                    </td>
                                    <td class="align-middle text-center">
                                        <span id="serial-{{ $item->id }}">{{ $item->serial_number ?? '-' }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span id="warranty-{{ $item->id }}">
                                            @if($item->warranty_name)
                                                {{ $item->warranty_name }} ({{ $item->warranty_duration }})
                                            @elseif($item->warranty_years)
                                                {{ $item->warranty_years }} years
                                            @else
                                                -
                                            @endif
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="align-middle text-center">
                                        {{ number_format($item->unitcost, 2) }}
                                    </td>
                                    <td class="align-middle text-center">
                                        {{ number_format($item->total, 2) }}
                                    </td>
                                    <td class="align-middle text-center">
                                        <button type="button"
                                                class="btn btn-sm btn-primary edit-item-btn"
                                                data-item-id="{{ $item->id }}"
                                                data-serial="{{ $item->serial_number }}"
                                                data-warranty-id="{{ $item->warranty_id }}"
                                                data-warranty-name="{{ $item->warranty_name }}"
                                                data-warranty-duration="{{ $item->warranty_duration }}"
                                                data-warranty-years="{{ $item->warranty_years }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editItemModal">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                                <path d="M16 5l3 3"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="6" class="text-end">
                                    Payed amount
                                </td>
                                <td class="text-center">{{ number_format($order->pay, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-end">Due</td>
                                <td class="text-center">{{ number_format($order->due, 2) }}</td>
                            </tr>

                            <tr>
                                <td colspan="6" class="text-end">Total</td>
                                <td class="text-center">{{ number_format($order->total, 2) }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('orders.download-pdf-bill', $order) }}" class="btn btn-outline-primary" target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2"/>
                                <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4"/>
                                <path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 -2h-6a2 2 0 0 1 -2 2z"/>
                            </svg>
                            Print PDF Bill
                        </a>
                    </div>

                    @if ($order->order_status === \App\Enums\OrderStatus::PENDING)
                    <div>
                        <form action="{{ route('orders.update', $order) }}" method="POST">
                            @method('put')
                            @csrf

                            <button type="submit"
                                    class="btn btn-primary"
                                    onclick="return confirm('Are you sure you want to complete this order?')"
                            >
                                {{ __('Complete Order') }}
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- Edit Item Modal -->
    <div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editItemModalLabel">Edit Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editItemForm">
                    @csrf
                    <input type="hidden" id="edit_item_id" name="item_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_serial_number" class="form-label">Serial Number</label>
                            <input type="text" class="form-control" id="edit_serial_number" name="serial_number" placeholder="Enter serial number">
                        </div>

                        <div class="mb-3">
                            <label for="edit_warranty_id" class="form-label">Warranty</label>
                            <select class="form-select" id="edit_warranty_id" name="warranty_id">
                                <option value="">No Warranty</option>
                                @foreach($warranties ?? [] as $warranty)
                                    <option value="{{ $warranty->id }}" data-name="{{ $warranty->name }}" data-duration="{{ $warranty->duration }}">
                                        {{ $warranty->name }} ({{ $warranty->duration }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Or enter custom warranty years below</div>
                        </div>

                        <div class="mb-3">
                            <label for="edit_warranty_years" class="form-label">Custom Warranty Years</label>
                            <input type="number" class="form-control" id="edit_warranty_years" name="warranty_years" min="0" placeholder="Enter warranty years">
                        </div>

                        <div id="edit_error_message" class="alert alert-danger d-none"></div>
                        <div id="edit_success_message" class="alert alert-success d-none"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-item-btn');
            const editForm = document.getElementById('editItemForm');
            const editItemId = document.getElementById('edit_item_id');
            const editSerialNumber = document.getElementById('edit_serial_number');
            const editWarrantyId = document.getElementById('edit_warranty_id');
            const editWarrantyYears = document.getElementById('edit_warranty_years');
            const errorMessage = document.getElementById('edit_error_message');
            const successMessage = document.getElementById('edit_success_message');

            // Populate modal with current values
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const itemId = this.dataset.itemId;
                    const serial = this.dataset.serial;
                    const warrantyId = this.dataset.warrantyId;
                    const warrantyYears = this.dataset.warrantyYears;

                    editItemId.value = itemId;
                    editSerialNumber.value = serial || '';
                    editWarrantyId.value = warrantyId || '';
                    editWarrantyYears.value = warrantyYears || '';

                    // Hide messages
                    errorMessage.classList.add('d-none');
                    successMessage.classList.add('d-none');
                });
            });

            // Handle form submission
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const itemId = editItemId.value;
                const formData = new FormData(editForm);

                // Hide previous messages
                errorMessage.classList.add('d-none');
                successMessage.classList.add('d-none');

                fetch(`/orders/items/${itemId}/update`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the display in the table
                        document.getElementById('serial-' + itemId).textContent = data.serial_number || '-';

                        let warrantyText = '-';
                        if (data.warranty_name) {
                            warrantyText = `${data.warranty_name} (${data.warranty_duration})`;
                        } else if (data.warranty_years) {
                            warrantyText = `${data.warranty_years} years`;
                        }
                        document.getElementById('warranty-' + itemId).textContent = warrantyText;

                        // Update button data attributes
                        const button = document.querySelector(`[data-item-id="${itemId}"]`);
                        button.dataset.serial = data.serial_number || '';
                        button.dataset.warrantyId = data.warranty_id || '';
                        button.dataset.warrantyName = data.warranty_name || '';
                        button.dataset.warrantyDuration = data.warranty_duration || '';
                        button.dataset.warrantyYears = data.warranty_years || '';

                        // Show success message
                        successMessage.textContent = data.message;
                        successMessage.classList.remove('d-none');

                        // Close modal after 1.5 seconds
                        setTimeout(() => {
                            const modal = bootstrap.Modal.getInstance(document.getElementById('editItemModal'));
                            modal.hide();
                        }, 1500);
                    } else {
                        errorMessage.textContent = data.message || 'An error occurred';
                        errorMessage.classList.remove('d-none');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    errorMessage.textContent = 'An error occurred while updating the item';
                    errorMessage.classList.remove('d-none');
                });
            });

            // Clear warranty_id when warranty_years is entered
            editWarrantyYears.addEventListener('input', function() {
                if (this.value) {
                    editWarrantyId.value = '';
                }
            });

            // Clear warranty_years when warranty_id is selected
            editWarrantyId.addEventListener('change', function() {
                if (this.value) {
                    editWarrantyYears.value = '';
                }
            });
        });
    </script>
    @endpush
@endsection
