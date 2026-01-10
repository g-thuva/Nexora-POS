@extends('layouts.nexora')

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <x-alert/>

        <form id="editOrderForm" action="{{ route('orders.update', $order) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row row-deck row-cards">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                {{ __('Edit Order') }}
                            </h3>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label required" for="customer_id">
                                            {{ __('Customer') }}
                                        </label>

                                        <div class="input-group">
                                            <select id="customer_id" name="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required>
                                                <option value="">Select a customer</option>
                                                @foreach($customers as $customer)
                                                    <option value="{{ $customer->id }}"
                                                            data-name="{{ $customer->name }}"
                                                            data-email="{{ $customer->email }}"
                                                            data-phone="{{ $customer->phone }}"
                                                            data-address="{{ $customer->address }}"
                                                            @selected(old('customer_id', $order->customer_id) == $customer->id)>
                                                        {{ $customer->name }}@if($customer->phone) - {{ $customer->phone }}@endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="button" class="btn btn-outline-primary" id="editCustomerBtn" title="Edit Customer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                                    <path d="M16 5l3 3"/>
                                                </svg>
                                            </button>
                                        </div>

                                        @error('customer_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label required" for="order_date">
                                            {{ __('Order Date') }}
                                        </label>

                                        <input id="order_date" name="order_date" type="date"
                                               class="form-control @error('order_date') is-invalid @enderror"
                                               value="{{ old('order_date', $order->order_date->format('Y-m-d')) }}" required>

                                        @error('order_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label required" for="payment_type">
                                            {{ __('Payment Type') }}
                                        </label>
                                        <select id="payment_type" name="payment_type" class="form-select @error('payment_type') is-invalid @enderror" required>
                                            <option value="Cash" @selected(old('payment_type', $order->payment_type) == 'Cash')>Cash</option>
                                            <option value="Card" @selected(old('payment_type', $order->payment_type) == 'Card')>Card</option>
                                            <option value="Bank Transfer" @selected(old('payment_type', $order->payment_type) == 'Bank Transfer')>Bank Transfer</option>
                                            <option value="Credit" @selected(old('payment_type', $order->payment_type) == 'Credit')>Credit</option>
                                        </select>
                                        @error('payment_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="discount_amount">
                                            {{ __('Discount Amount') }}
                                        </label>
                                        <input id="discount_amount" name="discount_amount" type="number" step="0.01" min="0"
                                               class="form-control @error('discount_amount') is-invalid @enderror"
                                               value="{{ old('discount_amount', $order->discount_amount ?? 0) }}">
                                        @error('discount_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Products Section -->
                            <div class="mt-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4>Products</h4>
                                    <button type="button" class="btn btn-primary btn-sm" id="addProductBtn">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M12 5l0 14" />
                                            <path d="M5 12l14 0" />
                                        </svg>
                                        Add Product
                                    </button>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered" id="productsTable">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Quantity</th>
                                                <th>Unit Cost</th>
                                                <th>Serial Number</th>
                                                <th>Warranty</th>
                                                <th>Total</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="productRows">
                                            @foreach($order->details as $index => $detail)
                                            <tr class="product-row" data-index="{{ $index }}">
                                                <td>
                                                    <input type="hidden" name="products[{{ $index }}][id]" value="{{ $detail->id }}">
                                                    <select name="products[{{ $index }}][product_id]" class="form-select product-select" required>
                                                        <option value="">Select Product</option>
                                                        @foreach($products as $product)
                                                            <option value="{{ $product->id }}"
                                                                    data-price="{{ $product->selling_price }}"
                                                                    @selected($detail->product_id == $product->id)>
                                                                {{ $product->name }} ({{ $product->code }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" name="products[{{ $index }}][quantity]" class="form-control quantity-input" value="{{ $detail->quantity }}" min="1" required>
                                                </td>
                                                <td>
                                                    <input type="number" name="products[{{ $index }}][unitcost]" class="form-control unitcost-input" value="{{ $detail->unitcost }}" step="0.01" min="0" required>
                                                </td>
                                                <td>
                                                    <input type="text" name="products[{{ $index }}][serial_number]" class="form-control" value="{{ $detail->serial_number }}" placeholder="Serial #">
                                                </td>
                                                <td>
                                                    <select name="products[{{ $index }}][warranty_id]" class="form-select warranty-select">
                                                        <option value="">No Warranty</option>
                                                        @foreach($warranties as $warranty)
                                                            <option value="{{ $warranty->id }}"
                                                                    data-name="{{ $warranty->name }}"
                                                                    data-duration="{{ $warranty->duration }}"
                                                                    @selected($detail->warranty_id == $warranty->id)>
                                                                {{ $warranty->name }} ({{ $warranty->duration }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="number" name="products[{{ $index }}][warranty_years]" class="form-control mt-1 warranty-years-input" value="{{ $detail->warranty_years }}" min="0" placeholder="Or years">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control total-input" value="{{ number_format($detail->total, 2) }}" readonly>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-danger remove-product-btn">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                            <path d="M4 7l16 0" />
                                                            <path d="M10 11l0 6" />
                                                            <path d="M14 11l0 6" />
                                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                {{ __('Update Order') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Order Summary') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <strong>Invoice No:</strong> {{ $order->invoice_no }}
                            </div>
                            <div class="mb-2">
                                <strong>Status:</strong>
                                <span class="badge bg-success">{{ $order->order_status->label() }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Total Products:</strong> <span id="totalProducts">{{ $order->total_products }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Sub Total:</strong> <span id="subTotal">{{ number_format($order->sub_total, 2) }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Discount:</strong> <span id="discountDisplay">{{ number_format($order->discount_amount ?? 0, 2) }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Total Amount:</strong> <span id="totalAmount">{{ number_format($order->total, 2) }}</span>
                            </div>
                            <div>
                                <strong>Created:</strong> {{ $order->created_at->format('M d, Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Edit Customer Modal -->
        <div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCustomerModalLabel">Edit Customer Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editCustomerForm">
                        @csrf
                        <input type="hidden" id="edit_customer_id" name="customer_id">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit_customer_name" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="edit_customer_name" name="name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit_customer_phone" class="form-label">Phone</label>
                                        <input type="text" class="form-control" id="edit_customer_phone" name="phone">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit_customer_email" class="form-label">Email</label>
                                        <input type="text" class="form-control" id="edit_customer_email" name="email">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit_customer_address" class="form-label">Address</label>
                                        <input type="text" class="form-control" id="edit_customer_address" name="address">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="edit_customer_account_holder" class="form-label">Account Holder</label>
                                        <input type="text" class="form-control" id="edit_customer_account_holder" name="account_holder">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="edit_customer_account_number" class="form-label">Account Number</label>
                                        <input type="text" class="form-control" id="edit_customer_account_number" name="account_number">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="edit_customer_bank_name" class="form-label">Bank Name</label>
                                        <input type="text" class="form-control" id="edit_customer_bank_name" name="bank_name">
                                    </div>
                                </div>
                            </div>

                            <div id="customer_error_message" class="alert alert-danger d-none"></div>
                            <div id="customer_success_message" class="alert alert-success d-none"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Customer Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('page-scripts')
<script>
    let productIndex = {{ count($order->details) }};
    const productsData = @json($products);
    const customersData = @json($customers);

    document.addEventListener('DOMContentLoaded', function() {
        const addProductBtn = document.getElementById('addProductBtn');
        const productRows = document.getElementById('productRows');
        const discountInput = document.getElementById('discount_amount');
        const editCustomerBtn = document.getElementById('editCustomerBtn');
        const customerSelect = document.getElementById('customer_id');

        // Verify elements exist
        if (!editCustomerBtn) {
            console.error('Edit customer button not found');
            return;
        }

        if (!customerSelect) {
            console.error('Customer select not found');
            return;
        }

        console.log('Edit customer button initialized');

        // Edit Customer Button Click
        editCustomerBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Edit customer button clicked');

            const selectedCustomerId = customerSelect.value;

            if (!selectedCustomerId) {
                alert('Please select a customer first');
                return;
            }

            console.log('Selected customer ID:', selectedCustomerId);

            // Find customer in data
            const customer = customersData.find(c => c.id == selectedCustomerId);

            console.log('Found customer:', customer);

            if (customer) {
                document.getElementById('edit_customer_id').value = customer.id;
                document.getElementById('edit_customer_name').value = customer.name || '';
                document.getElementById('edit_customer_phone').value = customer.phone || '';
                document.getElementById('edit_customer_email').value = customer.email || '';
                document.getElementById('edit_customer_address').value = customer.address || '';
                document.getElementById('edit_customer_account_holder').value = customer.account_holder || '';
                document.getElementById('edit_customer_account_number').value = customer.account_number || '';
                document.getElementById('edit_customer_bank_name').value = customer.bank_name || '';

                // Hide messages
                document.getElementById('customer_error_message').classList.add('d-none');
                document.getElementById('customer_success_message').classList.add('d-none');

                // Show modal - try multiple methods
                const modalElement = document.getElementById('editCustomerModal');
                if (modalElement) {
                    console.log('Modal element found, attempting to show...');

                    // Method 1: Use Bootstrap 5 Modal API
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        const bsModal = new bootstrap.Modal(modalElement);
                        bsModal.show();
                        console.log('Opened with Bootstrap 5 Modal API');
                    }
                    // Method 2: Use jQuery if Bootstrap JS is loaded via jQuery
                    else if (typeof $ !== 'undefined' && $.fn.modal) {
                        $(modalElement).modal('show');
                        console.log('Opened with jQuery modal');
                    }
                    // Method 3: Direct DOM manipulation
                    else {
                        console.log('Using direct DOM manipulation');
                        modalElement.classList.add('show');
                        modalElement.style.display = 'block';
                        modalElement.setAttribute('aria-modal', 'true');
                        modalElement.setAttribute('role', 'dialog');
                        modalElement.removeAttribute('aria-hidden');

                        // Add backdrop
                        const backdrop = document.createElement('div');
                        backdrop.className = 'modal-backdrop fade show';
                        backdrop.id = 'customModalBackdrop';
                        document.body.appendChild(backdrop);
                        document.body.classList.add('modal-open');

                        console.log('Modal opened with direct DOM manipulation');
                    }
                } else {
                    console.error('Modal element not found');
                    alert('Error: Modal not found in page');
                }
            } else {
                alert('Customer data not found');
            }
        });

        // Handle Customer Form Submission
        document.getElementById('editCustomerForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const customerId = document.getElementById('edit_customer_id').value;
            const formData = new FormData(this);
            const errorMessage = document.getElementById('customer_error_message');
            const successMessage = document.getElementById('customer_success_message');

            // Hide previous messages
            errorMessage.classList.add('d-none');
            successMessage.classList.add('d-none');

            fetch(`/customers/${customerId}/update`, {
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
                    // Update customer in dropdown
                    const option = customerSelect.querySelector(`option[value="${customerId}"]`);
                    if (option) {
                        option.textContent = `${data.customer.name}${data.customer.phone ? ' - ' + data.customer.phone : ''}`;
                        option.dataset.name = data.customer.name;
                        option.dataset.email = data.customer.email || '';
                        option.dataset.phone = data.customer.phone || '';
                        option.dataset.address = data.customer.address || '';
                    }

                    // Update customersData array
                    const customerIndex = customersData.findIndex(c => c.id == customerId);
                    if (customerIndex !== -1) {
                        customersData[customerIndex] = data.customer;
                    }

                    // Show success message
                    successMessage.textContent = data.message;
                    successMessage.classList.remove('d-none');

                    // Close modal after 1.5 seconds
                    setTimeout(() => {
                        closeCustomerModal();
                    }, 1500);
                } else {
                    errorMessage.textContent = data.message || 'An error occurred';
                    errorMessage.classList.remove('d-none');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                errorMessage.textContent = 'An error occurred while updating customer details';
                errorMessage.classList.remove('d-none');
            });
        });

        // Function to close modal
        function closeCustomerModal() {
            const modalElement = document.getElementById('editCustomerModal');

            // Try Bootstrap API first
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                if (modalInstance) {
                    modalInstance.hide();
                }
            } else if (typeof $ !== 'undefined' && $.fn.modal) {
                $(modalElement).modal('hide');
            } else {
                // Direct DOM manipulation
                modalElement.classList.remove('show');
                modalElement.style.display = 'none';
                modalElement.setAttribute('aria-hidden', 'true');
                modalElement.removeAttribute('aria-modal');
                modalElement.removeAttribute('role');

                // Remove backdrop
                const backdrop = document.getElementById('customModalBackdrop');
                if (backdrop) {
                    backdrop.remove();
                }
                document.body.classList.remove('modal-open');
            }
        }

        // Add close button handlers
        document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(btn => {
            btn.addEventListener('click', function() {
                closeCustomerModal();
            });
        });

        // Add new product row
        addProductBtn.addEventListener('click', function() {
            // Check current number of product rows
            const currentRows = productRows.querySelectorAll('.product-row').length;
            if (currentRows >= 11) {
                alert('Maximum 11 different items allowed per order. Please create a new order for additional items to prevent PDF generation issues.');
                return;
            }

            const newRow = createProductRow(productIndex);
            productRows.insertAdjacentHTML('beforeend', newRow);
            productIndex++;
            attachRowEvents();
            calculateTotals();
        });

        // Create product row HTML
        function createProductRow(index) {
            let productOptions = '<option value="">Select Product</option>';
            productsData.forEach(product => {
                productOptions += `<option value="${product.id}" data-price="${product.selling_price}">${product.name} (${product.code})</option>`;
            });

            let warrantyOptions = '<option value="">No Warranty</option>';
            @foreach($warranties as $warranty)
                warrantyOptions += `<option value="{{ $warranty->id }}" data-name="{{ $warranty->name }}" data-duration="{{ $warranty->duration }}">{{ $warranty->name }} ({{ $warranty->duration }})</option>`;
            @endforeach

            return `
                <tr class="product-row" data-index="${index}">
                    <td>
                        <input type="hidden" name="products[${index}][id]" value="">
                        <select name="products[${index}][product_id]" class="form-select product-select" required>
                            ${productOptions}
                        </select>
                    </td>
                    <td>
                        <input type="number" name="products[${index}][quantity]" class="form-control quantity-input" value="1" min="1" required>
                    </td>
                    <td>
                        <input type="number" name="products[${index}][unitcost]" class="form-control unitcost-input" value="0" step="0.01" min="0" required>
                    </td>
                    <td>
                        <input type="text" name="products[${index}][serial_number]" class="form-control" placeholder="Serial #">
                    </td>
                    <td>
                        <select name="products[${index}][warranty_id]" class="form-select warranty-select">
                            ${warrantyOptions}
                        </select>
                        <input type="number" name="products[${index}][warranty_years]" class="form-control mt-1 warranty-years-input" min="0" placeholder="Or years">
                    </td>
                    <td>
                        <input type="text" class="form-control total-input" value="0.00" readonly>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-product-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M4 7l16 0" />
                                <path d="M10 11l0 6" />
                                <path d="M14 11l0 6" />
                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                            </svg>
                        </button>
                    </td>
                </tr>
            `;
        }

        // Attach events to rows
        function attachRowEvents() {
            // Product selection change
            document.querySelectorAll('.product-select').forEach(select => {
                select.removeEventListener('change', handleProductChange);
                select.addEventListener('change', handleProductChange);
            });

            // Quantity/price change
            document.querySelectorAll('.quantity-input, .unitcost-input').forEach(input => {
                input.removeEventListener('input', handleQuantityPriceChange);
                input.addEventListener('input', handleQuantityPriceChange);
            });

            // Remove product
            document.querySelectorAll('.remove-product-btn').forEach(btn => {
                btn.removeEventListener('click', handleRemoveProduct);
                btn.addEventListener('click', handleRemoveProduct);
            });

            // Warranty interactions
            document.querySelectorAll('.warranty-select').forEach(select => {
                select.removeEventListener('change', handleWarrantySelect);
                select.addEventListener('change', handleWarrantySelect);
            });

            document.querySelectorAll('.warranty-years-input').forEach(input => {
                input.removeEventListener('input', handleWarrantyYears);
                input.addEventListener('input', handleWarrantyYears);
            });
        }

        function handleProductChange(e) {
            const row = e.target.closest('.product-row');
            const selectedOption = e.target.options[e.target.selectedIndex];
            const price = selectedOption.dataset.price || 0;
            const unitcostInput = row.querySelector('.unitcost-input');
            unitcostInput.value = price;
            calculateRowTotal(row);
        }

        function handleQuantityPriceChange(e) {
            const row = e.target.closest('.product-row');
            calculateRowTotal(row);
        }

        function handleRemoveProduct(e) {
            if (document.querySelectorAll('.product-row').length > 1) {
                e.target.closest('.product-row').remove();
                calculateTotals();
            } else {
                alert('At least one product is required');
            }
        }

        function handleWarrantySelect(e) {
            const row = e.target.closest('.product-row');
            if (e.target.value) {
                row.querySelector('.warranty-years-input').value = '';
            }
        }

        function handleWarrantyYears(e) {
            const row = e.target.closest('.product-row');
            if (e.target.value) {
                row.querySelector('.warranty-select').value = '';
            }
        }

        function calculateRowTotal(row) {
            const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const unitcost = parseFloat(row.querySelector('.unitcost-input').value) || 0;
            const total = quantity * unitcost;
            row.querySelector('.total-input').value = total.toFixed(2);
            calculateTotals();
        }

        function calculateTotals() {
            let subTotal = 0;
            let totalProducts = 0;

            document.querySelectorAll('.product-row').forEach(row => {
                const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
                const unitcost = parseFloat(row.querySelector('.unitcost-input').value) || 0;
                const total = quantity * unitcost;
                subTotal += total;
                totalProducts += quantity;
            });

            const discount = parseFloat(discountInput.value) || 0;
            const finalTotal = subTotal - discount;

            document.getElementById('totalProducts').textContent = totalProducts;
            document.getElementById('subTotal').textContent = subTotal.toFixed(2);
            document.getElementById('discountDisplay').textContent = discount.toFixed(2);
            document.getElementById('totalAmount').textContent = finalTotal.toFixed(2);
        }

        // Discount change
        discountInput.addEventListener('input', calculateTotals);

        // Initialize events
        attachRowEvents();
        calculateTotals();
    });
</script>
@endpush
@endsection
