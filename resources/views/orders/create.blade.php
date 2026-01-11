@extends('layouts.nexora')

@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <x-alert />

            @if ($errors->any())
                <div class="alert alert-danger">
                    <h6>Please fix the following errors:</h6>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- POS Header -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                        <h1 class="page-title mb-0">
                            {{ __('Point of Sale') }}
                        </h1>
                        <div class="d-flex flex-wrap gap-2">
                            {{-- Quick actions on POS: Return & Expense --}}
                            <a href="{{ route('returns.create') }}" class="btn btn-white" title="Create Return">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1"/></svg>
                                Return
                            </a>
                            <a href="{{ route('expenses.create') }}" class="btn btn-white" title="Record Expense">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14"/><path d="M5 12l14 0"/></svg>
                                Expense
                            </a>
                            <button type="button" class="btn btn-white" onclick="openQuickStockModalGlobal()" title="Quick Stock Update">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14"/><path d="M5 12l14 0"/></svg>
                                Quick Stock
                            </button>
                            <button type="button" class="btn btn-white" onclick="openQuickProductModal()" title="Add New Product">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="12" r="5"/><path d="M12 1l0 3"/><path d="M12 20l0 3"/><path d="M1 12l3 0"/><path d="M20 12l3 0"/></svg>
                                Quick Product
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <form id="order-form" action="{{ route('orders.store') }}" method="POST">
                @csrf
                <!-- Hidden date field with current date -->
                <input name="date" id="date" type="hidden" value="{{ now()->format('Y-m-d') }}">
                <!-- Hidden reference field with default value -->
                <input name="reference" type="hidden" value="ORDR">

                <div class="row g-3">
                    <!-- LEFT SECTION: Product Search (60%) -->
                    <div class="col-lg-7 col-xl-7 d-flex flex-column">
                        <div class="card flex-fill" style="height: auto; min-height: 400px; max-height: none; display: flex; flex-direction: column;">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3 class="card-title mb-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M4 7v-1a2 2 0 0 1 2 -2h2" />
                                                <path d="M4 17v1a2 2 0 0 0 2 2h2" />
                                                <path d="M16 4h2a2 2 0 0 1 2 2v1" />
                                                <path d="M16 20h2a2 2 0 0 0 2 -2v-1" />
                                                <path d="M8 11l0 .01" />
                                                <path d="M12 11l0 .01" />
                                                <path d="M16 11l0 .01" />
                                                <path d="M8 15l0 .01" />
                                                <path d="M12 15l0 .01" />
                                                <path d="M16 15l0 .01" />
                                            </svg>
                                            Product Search
                                        </h3>
                                    </div>
                                    <div class="text-muted">

                                    </div>
                                </div>
                            </div>
                            <div class="card-body" style="display: flex; flex-direction: column;">
                                <!-- Product Search Section -->
                                <div class="mb-4">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <input type="text" class="form-control form-control-lg"
                                                placeholder="Search products by name, SKU, or scan barcode..."
                                                autocomplete="off">
                                        </div>
                                    </div>
                                </div>

                                <!-- Products Grid -->
                                <div class="flex-1" style="overflow-y: auto; overflow-x: hidden;">
                                    <h4 class="mb-3">Products (<span id="products-count">{{ $products_count ?? safe_count($products) }}</span> items)</h4>
                                    <div id="products-grid" class="row g-2" style="margin: 0;">
                                        @foreach ($products as $product)
                                            <div class="col-6 col-sm-6 col-md-4 col-lg-3" style="padding: 0.25rem;">
                                                <div class="card product-card {{ $product->quantity <= 0 ? 'out-of-stock' : 'cursor-pointer hover-shadow' }}"
                                                    data-product-id="{{ $product->id }}"
                                                    data-id="{{ $product->id }}"
                                                    data-code="{{ $product->code }}"
                                                    data-stock="{{ $product->quantity }}"
                                                    data-warranty-id="{{ $product->warranty_id ?? '' }}"
                                                    data-warranty-name="{{ $product->warranty ? $product->warranty->name : '' }}"
                                                    data-warranty-duration="{{ $product->warranty ? $product->warranty->duration : '' }}"
                                                    data-product-name="{{ $product->name }}"
                                                    data-product-code="{{ $product->code }}"
                                                    style="border: {{ $product->quantity <= 0 ? '2px solid #ef4444' : '1px solid #e9ecef' }};
                                                    border-radius: 8px;
                                                    min-height: 120px;
                                                    height: 100%;
                                                    width: 100%;
                                                    transition: all 0.2s ease;
                                                    {{ $product->quantity <= 0 ? 'opacity: 0.6; cursor: not-allowed;' : '' }}">
                                                    <div class="card-body p-2" style="display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
                                                        <div class="text-start">
                                                            <div class="fw-bold {{ $product->quantity <= 0 ? 'text-muted' : 'text-dark' }}"
                                                                style="font-size: 13px; line-height: 1.3; word-wrap: break-word; min-height: 36px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                                {{ $product->name }}
                                                                @if ($product->quantity <= 0)
                                                                    <small class="text-danger ms-1 d-block">(Out of Stock)</small>
                                                                @endif
                                                            </div>
                                                            @if($product->code)
                                                            <div class="text-muted small" style="font-size: 10px;">
                                                                {{ $product->code }}
                                                            </div>
                                                            @endif
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center gap-1"
                                                            style="flex-wrap: wrap;">
                                                            <span
                                                                class="fw-bold {{ $product->quantity <= 0 ? 'text-muted' : 'text-success' }}"
                                                                style="font-size: 13px; white-space: nowrap;">LKR {{ number_format($product->selling_price, 0) }}</span>
                                                            @if ($product->quantity > 0)
                                                                <span class="badge rounded-pill"
                                                                    style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; font-size: 10px; font-weight: 600; padding: 4px 8px; white-space: nowrap; border-radius: 4px; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1); box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);">
                                                                    {{ $product->quantity }}</span>
                                                            @else
                                                                <span class="badge bg-danger" style="font-size: 9px; padding: 4px 6px; white-space: nowrap;">
                                                                    Out of Stock
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT SECTION: Cart & Customer (40%) -->
                    <div class="col-lg-5 col-xl-5 d-flex flex-column">
                        <div class="card flex-fill" style="height: auto; min-height: 400px; max-height: none; display: flex; flex-direction: column;">
                            <div class="card-header">
                                <h3 class="card-title" id="cart-title">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                        <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                        <path d="M17 17h-11v-14h-2" />
                                        <path d="M6 5l14 1l-1 7h-13" />
                                    </svg>
                                    Cart (<span id="cart-count">0</span>)
                                </h3>
                                <div class="card-actions">
                                    <button type="button" class="btn btn-outline-secondary btn-sm">Clear</button>
                                </div>
                            </div>
                            <div class="card-body p-3" style="display: flex; flex-direction: column;">
                                <!-- Customer Selection -->
                                <div class="mb-4">
                                    <div class="input-group">
                                        <select id="customer_id" name="customer_id"
                                            class="form-select @error('customer_id') is-invalid @enderror">
                                            <option value="">Walk-In Customer</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>
                                                    {{ $customer->name }}@if ($customer->phone)
                                                        - {{ $customer->phone }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-primary btn-icon"
                                            id="addCustomerBtn"
                                            title="Add New Customer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M12 5l0 14" />
                                                <path d="M5 12l14 0" />
                                            </svg>
                                        </button>
                                    </div>
                                    @error('customer_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Cart Items -->
                                <div id="cart-items-wrapper" class="mb-2" style="height: 240px !important; min-height: 400px !important; max-height: 400px !important; flex: 0 0 400px;">
                                    <div class="text-center py-2" id="empty-cart" style="display: align-items: center; justify-content: flex-start; gap: 8px; min-height: 120px; padding-top: 12px; padding-bottom: 12px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-muted mb-3"
                                            width="48" height="48" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                            <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                            <path d="M17 17h-11v-14h-2" />
                                            <path d="M6 5l14 1l-1 7h-13" />
                                        </svg>
                                        <p class="text-muted">Cart is empty</p>
                                    </div>
                                    <!-- Cart items will be dynamically added here -->
                                    <div id="cart-items"
                                        style="display: none; height: 100%; overflow-y: auto; overflow-x: hidden; padding-right: 5px;">
                                        <!-- Dynamic cart items -->
                                    </div>
                                </div>

                                <!-- Discount and Service Charges (shown only when cart has items) -->
                                <div id="cart-adjustments" class="mb-3" style="display: none;">
                                    <div class="row g-2 mb-2">
                                        <div class="col">
                                            <label class="form-label small">Discount (LKR)</label>
                                            <input type="number" id="discount-amount"
                                                class="form-control form-control-sm" step="0.01" min="0"
                                                value="0" placeholder="0.00" name="discount_amount">
                                        </div>
                                        <div class="col">
                                            <label class="form-label small">Service Charges (LKR)</label>
                                            <input type="number" id="service-charges"
                                                class="form-control form-control-sm" step="0.01" min="0"
                                                value="0" placeholder="0.00" name="service_charges">
                                        </div>
                                    </div>
                                </div>

                                <!-- Order Summary -->
                                <div class="border-top pt-3">
                                    <div class="row mb-2">
                                        <div class="col">Subtotal:</div>
                                        <div class="col-auto fw-bold" id="subtotal-amount">LKR 0.00</div>
                                    </div>
                                    <div class="row mb-2" id="discount-row" style="display: none;">
                                        <div class="col text-danger">Discount:</div>
                                        <div class="col-auto fw-bold text-danger" id="discount-display">-LKR 0.00</div>
                                    </div>
                                    <div class="row mb-2" id="service-row" style="display: none;">
                                        <div class="col text-info">Service Charges:</div>
                                        <div class="col-auto fw-bold text-info" id="service-display">+LKR 0.00</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col"><strong>Total:</strong></div>
                                        <div class="col-auto"><strong id="total-amount" class="h4 text-primary">LKR
                                                0.00</strong></div>
                                    </div>

                                    <!-- Payment Method -->
                                    <div class="mb-3">
                                        <label class="form-label">Payment Method</label>
                                        <select class="form-select" name="payment_type" id="payment_type">
                                            <option value="Cash">Cash</option>
                                            <option value="Card">Card</option>
                                            <option value="Bank Transfer">Bank Transfer</option>
                                            <option value="Credit Sales">Credit Sales</option>
                                        </select>
                                    </div>

                                    <!-- Credit Sales Options (Initially Hidden) -->
                                    <div id="credit-sales-section" class="mb-3" style="display: none;">
                                        <div class="alert alert-info">
                                            <strong>Credit Sales:</strong> Customer must be selected for credit transactions.
                                        </div>
                                        <div class="row g-2">
                                            <div class="col">
                                                <label class="form-label small">Credit Days</label>
                                                <select class="form-select form-select-sm" name="credit_days">
                                                    <option value="7">7 Days</option>
                                                    <option value="15">15 Days</option>
                                                    <option value="30" selected>30 Days</option>
                                                    <option value="60">60 Days</option>
                                                    <option value="90">90 Days</option>
                                                </select>
                                            </div>
                                            <div class="col">
                                                <label class="form-label small">Initial Payment (Optional)</label>
                                                <input type="number" class="form-control form-control-sm"
                                                       name="initial_payment" id="initial_payment"
                                                       step="0.01" min="0" placeholder="0.00">
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <label class="form-label small">Credit Notes</label>
                                            <textarea class="form-control form-control-sm" name="credit_notes"
                                                      rows="2" placeholder="Additional notes for credit sale..."></textarea>
                                        </div>
                                    </div>

                                    <script>
                                        // Show/hide amount received field based on payment method
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const paymentTypeSelect = document.querySelector('select[name="payment_type"]');
                                            const paymentAmountSection = document.getElementById('payment-amount-section');
                                            const paymentAmountInput = document.getElementById('payment-amount-input');
                                            const creditSalesSection = document.getElementById('credit-sales-section');
                                            const customerSelect = document.getElementById('customer_id');
                                            const completeBtn = document.getElementById('complete-payment-btn');

                                            // Create feedback element for balance/shortage
                                            let balanceFeedback = document.getElementById('balance-feedback');
                                            if (!balanceFeedback) {
                                                balanceFeedback = document.createElement('div');
                                                balanceFeedback.id = 'balance-feedback';
                                                balanceFeedback.style.marginTop = '0.5rem';
                                                paymentAmountInput && paymentAmountInput.parentNode.appendChild(balanceFeedback);
                                            }

                                            function sanitizeAmount(text) {
                                                if (!text) return 0;
                                                return parseFloat(String(text).replace(/[^0-9.]/g, '')) || 0;
                                            }

                                            function updateBalanceFeedback() {
                                                if (!paymentAmountInput) return;
                                                const enteredAmount = parseFloat(paymentAmountInput.value) || 0;
                                                const totalText = document.getElementById('total-amount')?.textContent || '';
                                                const totalAmount = sanitizeAmount(totalText);
                                                const diff = enteredAmount - totalAmount;

                                                // Reset visual state
                                                paymentAmountInput.classList.remove('is-invalid');
                                                paymentAmountInput.classList.remove('is-valid');
                                                paymentAmountInput.classList.remove('border-danger');
                                                paymentAmountInput.classList.remove('border-info');

                                                // Default: disable when no amount or zero
                                                if (!enteredAmount) {
                                                    balanceFeedback.innerHTML = '';
                                                    if (completeBtn) completeBtn.disabled = true;
                                                    return;
                                                }

                                                if (diff < 0) {
                                                    // Not enough
                                                    balanceFeedback.innerHTML =
                                                        `<span style="color: #dc2626; font-weight: 500;">Insufficient amount: LKR ${Math.abs(diff).toFixed(2)}</span>`;
                                                    paymentAmountInput.classList.add('is-invalid');
                                                    paymentAmountInput.classList.add('border-danger');
                                                    if (completeBtn) completeBtn.disabled = true;
                                                } else if (diff === 0) {
                                                    // Exact amount
                                                    balanceFeedback.innerHTML = '';
                                                    paymentAmountInput.classList.add('is-valid');
                                                    if (completeBtn) completeBtn.disabled = false;
                                                } else {
                                                    // Change to give
                                                    balanceFeedback.innerHTML =
                                                        `<span style="color: #2563eb; font-weight: 500;">Change to give: LKR ${diff.toFixed(2)}</span>`;
                                                    paymentAmountInput.classList.add('border-info');
                                                    if (completeBtn) completeBtn.disabled = false;
                                                }
                                            }

                                            function validateCreditSales() {
                                                const customerId = customerSelect.value;
                                                const isWalkIn = !customerId || customerId === '';

                                                if (isWalkIn) {
                                                    // Show error for walk-in customer
                                                    showToast('Credit sales requires a customer to be selected. Walk-in customers are not allowed for credit transactions.', 'error');
                                                    // Reset to cash payment
                                                    paymentTypeSelect.value = 'Cash';
                                                    paymentTypeSelect.dispatchEvent(new Event('change'));
                                                    return false;
                                                }
                                                return true;
                                            }

                                            if (paymentAmountInput) {
                                                paymentAmountInput.addEventListener('input', updateBalanceFeedback);
                                            }

                                            if (paymentTypeSelect) {
                                                paymentTypeSelect.addEventListener('change', function() {
                                                    if (this.value === 'Cash') {
                                                        paymentAmountSection.style.display = 'block';
                                                        creditSalesSection.style.display = 'none';
                                                        if (completeBtn) completeBtn.disabled = true; // wait for valid amount
                                                        updateBalanceFeedback();
                                                    } else if (this.value === 'Credit Sales') {
                                                        // Check if cart has more than 9 items
                                                        if (typeof cart !== 'undefined' && cart.length > 9) {
                                                            const removedCount = cart.length - 9;
                                                            const removedItems = cart.splice(9).map(item => item.name).join(', ');
                                                            alert(`Credit Sales allows maximum 9 items. Removed last ${removedCount} product(s): ${removedItems}`);
                                                            if (typeof updateCartDisplay === 'function') {
                                                                updateCartDisplay();
                                                            }
                                                        }

                                                        if (validateCreditSales()) {
                                                            paymentAmountSection.style.display = 'none';
                                                            creditSalesSection.style.display = 'block';
                                                            if (balanceFeedback) balanceFeedback.innerHTML = '';
                                                            if (paymentAmountInput) {
                                                                paymentAmountInput.value = '';
                                                                paymentAmountInput.classList.remove('is-invalid', 'is-valid', 'border-danger', 'border-info');
                                                            }
                                                            if (completeBtn) completeBtn.disabled = false;
                                                        }
                                                    } else {
                                                        paymentAmountSection.style.display = 'none';
                                                        creditSalesSection.style.display = 'none';
                                                        if (balanceFeedback) balanceFeedback.innerHTML = '';
                                                        if (paymentAmountInput) {
                                                            paymentAmountInput.value = '';
                                                            paymentAmountInput.classList.remove('is-invalid', 'is-valid', 'border-danger', 'border-info');
                                                        }
                                                        if (completeBtn) completeBtn.disabled = false; // card/bank: allow
                                                    }
                                                });

                                                // Trigger change on load
                                                if (paymentTypeSelect.value === 'Cash') {
                                                    paymentAmountSection.style.display = 'block';
                                                    creditSalesSection.style.display = 'none';
                                                    if (completeBtn) completeBtn.disabled = true;
                                                    updateBalanceFeedback();
                                                } else if (paymentTypeSelect.value === 'Credit Sales') {
                                                    if (validateCreditSales()) {
                                                        paymentAmountSection.style.display = 'none';
                                                        creditSalesSection.style.display = 'block';
                                                        if (completeBtn) completeBtn.disabled = false;
                                                    }
                                                } else {
                                                    paymentAmountSection.style.display = 'none';
                                                    creditSalesSection.style.display = 'none';
                                                    if (balanceFeedback) balanceFeedback.innerHTML = '';
                                                    if (completeBtn) completeBtn.disabled = false;
                                                }
                                            }

                                            // Validate credit sales when customer changes
                                            if (customerSelect) {
                                                customerSelect.addEventListener('change', function() {
                                                    if (paymentTypeSelect.value === 'Credit Sales') {
                                                        validateCreditSales();
                                                    }
                                                });
                                            }
                                        });
                                    </script>

                                    <!-- Amount Received (Initially Hidden) -->
                                    <div class="row g-2 mb-3" id="payment-amount-section" style="display: none;">
                                        <div class="col">
                                            <label class="form-label fw-bold text-primary">Amount (LKR)</label>
                                            <input type="number" id="payment-amount-input"
                                                class="form-control form-control-lg" step="0.01" min="0"
                                                placeholder="0.00" name="pay">
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="row g-2">
                                        <div class="col">
                                            <button type="button" id="complete-payment-btn"
                                                class="btn btn-primary w-100 btn-lg enhanced-primary-btn" onclick="submitOrder()" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border: none; font-weight: 600; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); transition: all 0.3s ease;">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M12 19h-7a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2h4l3 3h7a2 2 0 0 1 2 2v2" />
                                                    <path d="M16 19h6" />
                                                    <path d="M19 16v6" />
                                                </svg>
                                                Complete Payment
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hidden cart data -->
                <input type="hidden" name="cart_items" id="cart-items-input" value="">
            </form>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal modal-lg fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Process Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="payment-processor-container">
                        <!-- Payment processor will be loaded here -->
                        <livewire:payment.payment-processor />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Receipt Modal -->
    <div class="modal fade" id="orderReceiptModal" tabindex="-1" aria-labelledby="orderReceiptModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
                <div class="modal-header" style="background: #f8f9fa; border-radius: 12px 12px 0 0;">
                    <h5 class="modal-title" id="orderReceiptModalLabel" style="font-size: 16px; font-weight: 600; color: #495057;">Order Receipt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="order-receipt-content" class="receipt-container">
                        <div class="text-center p-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted">Loading order details...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Customer Modal -->
    <div class="modal fade" id="createCustomerModal" tabindex="-1" aria-labelledby="createCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createCustomerModalLabel">Add New Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createCustomerForm">
                        @csrf
                        <div class="mb-3">
                            <label for="customer_name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="customer_name" name="name" required>
                            <div class="invalid-feedback" id="name_error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="customer_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="customer_email" name="email">
                            <div class="invalid-feedback" id="email_error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="customer_phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="customer_phone" name="phone">
                            <div class="invalid-feedback" id="phone_error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="customer_address" class="form-label">Address</label>
                            <textarea class="form-control" id="customer_address" name="address" rows="2"></textarea>
                            <div class="invalid-feedback" id="address_error"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="submitCreateCustomer()">
                        <span id="createCustomerBtnText">Create Customer</span>
                        <span id="createCustomerSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Global Quick Stock Modal -->
    <div class="modal fade" id="globalQuickStockModal" tabindex="-1" aria-labelledby="globalQuickStockModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="globalQuickStockModalLabel">Quick Stock Update</h5>
                    <button type="button" class="btn-close" onclick="window.closeQuickStockModal()" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="globalQuickStockForm">
                        @csrf
                        <div class="mb-3">
                            <label for="global_stock_product" class="form-label">Select Product <span class="text-danger">*</span></label>
                            <select class="form-select" id="global_stock_product" name="product_id" required>
                                <option value="">Search and select product...</option>
                            </select>
                            <div class="invalid-feedback" id="global_product_error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="global_stock_quantity" class="form-label">Quantity to Add <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="global_stock_quantity" name="quantity" min="1" required>
                            <div class="invalid-feedback" id="global_quantity_error"></div>
                        </div>
                        <div class="alert alert-danger d-none" id="global_stock_error"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" onclick="window.closeQuickStockModal()">Cancel</button>
                    <button type="button" class="btn btn-primary" id="global-update-stock-btn" onclick="window.submitGlobalQuickStock()">
                        <span id="globalStockBtnText">Update Stock</span>
                        <span id="globalStockSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stock Update Modal -->
    <div class="modal fade" id="quickStockModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold" id="stock-product-name"></label>
                        <p class="text-muted small" id="stock-product-code"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Add Quantity</label>
                        <input type="number" class="form-control" id="stock-quantity-input" min="1" value="1" placeholder="Enter quantity to add">
                    </div>
                    <div id="stock-update-error" class="alert alert-danger d-none"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="update-stock-btn">
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        Add Stock
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Product Modal -->
    <div class="modal fade" id="quickProductModal" tabindex="-1" aria-labelledby="quickProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="quickProductModalLabel">Quick Add Product</h5>
                    <button type="button" class="btn-close" onclick="closeQuickProductModal()" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="quickProductForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="qp_name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="qp_name" name="name" required>
                                    <div class="invalid-feedback" id="qp_name_error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="qp_category" class="form-label">Category <span class="text-muted">(Optional)</span></label>
                                    <select class="form-select" id="qp_category" name="category_id">
                                        <option value="">-- No Category --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="qp_unit" class="form-label">Unit <span class="text-danger">*</span></label>
                                    <select class="form-select" id="qp_unit" name="unit_id" required>
                                        @php
                                            $pieceUnit = $units->firstWhere('slug', 'piece');
                                            $otherUnits = $units->where('slug', '!=', 'piece');
                                        @endphp
                                        @if($pieceUnit)
                                            <option value="{{ $pieceUnit->id }}" selected>{{ $pieceUnit->name }}</option>
                                        @endif
                                        @foreach($otherUnits as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="qp_unit_error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="qp_buying_price" class="form-label">Buying Price</label>
                                    <input type="number" class="form-control" id="qp_buying_price" name="buying_price" step="0.01" min="0" placeholder="0">
                                    <div class="invalid-feedback" id="qp_buying_price_error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="qp_selling_price" class="form-label">Selling Price</label>
                                    <input type="number" class="form-control" id="qp_selling_price" name="selling_price" step="0.01" min="0" placeholder="0">
                                    <div class="invalid-feedback" id="qp_selling_price_error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="qp_quantity" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" id="qp_quantity" name="quantity" min="1" value="1">
                                    <div class="invalid-feedback" id="qp_quantity_error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="qp_stock_alert" class="form-label">Stock Alert</label>
                                    <input type="number" class="form-control" id="qp_stock_alert" name="quantity_alert" min="0" placeholder="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="qp_warranty" class="form-label">Warranty <span class="text-muted">(Optional)</span></label>
                                    <select class="form-select" id="qp_warranty" name="warranty_id">
                                        <option value="">No warranty</option>
                                        @foreach($warranties as $warranty)
                                            <option value="{{ $warranty->id }}" {{ $warranty->slug == '3-years' ? 'selected' : '' }}>
                                                {{ $warranty->name }} @if($warranty->duration)({{ $warranty->duration }})@endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="qp_notes" class="form-label">Notes <span class="text-muted">(Optional)</span></label>
                                    <textarea class="form-control" id="qp_notes" name="notes" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-danger d-none" id="qp_error_alert"></div>
                        <div class="alert alert-success d-none" id="qp_success_alert"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" onclick="closeQuickProductModal()">Cancel</button>
                    <button type="button" class="btn btn-primary" id="quick-product-btn" onclick="submitQuickProduct()">
                        <span id="qpBtnText">Add Product</span>
                        <span id="qpSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('page-styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <style>
        /* Enhanced POS Preloader - Keep content hidden until fully loaded */
        .page-body {
            opacity: 0 !important;
            visibility: hidden;
            transition: opacity 0.4s ease-in, visibility 0s 0.4s;
        }

        body:not(.loading) .page-body {
            opacity: 1 !important;
            visibility: visible;
            transition: opacity 0.4s ease-in, visibility 0s;
        }

        /* Prevent flash of unstyled content */
        .container-fluid {
            height: auto;
        }

        /* Reserve space for major sections to prevent layout shift */
        #products-grid {
            min-height: 600px;
        }

        #cart-items {
            height: 100% !important;
            max-height: 100% !important;
            min-height: 0 !important;
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* Critical CSS - Prevent layout shifts */
        * {
            box-sizing: border-box;
        }

        /* Prevent font loading shifts */
        body {
            font-display: swap;
        }

        /* Prevent layout shifts - Fixed dimensions */
        .icon {
            width: 18px !important;
            height: 18px !important;
            display: inline-block;
            flex-shrink: 0;
            min-width: 18px;
            min-height: 18px;
        }

        svg.icon {
            width: 18px !important;
            height: 18px !important;
            flex-shrink: 0;
            vertical-align: middle;
        }

        .btn {
            min-height: 38px !important;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            contain: layout style;
            padding: 0.375rem 0.75rem;
        }

        .btn .icon {
            margin-right: 0.375rem;
            width: 18px !important;
            height: 18px !important;
        }

        .btn-sm {
            min-height: 32px !important;
            height: 32px;
        }

        /* Prevent cart layout shifts */
        .card {
            min-height: 200px;
            contain: layout;
            width: 100%;
        }

        .card-header {
            min-height: 60px;
            height: 60px;
            display: flex;
            align-items: center;
        }

        .card-body {
            min-height: 100px;
            padding: 1rem !important;
        }

        /* Prevent cart scrollable area from shifting */
        .overflow-auto {
            min-height: 0;
            max-height: none;
        }

        #cart-items {
            height: 100% !important;
            max-height: 100% !important;
            min-height: 0 !important;
            contain: layout;
            overflow-y: auto;
            overflow-x: hidden;
            padding-bottom: 0 !important;
        }

        #cart-items {
            height: 100% !important;
            max-height: 100% !important;
            min-height: 0 !important;
            contain: layout;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .cart-title {
            line-height: 1.5;
            min-height: 24px;
        }

        /* Enhanced cart-item with fixed dimensions to prevent sizing shifts */
        .cart-item {
            border-bottom: 1px solid #e9ecef;
            padding: 10px !important;
            min-height: 90px !important;
            height: auto;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            box-sizing: border-box;
            margin-bottom: 8px;
            background: #f8f9fa;
            border-radius: 6px;
            border: 1px solid #dee2e6;
            position: relative;
            contain: layout style;
            width: 100%;
        }

        /* Fixed dimensions for cart item children */
        .cart-item .row {
            min-height: 68px;
        }

        .cart-item .form-control,
        .cart-item .form-select {
            min-height: 32px !important;
            height: 32px !important;
            max-height: 32px;
        }

        .cart-item input[type="number"] {
            min-width: 60px !important;
            width: 60px !important;
        }

        .cart-item .btn-sm {
            min-width: 32px !important;
            min-height: 32px !important;
            width: 32px !important;
            height: 32px !important;
        }

        .cart-item * {
            flex-shrink: 0;
        }

        .cart-item .d-flex {
            flex-shrink: 1;
        }

        /* Ensure cart item text elements don't cause shifts */
        .cart-item h6,
        .cart-item .text-muted,
        .cart-item label {
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        /* Prevent product card shifts */
        .product-card {
            min-height: 120px !important;
            max-height: 150px !important;
            height: 120px;
            transition: all 0.2s ease-in-out;
            background: white;
            contain: layout style;
            overflow: hidden;
        }

        .product-card img {
            aspect-ratio: 1/1;
            object-fit: cover;
            width: 100%;
            height: auto;
        }

        /* Quantity button fixed dimensions */
        .quantity-btn {
            width: 32px !important;
            height: 32px !important;
            min-width: 32px;
            min-height: 32px;
            max-width: 32px;
            max-height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #ddd;
            background: white;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.15s;
            flex-shrink: 0;
            contain: layout;
        }

        .quantity-btn:hover {
            background: #f8f9fa;
            border-color: #3b82f6;
        }

        /* Input fixed dimensions */
        .quantity-input {
            width: 60px !important;
            height: 32px !important;
            min-width: 60px;
            min-height: 32px;
            max-width: 60px;
            max-height: 32px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 0;
            font-size: 14px;
            flex-shrink: 0;
            contain: layout;
        }

        /* Avatar fixed dimensions */
        .avatar {
            width: 2.5rem !important;
            height: 2.5rem !important;
            min-width: 2.5rem;
            min-height: 2.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            contain: layout;
        }

        .avatar-sm {
            width: 1.75rem !important;
            height: 1.75rem !important;
            min-width: 1.75rem;
            min-height: 1.75rem;
        }

        /* Badge fixed dimensions */
        .badge {
            display: inline-flex;
            align-items: center;
            min-height: 20px;
            padding: 0.25rem 0.5rem;
        }

        /* Form control stability */
        .form-control,
        .form-select {
            min-height: 38px !important;
            height: 38px;
        }

        .form-control:focus,
        .form-select:focus {
            outline: none;
        }

        /* Prevent input placeholder shifts */
        input::placeholder,
        textarea::placeholder {
            opacity: 0.6;
        }

        /* Empty state fixed dimensions */
        .empty-cart {
            min-height: 300px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .cart-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .quantity-btn {
            width: 32px;
            height: 32px;
            padding: 0;
            font-size: 14px;
        }

        /* Ensure cart items don't overlap */
        #cart-items .cart-item:not(:last-child) {
            margin-bottom: 12px;
        }

        /* Better form control styling in cart */
        .cart-item .form-control,
        .cart-item .form-select {
            border: 1px solid #ced4da;
            border-radius: 4px;
        }

        .cart-item .form-control:focus,
        .cart-item .form-select:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .overflow-auto {
            max-height: 400px;
        }

        .flex-1 {
            flex: 1;
        }

        .btn-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Prevent horizontal scrollbar in product section */
        .product-card {
            max-width: 100%;
            box-sizing: border-box;
        }

        .card-body {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        /* Make the POS layout responsive */
        @media (max-width: 991px) {

            .col-lg-7,
            .col-lg-5 {
                margin-bottom: 1rem;
            }
        }

        /* Product grid responsiveness */
        .product-card {
            min-height: 120px;
            background: white;
        }

        /* Disable hover lift to avoid any perceived size change on selection */
        .product-card:hover {
            transform: none !important;
            box-shadow: none !important;
            border-color: inherit !important;
        }

        /* Cart item styling */
        .cart-item-name {
            font-weight: 600;
            color: #1e293b;
        }

        .cart-item-price {
            color: #059669;
            font-weight: 600;
        }

        /* Customer dropdown styling */
        .ts-dropdown .option {
            padding: 10px 12px;
            border-bottom: 1px solid #f1f5f9;
        }

        .ts-dropdown .option:last-child {
            border-bottom: none;
        }

        .ts-dropdown .option:hover {
            background-color: #f8fafc;
        }

        .ts-dropdown .option.active {
            background-color: #e0f2fe;
        }

        .ts-control {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
        }

        .ts-control.focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Modal fixed dimensions to prevent shifts */
        .modal-dialog {
            min-height: 200px;
            contain: layout;
        }

        .modal-content {
            min-height: 150px;
            contain: layout;
        }

        .modal-body {
            min-height: 100px;
        }

        .modal-header {
            min-height: 60px;
            display: flex;
            align-items: center;
        }

        .modal-footer {
            min-height: 60px;
            display: flex;
            align-items: center;
        }

        /* Loading spinner fixed dimensions */
        .spinner-border {
            width: 2rem !important;
            height: 2rem !important;
            min-width: 2rem;
            min-height: 2rem;
            flex-shrink: 0;
            contain: layout;
        }

        .spinner-border-sm {
            width: 1rem !important;
            height: 1rem !important;
            min-width: 1rem;
            min-height: 1rem;
            flex-shrink: 0;
        }

        /* Receipt modal styles removed (cleanup) */

        /* Order Receipt Modal Styles */
        #orderReceiptModal .receipt-container {
            position: relative;
            padding: 20px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }

        #orderReceiptModal .company-logo {
            width: 50px;
            height: 50px;
            background: #3b82f6;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
            margin: 0 auto 10px;
        }

        #orderReceiptModal .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        #orderReceiptModal .company-address {
            font-size: 11px;
            color: #666;
            margin-bottom: 2px;
        }

        #orderReceiptModal .receipt-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        #orderReceiptModal .receipt-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #ccc;
        }

        #orderReceiptModal .customer-section {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #ccc;
        }

        #orderReceiptModal .customer-title {
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 13px;
        }

        #orderReceiptModal .customer-info div {
            margin-bottom: 3px;
            font-size: 11px;
        }

        #orderReceiptModal .items-section {
            margin-bottom: 20px;
        }

        #orderReceiptModal .item-container {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px dotted #ddd;
        }

        #orderReceiptModal .item-container:last-child {
            border-bottom: none;
        }

        #orderReceiptModal .item-main-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            margin-bottom: 5px;
        }

        #orderReceiptModal .item-number {
            font-weight: bold;
            min-width: 20px;
        }

        #orderReceiptModal .item-name {
            flex: 1;
            margin-left: 5px;
            margin-right: 10px;
            font-weight: bold;
        }

        #orderReceiptModal .item-qty {
            min-width: 60px;
            text-align: center;
        }

        #orderReceiptModal .item-price {
            min-width: 80px;
            text-align: right;
        }

        #orderReceiptModal .item-total {
            min-width: 90px;
            text-align: right;
            font-weight: bold;
        }

        #orderReceiptModal .item-details-row {
            margin-left: 25px;
            font-size: 10px;
            color: #666;
            line-height: 1.3;
        }

        #orderReceiptModal .serial-info {
            margin-bottom: 2px;
        }

        #orderReceiptModal .warranty-info {
            color: #28a745;
            font-weight: bold;
        }

        #orderReceiptModal .totals-section {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px dashed #ccc;
        }

        #orderReceiptModal .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 12px;
        }

        #orderReceiptModal .total-row.final {
            font-weight: bold;
            font-size: 14px;
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px solid #333;
        }

        #orderReceiptModal .print-actions {
            margin-top: 20px;
            text-align: center;
            padding-top: 15px;
            border-top: 1px dashed #ccc;
        }

        #orderReceiptModal .print-btn {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            margin-right: 10px;
        }

        #orderReceiptModal .print-btn:hover {
            background: #2563eb;
        }

        #orderReceiptModal .pdf-btn {
            background: #dc2626;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            margin-right: 10px;
        }

        #orderReceiptModal .pdf-btn:hover {
            background: #b91c1c;
        }
    </style>
@endpush

@push('page-scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

    <script>
        // Cache buster: v2025-11-21-001
        // Function to show order receipt modal
        function showOrderReceiptModal(orderData) {
            console.log('showOrderReceiptModal called with:', orderData);

            if (!orderData) {
                console.error('No order data provided to modal');
                alert('Error: No order data available');
                return;
            }

            window.currentOrderData = orderData;

            const customer = orderData.customer;
            const invoiceNo = orderData.invoice_no;
            const dateTime = orderData.order_date;
            const items = orderData.details || orderData.items;
            const subtotal = orderData.sub_total || orderData.subtotal;
            const discount = (orderData.discount || orderData.discount_amount || 0);
            const serviceCharges = (orderData.service_charges || 0);
            const total = orderData.total;

            let receiptHTML = '';
            receiptHTML += `
        <div class="receipt-header">
            <div class="company-logo">{{ $shop ? strtoupper(substr($shop->name, 0, 1)) : 'S' }}</div>
            <div class="company-name">{{ $shop ? $shop->name : 'Shop Name' }}</div>
            <div class="company-address">{{ $shop ? $shop->address : 'Shop Address' }}</div>
            <div class="company-address">{{ $shop ? $shop->phone : 'Phone' }} | {{ $shop ? $shop->email : 'Email' }}</div>
        </div>

        <div class="receipt-info">
            <div>
                <strong>Receipt #:</strong><br>
                <strong>Date:</strong>
            </div>
            <div>
                ${invoiceNo}<br>
                ${new Date(dateTime).toLocaleDateString()}
            </div>
        </div>

        <div class="customer-section">
            <div class="customer-title">Customer Details</div>
            <div class="customer-info">
                <div><strong>Name:</strong> ${customer.name}</div>
`;

            if (customer.phone) {
                receiptHTML += `                <div><strong>Phone:</strong> ${customer.phone}</div>\n`;
            }
            if (customer.email) {
                receiptHTML += `                <div><strong>Email:</strong> ${customer.email}</div>\n`;
            }

            receiptHTML += `            </div>
        </div>

        <div class="items-section">
`;

            for (let i = 0; i < items.length; i++) {
                const item = items[i];
                const unitPrice = Number(item.unitcost || item.price).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                const lineTotal = Number(item.total).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

                receiptHTML += `            <div class="item-container">
                <div class="item-main-row">
                    <span class="item-number">${i + 1}.</span>
                    <span class="item-name">${item.product && item.product.name ? item.product.name : item.name}</span>
                    <span class="item-qty">Qty: ${item.quantity}</span>
                    <span class="item-price">LKR ${unitPrice}</span>
                    <span class="item-total">LKR ${lineTotal}</span>
                </div>
`;

                const hasSerial = item.serial_number || (item.product && item.product.serial_number);
                const warrantyYears = item.warranty_years || (item.product && item.product.warranty_years) ? Number(item.warranty_years || item.product.warranty_years) : 0;
                if (hasSerial || warrantyYears > 0) {
                    receiptHTML += `                <div class="item-details-row">\n`;
                    if (hasSerial) {
                        receiptHTML += `                    <div class="serial-info">Serial No: ${item.serial_number || item.product.serial_number}</div>\n`;
                    }
                    if (warrantyYears > 0) {
                        receiptHTML += `                    <div class="warranty-info">Warranty: ${warrantyYears} ${warrantyYears === 1 ? 'year' : 'years'}</div>\n`;
                    }
                    receiptHTML += `                </div>\n`;
                }

                receiptHTML += `            </div>\n`;
            }

            receiptHTML += `        </div>

        <div class="totals-section">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>LKR ${Number(subtotal).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
            </div>
`;

            if (discount && discount > 0) {
                receiptHTML += `            <div class="total-row">
                <span>Discount:</span>
                <span>-LKR ${Number(discount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
            </div>
`;
            }
            if (serviceCharges && serviceCharges > 0) {
                receiptHTML += `            <div class="total-row">
                <span>Service Charges:</span>
                <span>+LKR ${Number(serviceCharges).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
            </div>
`;
            }

            receiptHTML += `            <div class="total-row final">
                <span>TOTAL:</span>
                <span>LKR ${Number(total).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
            </div>
        </div>

        <div class="print-actions">
            <button class="print-btn" onclick="handlePrintReceipt()">🖨️ Print Receipt</button>
            <button class="pdf-btn" onclick="if(window.currentOrderData && window.currentOrderData.id) { downloadPdfReliably(window.currentOrderData.id, window.currentOrderData.invoice_no); } else { alert('Order information not available'); }">📄 Download PDF</button>
        </div>
`;

            document.getElementById('order-receipt-content').innerHTML = receiptHTML;

            // Show modal with multiple fallback methods
            const modalEl = document.getElementById('orderReceiptModal');
            console.log('Attempting to show modal, Bootstrap available:', typeof bootstrap !== 'undefined');

            try {
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    const modal = new bootstrap.Modal(modalEl, {
                        backdrop: 'static',
                        keyboard: true
                    });
                    modal.show();
                    console.log('Modal shown using Bootstrap');
                } else if (typeof $ !== 'undefined' && $.fn.modal) {
                    $(modalEl).modal('show');
                    console.log('Modal shown using jQuery');
                } else {
                    // Fallback: manually add classes
                    modalEl.classList.add('show');
                    modalEl.style.display = 'block';
                    modalEl.setAttribute('aria-modal', 'true');
                    modalEl.removeAttribute('aria-hidden');

                    const backdrop = document.createElement('div');
                    backdrop.className = 'modal-backdrop fade show';
                    backdrop.id = 'orderReceiptModalBackdrop';
                    document.body.appendChild(backdrop);
                    document.body.classList.add('modal-open');
                    console.log('Modal shown using manual method');
                }
            } catch (error) {
                console.error('Error showing modal:', error);
                alert('Order created successfully! Invoice: ' + invoiceNo);
            }
        }

        // Check Bootstrap availability and provide debug info
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Bootstrap available:', typeof bootstrap !== 'undefined');
            console.log('jQuery available:', typeof $ !== 'undefined');
            if (typeof bootstrap !== 'undefined') {
                console.log('Bootstrap version:', bootstrap);
            }
            if (typeof $ !== 'undefined' && $.fn.modal) {
                console.log('jQuery modal available');
            }

            // Add close button handlers for modal
            const modalEl = document.getElementById('orderReceiptModal');
            if (modalEl) {
                const closeButtons = modalEl.querySelectorAll('[data-bs-dismiss="modal"]');
                closeButtons.forEach(btn => {
                    btn.addEventListener('click', function() {
                        // Close using Bootstrap if available
                        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                            const modal = bootstrap.Modal.getInstance(modalEl);
                            if (modal) modal.hide();
                        } else if (typeof $ !== 'undefined' && $.fn.modal) {
                            $(modalEl).modal('hide');
                        } else {
                            // Manual close
                            modalEl.classList.remove('show');
                            modalEl.style.display = 'none';
                            modalEl.removeAttribute('aria-modal');
                            modalEl.setAttribute('aria-hidden', 'true');
                            const backdrop = document.getElementById('orderReceiptModalBackdrop');
                            if (backdrop) backdrop.remove();
                            document.body.classList.remove('modal-open');
                        }
                    });
                });

                // Add ESC key handler for modal
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' || e.keyCode === 27) {
                        // Check if modal is currently shown
                        if (modalEl.classList.contains('show') || modalEl.style.display === 'block') {
                            e.preventDefault();
                            console.log('ESC pressed - closing modal');

                            // Close using Bootstrap if available
                            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                                const modal = bootstrap.Modal.getInstance(modalEl);
                                if (modal) {
                                    modal.hide();
                                }
                            } else if (typeof $ !== 'undefined' && $.fn.modal) {
                                $(modalEl).modal('hide');
                            } else {
                                // Manual close
                                modalEl.classList.remove('show');
                                modalEl.style.display = 'none';
                                modalEl.removeAttribute('aria-modal');
                                modalEl.setAttribute('aria-hidden', 'true');
                                const backdrop = document.getElementById('orderReceiptModalBackdrop');
                                if (backdrop) backdrop.remove();
                                document.body.classList.remove('modal-open');
                            }
                        }
                    }
                });
            }
        });

        // Function to get current date and time
        function getCurrentDateTime() {
            const now = new Date();

            // Get current date in YYYY-MM-DD format
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const currentDate = `${year}-${month}-${day}`;

            // Get current time in HH:MM:SS format
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const currentTime = `${hours}:${minutes}:${seconds}`;

            // Get current datetime in ISO format (YYYY-MM-DDTHH:MM:SS)
            const currentDateTime = `${currentDate}T${currentTime}`;

            console.log('Current Date:', currentDate);
            console.log('Current Time:', currentTime);
            console.log('Current DateTime:', currentDateTime);

            return {
                date: currentDate,
                time: currentTime,
                datetime: currentDateTime,
                timestamp: now.getTime(),
                formatted: now.toLocaleString()
            };
        }

        // Open create customer modal
        function openCreateCustomerModal() {
            console.log('Opening create customer modal...');

            try {
                // Reset form
                const form = document.getElementById('createCustomerForm');
                if (!form) {
                    console.error('Create customer form not found!');
                    alert('Error: Customer form not found. Please refresh the page.');
                    return;
                }
                form.reset();

                // Clear errors
                document.querySelectorAll('#createCustomerModal .invalid-feedback').forEach(el => el.textContent = '');
                document.querySelectorAll('#createCustomerModal .form-control').forEach(el => el.classList.remove('is-invalid'));

                // Show modal
                const modalEl = document.getElementById('createCustomerModal');
                if (!modalEl) {
                    console.error('Create customer modal element not found!');
                    alert('Error: Modal element not found. Please refresh the page.');
                    return;
                }

                // Try multiple methods to show modal
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();
                    console.log('Modal shown using Bootstrap');
                } else if (typeof $ !== 'undefined' && $.fn.modal) {
                    $(modalEl).modal('show');
                    console.log('Modal shown using jQuery');
                } else {
                    // Manual fallback - add classes and backdrop
                    console.log('Using manual modal method');
                    modalEl.classList.add('show');
                    modalEl.style.display = 'block';
                    modalEl.setAttribute('aria-modal', 'true');
                    modalEl.removeAttribute('aria-hidden');

                    // Add backdrop
                    const backdrop = document.createElement('div');
                    backdrop.className = 'modal-backdrop fade show';
                    backdrop.id = 'createCustomerModalBackdrop';
                    document.body.appendChild(backdrop);
                    document.body.classList.add('modal-open');

                    // Add close handlers
                    const closeButtons = modalEl.querySelectorAll('[data-bs-dismiss="modal"]');
                    closeButtons.forEach(btn => {
                        btn.onclick = function() {
                            modalEl.classList.remove('show');
                            modalEl.style.display = 'none';
                            modalEl.removeAttribute('aria-modal');
                            modalEl.setAttribute('aria-hidden', 'true');
                            const backdrop = document.getElementById('createCustomerModalBackdrop');
                            if (backdrop) backdrop.remove();
                            document.body.classList.remove('modal-open');
                        };
                    });

                    console.log('Modal shown using manual method');
                }
            } catch (error) {
                console.error('Error opening modal:', error);
                alert('Error opening customer form: ' + error.message);
            }
        }

        // Submit create customer form
        function submitCreateCustomer() {
            const form = document.getElementById('createCustomerForm');
            const formData = new FormData(form);
            const btn = document.querySelector('#createCustomerModal .btn-primary');
            const btnText = document.getElementById('createCustomerBtnText');
            const spinner = document.getElementById('createCustomerSpinner');

            // Clear previous errors
            document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
            document.querySelectorAll('.form-control').forEach(el => el.classList.remove('is-invalid'));

            // Show loading
            btn.disabled = true;
            btnText.classList.add('d-none');
            spinner.classList.remove('d-none');

            fetch('{{ route("customers.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal using multiple methods
                    const modalEl = document.getElementById('createCustomerModal');
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();
                    } else if (typeof $ !== 'undefined' && $.fn.modal) {
                        $(modalEl).modal('hide');
                    } else {
                        // Manual close
                        modalEl.classList.remove('show');
                        modalEl.style.display = 'none';
                        modalEl.removeAttribute('aria-modal');
                        modalEl.setAttribute('aria-hidden', 'true');
                        const backdrop = document.getElementById('createCustomerModalBackdrop');
                        if (backdrop) backdrop.remove();
                        document.body.classList.remove('modal-open');
                    }

                    // Add new customer to select dropdown
                    const customerSelect = document.getElementById('customer_id');
                    const tomSelect = customerSelect.tomselect;

                    if (tomSelect) {
                        tomSelect.addOption({
                            value: data.customer.id,
                            text: data.customer.name + (data.customer.phone ? ' - ' + data.customer.phone : '')
                        });
                        tomSelect.setValue(data.customer.id);
                    } else {
                        const option = new Option(
                            data.customer.name + (data.customer.phone ? ' - ' + data.customer.phone : ''),
                            data.customer.id,
                            true,
                            true
                        );
                        customerSelect.add(option);
                    }

                    // Show success message
                    showSuccessNotification('Customer created successfully!');
                } else {
                    // Show validation errors
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const errorDiv = document.getElementById(field + '_error');
                            const input = document.getElementById('customer_' + field);
                            if (errorDiv && input) {
                                errorDiv.textContent = data.errors[field][0];
                                input.classList.add('is-invalid');
                            }
                        });
                    } else {
                        alert('Error: ' + (data.message || 'Failed to create customer'));
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while creating the customer');
            })
            .finally(() => {
                btn.disabled = false;
                btnText.classList.remove('d-none');
                spinner.classList.add('d-none');
            });
        }

        // Warn before leaving page if cart has items
        let isSubmitting = false;
        window.addEventListener('beforeunload', function(e) {
            if (cart.length > 0 && !isSubmitting) {
                e.preventDefault();
                e.returnValue = 'You have items in your cart. Are you sure you want to leave?';
                return e.returnValue;
            }
        });

        // Set current date when page loads
        document.addEventListener('DOMContentLoaded', function() {
            const dateTime = getCurrentDateTime();

            // Set the hidden date field with current date
            const dateField = document.getElementById('date');
            if (dateField) {
                dateField.value = dateTime.date;
            }

            // Add event listener to Add Customer button
            const addCustomerBtn = document.getElementById('addCustomerBtn');
            if (addCustomerBtn) {
                addCustomerBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Add customer button clicked');
                    openCreateCustomerModal();
                });
                console.log('Add customer button listener attached');
            } else {
                console.error('Add customer button not found!');
            }

            // Display current date and time in console
            console.log('Order form loaded at:', dateTime.formatted);

            // Products and customers loaded via Blade - no auto-refresh
            // Only refresh after Quick Product/Stock actions
        });

        // Refresh functions - only called after quick stock/product actions
        let lastProductsData = null;
        let lastCustomersData = null;

        function refreshProducts() {
            console.log('Refreshing products...');
            fetch('{{ route("orders.products") }}')
                .then(response => {
                    console.log('Products response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Products data received:', data);
                    const currentData = JSON.stringify(data.products);

                    // Only update if data has changed
                    if (lastProductsData !== currentData) {
                        console.log('Products changed! Updating grid...');
                        updateProductsGrid(data.products);
                        updateProductCount(data.count);
                        lastProductsData = currentData;
                    } else {
                        console.log('No product changes detected');
                    }
                })
                .catch(error => {
                    console.error('Error refreshing products:', error);
                    console.error('Error details:', error.message, error.stack);
                });
        }

        function refreshCustomers() {
            fetch('{{ route("orders.customers") }}')
                .then(response => response.json())
                .then(data => {
                    const currentData = JSON.stringify(data.customers);

                    // Only update if data has changed
                    if (lastCustomersData !== currentData) {
                        console.log('Customers updated from database');
                        updateCustomerDropdown(data.customers);
                        lastCustomersData = currentData;
                    }
                })
                .catch(error => console.error('Error refreshing customers:', error));
        }

        // Define addToCart function before updateProductsGrid so it's available for event listeners
        function addToCart(productId, productElement) {
            console.log('=== addToCart called ===', {
                productId: productId,
                productElement: productElement,
                hasElement: !!productElement
            });

            if (!productElement) {
                console.error('ERROR: productElement is null or undefined');
                return;
            }

            // Get product name (handle both in-stock and out-of-stock cases)
            const productNameElement = productElement.querySelector('.fw-bold');
            const productName = productNameElement ? productNameElement.textContent.replace('(Out of Stock)', '').trim() : 'Unknown Product';

            console.log('Product name extracted:', productName);

            // Get product price - try multiple selectors to be more robust
            let priceElement = productElement.querySelector('.fw-bold.text-success') ||
                productElement.querySelector('.fw-bold.text-muted') ||
                productElement.querySelector('.text-success') ||
                productElement.querySelector('.text-muted');

            let productPrice = 0;
            if (priceElement) {
                // Clean price text: remove 'LKR ', commas, and any other non-numeric characters except decimal points
                const priceText = priceElement.textContent.replace('LKR', '').replace(/,/g, '').trim();
                productPrice = parseFloat(priceText);

                // If still NaN, try to find any numbers in the text
                if (isNaN(productPrice)) {
                    const numbers = priceText.match(/[\d.]+/);
                    productPrice = numbers ? parseFloat(numbers[0]) : 0;
                }
            }

            // Debug log for troubleshooting
            console.log('Price parsing:', {
                priceElement: priceElement ? priceElement.textContent : 'not found',
                productPrice: productPrice,
                productName: productName
            });

            // Get stock from data attribute (more reliable)
            const stock = parseInt(productElement.dataset.stock || '0');

            // Get warranty from product data attributes
            const warrantyId = productElement.dataset.warrantyId || '';
            const warrantyName = productElement.dataset.warrantyName || '';
            const warrantyDuration = productElement.dataset.warrantyDuration || '';

            // Debug log for warranty
            console.log('Adding product to cart - Warranty info:', {
                productId: productId,
                productName: productName,
                warrantyId: warrantyId,
                warrantyName: warrantyName,
                warrantyDuration: warrantyDuration,
                warrantyIdType: typeof warrantyId
            });

            // Validate price
            if (isNaN(productPrice) || productPrice <= 0) {
                console.error('PRICE VALIDATION FAILED:', {
                    productName: productName,
                    productPrice: productPrice,
                    priceType: typeof productPrice
                });
                const msg = 'Unable to add ' + productName + ' - invalid price information.';
                try {
                    if (typeof showToast === 'function') {
                        showToast(msg, 'error');
                    } else {
                        alert(msg);
                    }
                } catch (e) {
                    console.error('Error showing toast:', e);
                    alert(msg);
                }
                return;
            }

            console.log('Price validation PASSED:', productPrice);

            // Double-check stock availability
            if (stock <= 0 || productElement.classList.contains('out-of-stock')) {
                console.log('Stock validation FAILED - out of stock');
                const msg = productName + ' is currently out of stock and cannot be added to cart.';
                try {
                    if (typeof showToast === 'function') {
                        showToast(msg, 'error');
                    } else {
                        alert(msg);
                    }
                } catch (e) {
                    console.error('Error showing toast:', e);
                    alert(msg);
                }
                return;
            }

            console.log('Stock validation PASSED:', stock);

            // Check if product already exists in cart
            const existingItem = cart.find(item => item.id === productId);

            if (existingItem) {
                console.log('Product already in cart - increasing quantity');
                // Get current available stock (accounting for items already in cart from other sessions)
                const availableStock = stock;
                const totalInCart = existingItem.quantity;

                // Check if we can increase quantity
                if (totalInCart + 1 <= availableStock) {
                    existingItem.quantity += 1;
                    existingItem.total = existingItem.quantity * existingItem.price;
                    // Update the stock reference in case it changed
                    existingItem.stock = stock;
                    console.log('Quantity increased:', existingItem.quantity);
                    updateCartDisplay();
                } else {
                    console.log('Cannot add more - stock limit reached');
                    alert(`Cannot add more items. Available stock: ${availableStock}, Currently in cart: ${totalInCart}`);
                }
            } else {
                // Check payment type and enforce different limits
                const paymentType = document.querySelector('select[name="payment_type"]')?.value || 'Cash';
                const maxItems = paymentType === 'Credit Sales' ? 9 : 11;

                if (cart.length >= maxItems) {
                    const msg = `Maximum ${maxItems} different items allowed per order for ${paymentType}. Please create a new order for additional items.`;
                    try {
                        if (typeof showToast === 'function') {
                            showToast(msg, 'warning');
                        } else {
                            alert(msg);
                        }
                    } catch (e) {
                        console.error('Error showing toast:', e);
                        alert(msg);
                    }
                    return;
                }

                console.log('Adding NEW product to cart');
                // Create new cart item with product's default warranty
                const lineId = (typeof crypto !== 'undefined' && crypto.randomUUID) ? crypto.randomUUID() : (Date.now()
                    .toString(36) + Math.random().toString(36).slice(2));
                const newItem = {
                    lineId: lineId,
                    id: productId,
                    name: productName,
                    price: productPrice,
                    quantity: 1,
                    stock: stock,
                    total: productPrice,
                    serial_number: '',
                    warranty_years: null,
                    warranty_id: warrantyId,  // Use product's default warranty
                    warranty_name: warrantyName,
                    warranty_duration: warrantyDuration
                };
                console.log('New cart item created with warranty:', {
                    warranty_id: newItem.warranty_id,
                    warranty_name: newItem.warranty_name,
                    warranty_duration: newItem.warranty_duration,
                    fullItem: newItem
                });
                cart.push(newItem);
                console.log('Cart after push:', cart);
                updateCartDisplay();
            }

            console.log('=== addToCart completed successfully ===');
        }

        function updateProductsGrid(products) {
            const grid = document.getElementById('products-grid');
            if (!grid) return;

            grid.innerHTML = '';

            products.forEach(product => {
                const isOutOfStock = product.stock <= 0;
                const isLowStock = product.stock > 0 && product.stock <= 5;

                const col = document.createElement('div');
                col.className = 'col-6 col-sm-6 col-md-4 col-lg-3';
                col.style.padding = '0.25rem';

                const card = document.createElement('div');
                card.className = `card product-card ${isOutOfStock ? 'out-of-stock' : 'cursor-pointer hover-shadow'}`;
                card.dataset.productId = product.id;
                card.dataset.id = product.id;
                card.dataset.code = product.code || '';
                card.dataset.stock = product.stock;
                card.dataset.warrantyId = product.warranty_id || '';
                card.dataset.warrantyName = product.warranty_name || '';
                card.dataset.warrantyDuration = product.warranty_duration || '';
                card.dataset.productName = product.name;
                card.dataset.productCode = product.code || '';
                card.style.border = isOutOfStock ? '2px solid #ef4444' : '1px solid #e9ecef';
                card.style.borderRadius = '8px';
                card.style.minHeight = '120px';
                card.style.height = '100%';
                card.style.width = '100%';
                card.style.transition = 'all 0.2s ease';
                if (isOutOfStock) {
                    card.style.opacity = '0.6';
                    card.style.cursor = 'not-allowed';
                }

                card.innerHTML = `
                    <div class="card-body p-2" style="display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
                        <div class="text-start">
                            <div class="fw-bold ${isOutOfStock ? 'text-muted' : 'text-dark'}"
                                style="font-size: 13px; line-height: 1.3; word-wrap: break-word; min-height: 36px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                ${product.name}
                                ${isOutOfStock ? '<small class="text-danger ms-1 d-block">(Out of Stock)</small>' : ''}
                            </div>
                            ${product.code ? `<div class="text-muted small" style="font-size: 10px;">
                                ${product.code}
                            </div>` : ''}
                        </div>
                        <div class="d-flex justify-content-between align-items-center gap-1" style="flex-wrap: wrap;">
                            <span class="fw-bold ${isOutOfStock ? 'text-muted' : 'text-success'}"
                                style="font-size: 13px; white-space: nowrap;">LKR ${Number(product.price).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',')}</span></span>
                            ${product.stock > 0 ?
                                `<span class="badge rounded-pill"
                                    style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; font-size: 10px; font-weight: 600; padding: 4px 8px; white-space: nowrap; border-radius: 4px; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1); box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);">${product.stock}</span>` :
                                `<span class="badge bg-danger" style="font-size: 9px; padding: 4px 6px; white-space: nowrap;">Out of Stock</span>`
                            }
                        </div>
                    </div>
                `;

                // Attach click event listener
                card.addEventListener('click', function() {
                    if (this.classList.contains('out-of-stock')) {
                        const productName = this.querySelector('.fw-bold').textContent.replace('(Out of Stock)', '').trim();
                        showToast(`"${productName}" is currently out of stock and cannot be added to cart.`, 'error');
                        return;
                    }

                    const productId = this.dataset.productId;
                    addToCart(productId, this);
                });

                col.appendChild(card);
                grid.appendChild(col);
            });
        }

        function updateProductCount(count) {
            const countElement = document.getElementById('products-count');
            if (countElement) {
                countElement.textContent = count;
            }
        }

        function updateCustomerDropdown(customers) {
            const customerSelect = document.getElementById('customer_id');
            if (!customerSelect) return;

            // Get TomSelect instance
            const tomSelect = customerSelect.tomselect;
            if (!tomSelect) return;

            // Get currently selected value
            const currentValue = tomSelect.getValue();

            // Clear and rebuild options
            tomSelect.clear();
            tomSelect.clearOptions();

            customers.forEach(customer => {
                const displayText = customer.name +
                    (customer.mobile ? ' - ' + customer.mobile : '') +
                    (customer.email ? ' - ' + customer.email : '');

                tomSelect.addOption({
                    value: customer.id,
                    text: displayText
                });
            });

            // Restore previously selected value if it still exists
            if (currentValue && customers.find(c => c.id == currentValue)) {
                tomSelect.setValue(currentValue);
            }
        }

        // POS System JavaScript
        let cart = [];
        let cartCount = 0;
        let cartTotal = 0.00;

        // Warranties data from backend
        const warranties = @json($warranties);

        // Initialize Tom Select for customer dropdown
        new TomSelect("#customer_id", {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            },
            placeholder: "Search customer by name, phone, or email",
            searchField: ['text'],
            maxOptions: null,
            render: {
                option: function(data, escape) {
                    const parts = data.text.split(' - ');
                    const name = parts[0] || '';
                    const phone = parts[1] || '';

                    return '<div class="p-2">' +
                        '<div class="fw-bold text-dark">' + escape(name) + '</div>' +
                        (phone ? '<div class="small text-muted mt-1">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">' +
                            '<path stroke="none" d="M0 0h24v24H0z" fill="none"/>' +
                            '<path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2"/>' +
                            '</svg>' + escape(phone) + '</div>' : '') +
                        '</div>';
                },
                item: function(data, escape) {
                    const parts = data.text.split(' - ');
                    const name = parts[0] || '';
                    const phone = parts[1] || '';

                    return '<div>' +
                        '<span class="fw-bold">' + escape(name) + '</span>' +
                        (phone ? ' <small class="text-muted">(' + escape(phone) + ')</small>' : '') +
                        '</div>';
                }
            }
        });

        // Add product click handlers
        document.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('click', function(e) {
                console.log('Product card clicked:', {
                    productId: this.dataset.productId,
                    isOutOfStock: this.classList.contains('out-of-stock'),
                    target: e.target
                });

                // Check if product is out of stock
                if (this.classList.contains('out-of-stock')) {
                    // Show a more user-friendly message for out of stock products
                    const productName = this.querySelector('.fw-bold').textContent.replace('(Out of Stock)',
                        '').trim();

                    // Create and show a toast notification instead of alert
                    showToast(`"${productName}" is currently out of stock and cannot be added to cart.`,
                        'error');
                    return;
                }

                const productId = this.dataset.productId;
                console.log('Calling addToCart with productId:', productId);
                addToCart(productId, this);
            });
        });

        // Clear cart button
        const clearButton = document.querySelector('.card-actions button');
        if (clearButton) {
            clearButton.addEventListener('click', clearCart);
        }

        // Product search functionality
        const searchInput = document.querySelector('input[placeholder*="Search products"]');
        if (searchInput) {
            searchInput.addEventListener('input', filterProducts);
        }

        // Discount and service charges event listeners
        const discountInput = document.getElementById('discount-amount');
        const serviceChargesInput = document.getElementById('service-charges');

        if (discountInput) {
            discountInput.addEventListener('input', updateOrderTotals);
        }

        if (serviceChargesInput) {
            serviceChargesInput.addEventListener('input', updateOrderTotals);
        }

        // addToCart function is now defined earlier, before updateProductsGrid

        function updateCartDisplay() {
            const emptyCart = document.getElementById('empty-cart');
            const cartItems = document.getElementById('cart-items');
            const cartTitle = document.querySelector('h3.card-title');
            const cartAdjustments = document.getElementById('cart-adjustments');

            cartCount = cart.reduce((sum, item) => sum + item.quantity, 0);
            cartTotal = cart.reduce((sum, item) => sum + item.total, 0);

            // Update cart count in the title
            const cartCountElement = document.getElementById('cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = cartCount;
            }

            if (cart.length === 0) {
                if (emptyCart) emptyCart.style.display = 'block';
                if (cartItems) cartItems.style.display = 'none';
                if (cartAdjustments) cartAdjustments.style.display = 'none';
            } else {
                if (emptyCart) emptyCart.style.display = 'none';
                if (cartItems) {
                    cartItems.style.display = 'block';
                    if (cartAdjustments) cartAdjustments.style.display = 'block';

                    cartItems.innerHTML = cart.map(item => {
                        // Debug warranty selection
                        console.log('Rendering cart item warranty:', {
                            product: item.name,
                            warranty_id: item.warranty_id,
                            warranty_name: item.warranty_name,
                            warranty_duration: item.warranty_duration,
                            has_warranty: !!(item.warranty_id && item.warranty_name),
                            condition_check: item.warranty_id && item.warranty_name
                        });

                        return `
                        <div class="cart-item" style="min-height: 95px !important; padding: 12px !important; margin-bottom: 8px !important;">
                            <!-- Product Name and Remove Button -->
                            <div class="d-flex justify-content-between align-items-start mb-2" style="min-height: 24px;">
                                <div class="flex-1">
                                    <div class="cart-item-name fw-bold text-dark" style="font-size: 13px; line-height: 1.2; min-height: 20px;">${item.name}</div>
                                </div>
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeFromCart('${item.lineId}')" style="padding: 4px 8px; font-size: 12px; min-width: 28px; min-height: 28px; width: 28px; height: 28px; flex-shrink: 0;">
                                    ×
                                </button>
                            </div>

                            <!-- Quantity Controls and Price -->
                            <div class="d-flex justify-content-between align-items-center mb-2" style="min-height: 32px;">
                                <div class="d-flex align-items-center">
                                    <button type="button" class="btn btn-outline-secondary btn-sm me-2" onclick="updateQuantity('${item.lineId}', -1)" style="width: 28px !important; height: 28px !important; min-width: 28px; min-height: 28px; padding: 0; font-size: 14px; flex-shrink: 0;">-</button>
                                    <span class="me-2 px-2 fw-bold" style="min-width: 30px; text-align: center; font-size: 14px; line-height: 28px; flex-shrink: 0;">${item.quantity}</span>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="updateQuantity('${item.lineId}', 1)" style="width: 28px !important; height: 28px !important; min-width: 28px; min-height: 28px; padding: 0; font-size: 14px; flex-shrink: 0;">+</button>
                                </div>
                                <div class="cart-item-price fw-bold text-success" style="font-size: 14px; line-height: 28px; white-space: nowrap;">LKR ${item.total.toLocaleString()}</div>
                            </div>

                            <!-- Serial Number and Warranty in Same Row -->
                            <div class="d-flex gap-2" style="min-height: 32px;">
                                <div style="flex: 0 0 60%;">
                                    <input type="text"
                                           class="form-control form-control-sm"
                                           placeholder="Serial number (optional)"
                                           value="${item.serial_number || ''}"
                                           oninput="this.value = this.value.toUpperCase(); updateSerial('${item.lineId}', this.value)"
                                           style="font-size: 12px; padding: 6px 8px; text-transform: uppercase; min-height: 32px !important; height: 32px !important;">
                                </div>
                                <div style="flex: 0 0 40%;">
                                    <select class="form-select form-select-sm"
                                            onchange="updateWarrantyId('${item.lineId}', this.value)"
                                            style="font-size: 12px; padding: 6px 8px; min-height: 32px !important; height: 32px !important;">
                                        <option value="" ${!item.warranty_id ? 'selected' : ''}>-- Select Warranty --</option>
                                        ${warranties.map(w => {
                                            const isSelected = item.warranty_id && String(item.warranty_id) === String(w.id);
                                            return '<option value="' + w.id + '" ' + (isSelected ? 'selected' : '') + '>' +
                                                w.name + (w.duration ? ' (' + w.duration + ' months)' : '') +
                                            '</option>';
                                        }).join('')}
                                    </select>
                                </div>
                            </div>
                        </div>
                    `;
                    }).join('');
                }
            }

            // Update totals with discount and service charges
            updateOrderTotals();
        }

        function removeFromCart(lineId) {
            cart = cart.filter(item => item.lineId !== lineId);
            updateCartDisplay();
        }

        function updateSerial(lineId, serial) {
            const item = cart.find(i => i.lineId === lineId);
            if (item) {
                item.serial_number = serial ? serial.toUpperCase() : '';
            }
        }

        function updateWarrantyId(lineId, warrantyId) {
            const item = cart.find(i => i.lineId === lineId);
            if (item) {
                if (warrantyId) {
                    const warranty = warranties.find(w => w.id == warrantyId);
                    item.warranty_id = warrantyId;
                    item.warranty_name = warranty ? warranty.name : '';
                    item.warranty_duration = warranty ? warranty.duration : '';
                } else {
                    item.warranty_id = null;
                    item.warranty_name = '';
                    item.warranty_duration = '';
                }
                updateCartDisplay(); // Refresh display to show warranty info
            }
        }

        function updateWarranty(lineId, years) {
            const item = cart.find(i => i.lineId === lineId);
            if (item) {
                item.warranty_years = years ? parseInt(years) : null;
            }
        }

        function updateQuantity(lineId, change) {
            const item = cart.find(item => item.lineId === lineId);
            if (item) {
                const newQuantity = item.quantity + change;
                if (newQuantity > 0 && newQuantity <= item.stock) {
                    item.quantity = newQuantity;
                    item.total = item.quantity * item.price;
                    updateCartDisplay();
                } else if (newQuantity <= 0) {
                    removeFromCart(lineId);
                } else {
                    alert(`Cannot add more items. Maximum stock available: ${item.stock}`);
                }
            }
        }

        function clearCart() {
            if (cart.length > 0 && confirm('Are you sure you want to clear the cart?')) {
                cart = [];
                // Reset discount and service charges
                document.getElementById('discount-amount').value = '0';
                document.getElementById('service-charges').value = '0';
                updateCartDisplay();
            }
        }

        // Toast notification function
        function showToast(message, type = 'info') {
            // Remove any existing toast
            const existingToast = document.querySelector('.custom-toast');
            if (existingToast) {
                existingToast.remove();
            }

            // Create toast element
            const toast = document.createElement('div');
            toast.className =
                `custom-toast alert alert-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info'} alert-dismissible fade show`;
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                min-width: 300px;
                max-width: 500px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            `;
            toast.innerHTML = `
                <strong>${type === 'error' ? 'Error:' : type === 'success' ? 'Success:' : 'Info:'}</strong> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            // Add to document
            document.body.appendChild(toast);

            // Auto remove after 4 seconds
            setTimeout(() => {
                if (toast && toast.parentNode) {
                    toast.remove();
                }
            }, 4000);
        }

        // Function to update order totals including discount and service charges
        function updateOrderTotals() {
            const discountAmount = parseFloat(document.getElementById('discount-amount').value) || 0;
            const serviceCharges = parseFloat(document.getElementById('service-charges').value) || 0;

            const subtotalElement = document.getElementById('subtotal-amount');
            const discountRow = document.getElementById('discount-row');
            const discountDisplay = document.getElementById('discount-display');
            const serviceRow = document.getElementById('service-row');
            const serviceDisplay = document.getElementById('service-display');
            const totalElement = document.getElementById('total-amount');

            // Update subtotal
            if (subtotalElement) {
                subtotalElement.textContent = `LKR ${cartTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',')}`;
            }

            // Show/hide and update discount
            if (discountAmount > 0) {
                if (discountRow) discountRow.style.display = 'flex';
                if (discountDisplay) discountDisplay.textContent = `-LKR ${discountAmount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',')}`;
            } else {
                if (discountRow) discountRow.style.display = 'none';
            }

            // Show/hide and update service charges
            if (serviceCharges > 0) {
                if (serviceRow) serviceRow.style.display = 'flex';
                if (serviceDisplay) serviceDisplay.textContent = `+LKR ${serviceCharges.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',')}`;
            } else {
                if (serviceRow) serviceRow.style.display = 'none';
            }

            // Calculate and update final total with proper rounding
            const finalTotal = Math.round((cartTotal - discountAmount + serviceCharges) * 100) / 100;
            if (totalElement) {
                totalElement.textContent = `LKR ${finalTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',')}`;
            }
        }

        function filterProducts() {
            const searchTerm = event.target.value.toLowerCase();
            const productCards = document.querySelectorAll('.product-card');

            productCards.forEach(card => {
                // Get product name
                const productNameElement = card.querySelector('.fw-bold');
                const productName = productNameElement ? productNameElement.textContent.toLowerCase().replace('(out of stock)', '').trim() : '';

                // Get product code/SKU from data attribute or text
                const productCode = card.getAttribute('data-code') || '';
                const productCodeLower = productCode.toLowerCase();

                // Get product ID
                const productId = card.getAttribute('data-id') || '';

                // Search by name, code (SKU), or ID
                if (productName.includes(searchTerm) ||
                    productCodeLower.includes(searchTerm) ||
                    productId.includes(searchTerm)) {
                    card.parentElement.style.display = 'block';
                } else {
                    card.parentElement.style.display = 'none';
                }
            });
        }

        // Payment validation handler - REMOVED conflicting event listener
        // The button now uses only onclick="submitOrder()" to avoid conflicts

        function loadPaymentProcessor() {
            const customerId = document.getElementById('customer_id').value;

            // Dispatch Livewire event to load payment data
            if (window.Livewire) {
                Livewire.emit('load-payment-data', {
                    cartItems: cart,
                    customerId: customerId
                });
            }
        }

        // Listen for payment completion events
        document.addEventListener('livewire:load', function() {
            Livewire.on('payment-completed', function(data) {
                // Hide the payment modal
                const paymentModalElement = document.getElementById('paymentModal');
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    const modal = bootstrap.Modal.getInstance(paymentModalElement);
                    if (modal) {
                        modal.hide();
                    }
                } else if (typeof $ !== 'undefined' && $.fn.modal) {
                    $('#paymentModal').modal('hide');
                } else {
                    // Fallback
                    paymentModalElement.style.display = 'none';
                    paymentModalElement.classList.remove('show');
                }

                // Clear the cart
                cart = [];
                updateCartDisplay();

                // Show success notification
                showSuccessNotification(`Payment completed successfully! Invoice: ${data[0].invoice_no}`);

                // Redirect to order details after 3 seconds
                setTimeout(() => {
                    window.location.href = `{{ route('orders.show', '') }}/${data[0].order_id}`;
                }, 3000);
            });
        });

        function showSuccessNotification(message) {
            // Create a nice success notification
            const notification = document.createElement('div');
            notification.className =
                'alert alert-success alert-dismissible position-fixed top-0 start-50 translate-middle-x';
            notification.style.zIndex = '9999';
            notification.style.marginTop = '20px';
            notification.innerHTML = `
                <div class="d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-success" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M5 12l5 5l10 -10"/>
                    </svg>
                    <div>
                        <strong>Success!</strong> ${message}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(notification);

            // Auto-remove after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 5000);
        }

        // Make cart data available globally for Livewire component
        window.getCartData = function() {
            return cart;
        };

        // Reset payment processor when modal is closed
        if (paymentModal) {
            paymentModal.addEventListener('hidden.bs.modal', function() {
                if (window.Livewire) {
                    Livewire.emit('reset-payment');
                }
            });
        }

        // (Old receipt modal + helpers removed - cleaned up unused modal logic)

        // Function to update stock display after successful payment
        function updateStockDisplay(soldItems) {
            console.log('Updating stock display for items:', soldItems);

            soldItems.forEach(soldItem => {
                // Find the product card on the page
                const productCard = document.querySelector(`[data-product-id="${soldItem.product_id}"]`);
                if (productCard) {
                    const stockBadge = productCard.querySelector('.badge');
                    if (stockBadge) {
                        const currentStockText = stockBadge.textContent;
                        const currentStock = parseInt(currentStockText.replace('Stock: ', ''));
                        const newStock = Math.max(0, currentStock - soldItem.quantity);

                        // Update the stock display
                        stockBadge.textContent = `Stock: ${newStock}`;

                        // Update the badge color based on stock level
                        stockBadge.className = 'badge rounded-pill';
                        if (newStock > 0) {
                            stockBadge.style.backgroundColor = '#3b82f6';
                            stockBadge.style.color = 'white';
                        } else {
                            stockBadge.style.backgroundColor = '#ef4444';
                            stockBadge.style.color = 'white';
                            // Optionally disable the product card
                            productCard.style.opacity = '0.6';
                            productCard.style.pointerEvents = 'none';
                        }

                        console.log(`Updated ${soldItem.product_name}: ${currentStock} → ${newStock}`);
                    }
                }
            });
        }

        // (Old startNewOrder and Escape key handling for the removed receipt modal have been removed.)

        // Submit order function
        function submitOrder() {
            console.log('Submit order called');

            // Validate required fields
            const customerId = document.getElementById('customer_id').value;
            const paymentType = document.querySelector('select[name="payment_type"]').value;

            console.log('Customer ID:', customerId);
            console.log('Payment Type:', paymentType);
            console.log('Cart items:', cart);

            // If no customer selected, it will be saved as Walk-In Customer by server

            if (!paymentType) {
                alert('Please select a payment method');
                return;
            }

            if (cart.length === 0) {
                alert('Please add items to cart');
                return;
            }

            // Handle payment amount
            const payAmountInput = document.getElementById('payment-amount-input');
            if (paymentType === 'Cash') {
                const payAmount = payAmountInput.value;
                if (!payAmount || parseFloat(payAmount) <= 0) {
                    alert('Please enter payment amount');
                    return;
                }
            } else {
                // For non-cash payments, set pay amount to total
                const cartTotalAmount = cart.reduce((sum, item) => sum + item.total, 0);
                if (payAmountInput) {
                    payAmountInput.value = cartTotalAmount;
                }
            }

            // Populate hidden cart data
            const cartData = JSON.stringify(cart);
            document.getElementById('cart-items-input').value = cartData;

            console.log('Cart data being sent:', cartData);
            console.log('Discount amount:', document.getElementById('discount-amount').value);
            console.log('Service charges:', document.getElementById('service-charges').value);

            // Submit the form using AJAX to avoid redirect issues
            // Use the specific order form ID to avoid confusion with logout form
            const form = document.getElementById('order-form');
            if (!form) {
                console.error('❌ Order form not found!');
                alert('Error: Order form not found');
                return;
            }
            const formData = new FormData(form);

            console.log('🔍 FORM DEBUG INFO:');
            console.log('Form element:', form);
            console.log('Form action attribute:', form.getAttribute('action'));
            console.log('Form action property:', form.action);
            console.log('Current URL:', window.location.href);
            console.log('Form data:', Object.fromEntries(formData));

            // Force absolute URL to prevent any relative path issues
            const targetUrl = form.action || 'http://127.0.0.1:8000/simple-test';
            console.log('🚀 Final target URL:', targetUrl);

            fetch(targetUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                            document.querySelector('input[name="_token"]')?.value
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response headers:', response.headers);
                    return response.text(); // Get raw text first
                })
                .then(data => {
                    console.log('Raw response:', data);

                    // Try to parse as JSON first (from OrderController)
                    try {
                        const jsonResponse = JSON.parse(data);
                        console.log('✅ JSON RESPONSE RECEIVED!');
                        console.log('� Server response:', jsonResponse);

                        if (jsonResponse.success) {
                            // Update stock display with sold items
                            if (jsonResponse.soldItems && jsonResponse.soldItems.length > 0) {
                                updateStockDisplay(jsonResponse.soldItems);
                            }

                            // Clear cart and reset UI
                            const cartItemsBeforeClear = [...cart]; // Store cart items for potential stock update
                            cart = [];
                            updateCartDisplay();
                            const payInput = document.getElementById('payment-amount-input');
                            const feedback = document.getElementById('balance-feedback');
                            if (payInput) {
                                payInput.value = '';
                                payInput.classList.remove('is-invalid', 'is-valid', 'border-danger', 'border-info');
                            }
                            if (feedback) feedback.innerHTML = '';
                            document.getElementById('cart-items-input').value = '[]';

                            // If server doesn't provide soldItems, use cart data as fallback
                            if (!jsonResponse.soldItems && cartItemsBeforeClear.length > 0) {
                                const fallbackSoldItems = cartItemsBeforeClear.map(item => ({
                                    product_id: item.id,
                                    product_name: item.name,
                                    quantity: item.quantity
                                }));
                                updateStockDisplay(fallbackSoldItems);
                            }

                            // Show success message briefly, then show modal
                            isSubmitting = true; // Prevent leave confirmation
                            showSuccessNotification('Order created successfully!');
                            console.log('About to show modal with order data:', jsonResponse.order);
                            setTimeout(() => {
                                console.log('Calling showOrderReceiptModal now...');
                                showOrderReceiptModal(jsonResponse.order);
                            }, 500);
                        } else {
                            alert('❌ Order creation failed: ' + (jsonResponse.message || 'Unknown error'));
                        }
                    } catch (e) {
                        // Fallback for HTML responses (like our debug route)
                        console.log('Response is not JSON, checking for SUCCESS marker');
                        if (data.includes('SUCCESS!')) {
                            console.log('✅ DEBUG SUCCESS RESPONSE!');
                            alert('✅ SUCCESS! Check console for details.');
                            const newWindow = window.open();
                            newWindow.document.write(data);
                        } else {
                            console.log('❌ Unexpected response:', data);
                            alert('❌ Got unexpected response. Check console for details.');
                        }
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    alert('Network error: ' + error.message);
                });
        }

        // Handle POS receipt printing
        function handlePrintReceipt() {
            console.log('handlePrintReceipt called');
            console.log('window.currentOrderData:', window.currentOrderData);

            if (!window.currentOrderData) {
                console.error('No order data available');
                alert('Error: Order information not available. Please try creating the order again.');
                return;
            }

            if (!window.currentOrderData.id) {
                console.error('Order ID missing from order data:', window.currentOrderData);
                alert('Error: Order ID not available. Please try creating the order again.');
                return;
            }

            const orderId = window.currentOrderData.id;
            const returnUrl = encodeURIComponent(window.location.href);
            const printUrl = `/orders/${orderId}/receipt?auto=1&pos=1&return=${returnUrl}`;

            console.log('Redirecting to print URL:', printUrl);
            window.location.href = printUrl;
        }

        // Reliable PDF download function using fetch API
        async function downloadPdfReliably(orderId, invoiceNo) {
            if (!orderId) {
                alert('Order ID not available. Cannot download PDF.');
                return;
            }

            const downloadUrl = `/orders/${orderId}/download-pdf-bill`;
            console.log('Downloading PDF from:', downloadUrl);

            try {
                // Show loading state
                const button = event?.target;
                const originalText = button?.textContent;
                if (button) {
                    button.disabled = true;
                    button.textContent = '⏳ Downloading...';
                }

                const resp = await fetch(downloadUrl, {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/pdf, application/octet-stream, */*'
                    }
                });

                // Restore button state
                if (button) {
                    button.disabled = false;
                    button.textContent = originalText;
                }

                // Check for redirect to login
                if (resp.redirected && resp.url.includes('/login')) {
                    alert('Your session has expired. Please login again.');
                    window.location = resp.url;
                    return;
                }

                // Check if response is successful
                if (!resp.ok) {
                    console.error('PDF download failed with status:', resp.status);
                    alert('Failed to download PDF. Server returned an error. Please try again.');
                    return;
                }

                const contentType = resp.headers.get('Content-Type') || '';

                // Check if response is JSON (error response)
                if (contentType.includes('json')) {
                    const errorData = await resp.json();
                    console.error('PDF download error:', errorData);
                    alert(errorData.message || 'Failed to generate PDF. Please try again.');
                    return;
                }

                // If response is HTML, it's likely an error page
                if (contentType.includes('html')) {
                    console.error('Expected PDF, got HTML page');
                    alert('Server returned an error page. Please try again.');
                    return;
                }

                // Get PDF blob and trigger download (accept PDF or octet-stream)
                const blob = await resp.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `Invoice_${invoiceNo || orderId}.pdf`;
                document.body.appendChild(a);
                a.click();

                // Cleanup
                setTimeout(() => {
                    a.remove();
                    window.URL.revokeObjectURL(url);
                }, 100);

                console.log('PDF download completed successfully');

            } catch (err) {
                console.error('PDF download error:', err);
                alert('Failed to download PDF. Please check your connection and try again.');
            }
        }

        // Quick Stock Update Functions
        let currentStockProductId = null;

        function openQuickStockModal(productId, productName, productCode) {
            currentStockProductId = productId;
            document.getElementById('stock-product-name').textContent = productName;
            document.getElementById('stock-product-code').textContent = productCode;
            document.getElementById('stock-quantity-input').value = 1;
            document.getElementById('stock-update-error').classList.add('d-none');

            const modal = new bootstrap.Modal(document.getElementById('quickStockModal'));
            modal.show();
        }

        document.getElementById('update-stock-btn').addEventListener('click', async function() {
            const quantity = parseInt(document.getElementById('stock-quantity-input').value);

            if (!quantity || quantity < 1) {
                document.getElementById('stock-update-error').textContent = 'Please enter a valid quantity';
                document.getElementById('stock-update-error').classList.remove('d-none');
                return;
            }

            const btn = this;
            const spinner = btn.querySelector('.spinner-border');
            const originalText = btn.textContent;

            btn.disabled = true;
            spinner.classList.remove('d-none');
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Updating...';

            try {
                const response = await fetch(`/products/${currentStockProductId}/add-stock`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ add_quantity: quantity })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Close modal
                    bootstrap.Modal.getInstance(document.getElementById('quickStockModal')).hide();

                    // Refresh products list
                    await refreshProducts();

                    // Show success message
                    if (typeof showToast === 'function') {
                        showToast(`Stock updated successfully. New stock: ${data.new_quantity}`, 'success');
                    } else {
                        alert(`Stock updated successfully. New stock: ${data.new_quantity}`);
                    }
                } else {
                    document.getElementById('stock-update-error').textContent = data.message || 'Failed to update stock';
                    document.getElementById('stock-update-error').classList.remove('d-none');
                }
            } catch (error) {
                console.error('Stock update error:', error);
                document.getElementById('stock-update-error').textContent = 'Network error. Please try again.';
                document.getElementById('stock-update-error').classList.remove('d-none');
            } finally {
                btn.disabled = false;
                btn.textContent = originalText;
            }
        });

        // Allow Enter key to submit
        document.getElementById('stock-quantity-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('update-stock-btn').click();
            }
        });

        // Global Quick Stock functionality - Initialize immediately
        (function() {
            window.globalProductTomSelect = null;

            window.openQuickStockModalGlobal = function() {
                console.log('openQuickStockModalGlobal called'); // Debug log

                // Reset form
                const form = document.getElementById('globalQuickStockForm');
                if (!form) {
                    console.error('Form globalQuickStockForm not found');
                    return;
                }
                form.reset();
            document.querySelectorAll('#globalQuickStockModal .invalid-feedback').forEach(el => {
                el.textContent = '';
                el.style.display = 'none';
            });
            document.querySelectorAll('#globalQuickStockModal .form-control, #globalQuickStockModal .form-select').forEach(el => {
                el.classList.remove('is-invalid');
            });
            document.getElementById('global_stock_error').classList.add('d-none');

            // Initialize TomSelect for product search if not already initialized
            if (!window.globalProductTomSelect) {
                window.globalProductTomSelect = new TomSelect('#global_stock_product', {
                    valueField: 'id',
                    labelField: 'name',
                    searchField: ['name', 'code'],
                    load: function(query, callback) {
                        if (!query.length) {
                            callback();
                            return;
                        }

                        const url = `/api/products?search=${encodeURIComponent(query)}`;

                        console.log('Fetching products from:', url);

                        fetch(url)
                            .then(response => {
                                console.log('Response status:', response.status);
                                return response.json();
                            })
                            .then(json => {
                                console.log('Products received:', json);
                                callback(json);
                            })
                            .catch(error => {
                                console.error('Error loading products:', error);
                                callback();
                            });
                    },
                    render: {
                        option: function(item, escape) {
                            return `<div>
                                <span class="fw-bold">${escape(item.name)}</span>
                                <span class="text-muted ms-2">(${escape(item.code || 'N/A')})</span>
                                <br><small class="text-muted">Current Stock: ${escape(item.quantity || 0)}</small>
                            </div>`;
                        },
                        item: function(item, escape) {
                            return `<div>${escape(item.name)} <small class="text-muted">(${escape(item.code || 'N/A')})</small></div>`;
                        }
                    },
                    placeholder: 'Type to search products...',
                    preload: 'focus',
                    loadThrottle: 300
                });
            } else {
                window.globalProductTomSelect.clear();
                window.globalProductTomSelect.clearOptions();
            }

            // Show modal
            const modalEl = document.getElementById('globalQuickStockModal');
            if (!modalEl) {
                console.error('Modal globalQuickStockModal not found');
                return;
            }

            // Try Bootstrap 5 first, then fallback to jQuery
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            } else if (typeof $ !== 'undefined' && $.fn.modal) {
                $(modalEl).modal('show');
            } else {
                // Fallback: manually show modal
                modalEl.classList.add('show');
                modalEl.style.display = 'block';
                modalEl.setAttribute('aria-modal', 'true');
                modalEl.removeAttribute('aria-hidden');

                // Add backdrop
                const backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade show';
                backdrop.id = 'globalQuickStockBackdrop';
                document.body.appendChild(backdrop);
                document.body.classList.add('modal-open');
            }
        }

        window.closeQuickStockModal = function() {
            const modalEl = document.getElementById('globalQuickStockModal');
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (modalInstance) modalInstance.hide();
            } else if (typeof $ !== 'undefined' && $.fn.modal) {
                $(modalEl).modal('hide');
            } else {
                // Manual close
                modalEl.classList.remove('show');
                modalEl.style.display = 'none';
                modalEl.removeAttribute('aria-modal');
                modalEl.setAttribute('aria-hidden', 'true');
                const backdrop = document.getElementById('globalQuickStockBackdrop');
                if (backdrop) backdrop.remove();
                document.body.classList.remove('modal-open');
            }
        }

        window.submitGlobalQuickStock = async function() {
            const productId = window.globalProductTomSelect.getValue();
            const quantity = parseInt(document.getElementById('global_stock_quantity').value);

            // Reset errors
            document.querySelectorAll('#globalQuickStockModal .invalid-feedback').forEach(el => {
                el.textContent = '';
                el.style.display = 'none';
            });
            document.querySelectorAll('#globalQuickStockModal .form-control, #globalQuickStockModal .form-select').forEach(el => {
                el.classList.remove('is-invalid');
            });
            document.getElementById('global_stock_error').classList.add('d-none');

            // Validate
            let hasError = false;
            if (!productId) {
                document.getElementById('global_product_error').textContent = 'Please select a product';
                document.getElementById('global_product_error').style.display = 'block';
                document.getElementById('global_stock_product').closest('.ts-wrapper').classList.add('is-invalid');
                hasError = true;
            }
            if (!quantity || quantity < 1) {
                document.getElementById('global_quantity_error').textContent = 'Please enter a valid quantity';
                document.getElementById('global_quantity_error').style.display = 'block';
                document.getElementById('global_stock_quantity').classList.add('is-invalid');
                hasError = true;
            }

            if (hasError) return;

            // Show loading state
            const btn = document.getElementById('global-update-stock-btn');
            const btnText = document.getElementById('globalStockBtnText');
            const spinner = document.getElementById('globalStockSpinner');

            btn.disabled = true;
            btnText.classList.add('d-none');
            spinner.classList.remove('d-none');

            try {
                const response = await fetch(`/products/${productId}/add-stock`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ add_quantity: quantity })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Close modal
                    const modalEl = document.getElementById('globalQuickStockModal');
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        const modalInstance = bootstrap.Modal.getInstance(modalEl);
                        if (modalInstance) modalInstance.hide();
                    } else if (typeof $ !== 'undefined' && $.fn.modal) {
                        $(modalEl).modal('hide');
                    } else {
                        // Manual close
                        modalEl.classList.remove('show');
                        modalEl.style.display = 'none';
                        modalEl.removeAttribute('aria-modal');
                        modalEl.setAttribute('aria-hidden', 'true');
                        const backdrop = document.getElementById('globalQuickStockBackdrop');
                        if (backdrop) backdrop.remove();
                        document.body.classList.remove('modal-open');
                    }

                    // Refresh products list
                    await refreshProducts();

                    // Show success message with toast notification
                    showToast(data.message || 'Stock updated successfully!', 'success');
                } else {
                    const errorDiv = document.getElementById('global_stock_error');
                    errorDiv.textContent = data.message || 'Failed to update stock';
                    errorDiv.classList.remove('d-none');
                }
            } catch (error) {
                console.error('Stock update error:', error);
                const errorDiv = document.getElementById('global_stock_error');
                errorDiv.textContent = 'Network error. Please try again.';
                errorDiv.classList.remove('d-none');
            } finally {
                btn.disabled = false;
                btnText.classList.remove('d-none');
                spinner.classList.add('d-none');
            }
        }

        // Allow Enter key to submit for global quick stock
        const globalQuantityInput = document.getElementById('global_stock_quantity');
        if (globalQuantityInput) {
            globalQuantityInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    window.submitGlobalQuickStock();
                }
            });
        }

        // Quick Product Functions
        window.openQuickProductModal = function() {
            const modalEl = document.getElementById('quickProductModal');

            // Reset form
            document.getElementById('quickProductForm').reset();

            // Reset validation states
            document.querySelectorAll('#quickProductModal .is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
            document.querySelectorAll('#quickProductModal .invalid-feedback').forEach(el => {
                el.textContent = '';
                el.style.display = 'none';
            });
            document.getElementById('qp_error_alert').classList.add('d-none');
            document.getElementById('qp_success_alert').classList.add('d-none');

            // Set default quantity to 1
            document.getElementById('qp_quantity').value = '1';

            // Open modal
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            } else if (typeof $ !== 'undefined' && $.fn.modal) {
                $(modalEl).modal('show');
            } else {
                // Manual show
                modalEl.classList.add('show');
                modalEl.style.display = 'block';
                modalEl.setAttribute('aria-modal', 'true');
                modalEl.removeAttribute('aria-hidden');

                // Add backdrop
                const backdrop = document.createElement('div');
                backdrop.id = 'quickProductBackdrop';
                backdrop.className = 'modal-backdrop fade show';
                document.body.appendChild(backdrop);
                document.body.classList.add('modal-open');
            }
        }

        window.closeQuickProductModal = function() {
            const modalEl = document.getElementById('quickProductModal');
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (modalInstance) modalInstance.hide();
            } else if (typeof $ !== 'undefined' && $.fn.modal) {
                $(modalEl).modal('hide');
            } else {
                // Manual close
                modalEl.classList.remove('show');
                modalEl.style.display = 'none';
                modalEl.removeAttribute('aria-modal');
                modalEl.setAttribute('aria-hidden', 'true');
                const backdrop = document.getElementById('quickProductBackdrop');
                if (backdrop) backdrop.remove();
                document.body.classList.remove('modal-open');
            }
        }

        window.submitQuickProduct = async function() {
            // Reset validation states
            document.querySelectorAll('#quickProductModal .is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
            document.querySelectorAll('#quickProductModal .invalid-feedback').forEach(el => {
                el.textContent = '';
                el.style.display = 'none';
            });
            document.getElementById('qp_error_alert').classList.add('d-none');
            document.getElementById('qp_success_alert').classList.add('d-none');

            // Get form data
            const formData = new FormData(document.getElementById('quickProductForm'));

            // Validate required fields
            let hasError = false;
            const name = formData.get('name');
            const unitId = formData.get('unit_id');

            if (!name || name.trim() === '') {
                document.getElementById('qp_name').classList.add('is-invalid');
                document.getElementById('qp_name_error').textContent = 'Product name is required';
                document.getElementById('qp_name_error').style.display = 'block';
                hasError = true;
            }

            if (!unitId) {
                document.getElementById('qp_unit').classList.add('is-invalid');
                document.getElementById('qp_unit_error').textContent = 'Unit is required';
                document.getElementById('qp_unit_error').style.display = 'block';
                hasError = true;
            }

            if (hasError) return;

            // Show loading state
            const btn = document.getElementById('quick-product-btn');
            const btnText = document.getElementById('qpBtnText');
            const spinner = document.getElementById('qpSpinner');

            btn.disabled = true;
            btnText.classList.add('d-none');
            spinner.classList.remove('d-none');

            try {
                const response = await fetch('{{ route("products.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Show success message
                    showToast(data.message || 'Product added successfully!', 'success');

                    // Refresh products list
                    await refreshProducts();

                    // Close modal after a short delay
                    setTimeout(() => {
                        window.closeQuickProductModal();
                    }, 500);
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        Object.keys(data.errors).forEach(key => {
                            const fieldMap = {
                                'name': 'qp_name',
                                'unit_id': 'qp_unit',
                                'buying_price': 'qp_buying_price',
                                'selling_price': 'qp_selling_price',
                                'quantity': 'qp_quantity'
                            };

                            const fieldId = fieldMap[key];
                            if (fieldId) {
                                document.getElementById(fieldId).classList.add('is-invalid');
                                document.getElementById(fieldId + '_error').textContent = data.errors[key][0];
                                document.getElementById(fieldId + '_error').style.display = 'block';
                            }
                        });
                    }

                    const errorDiv = document.getElementById('qp_error_alert');
                    errorDiv.textContent = data.message || 'Failed to add product';
                    errorDiv.classList.remove('d-none');
                }
            } catch (error) {
                console.error('Product creation error:', error);
                const errorDiv = document.getElementById('qp_error_alert');
                errorDiv.textContent = 'Network error. Please try again.';
                errorDiv.classList.remove('d-none');
            } finally {
                btn.disabled = false;
                btnText.classList.remove('d-none');
                spinner.classList.add('d-none');
            }
        }

        // Allow Enter key to submit for quick product
        const qpNameInput = document.getElementById('qp_name');
        if (qpNameInput) {
            qpNameInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    window.submitQuickProduct();
                }
            });
        }

        })(); // End of IIFE

        // Enhanced POS Page Preloader - Maximum Stability
        (function() {
            let isPageReady = false;
            let checksPassed = {
                dom: false,
                images: false,
                fonts: false,
                tomSelect: false,
                productGrid: false,
                cart: false,
                styles: false
            };

            function checkIfAllReady() {
                const allReady = Object.values(checksPassed).every(check => check === true);

                if (allReady && !isPageReady) {
                    isPageReady = true;

                    // Wait longer to ensure everything is absolutely stable
                    setTimeout(function() {
                        const pageBody = document.querySelector('.page-body');
                        if (pageBody) {
                            // Force a reflow to ensure layout is calculated
                            pageBody.offsetHeight;

                            pageBody.style.opacity = '1';
                            pageBody.style.visibility = 'visible';
                            pageBody.style.transition = 'opacity 0.5s ease-in';
                        }
                        console.log('✓✓✓ POS page fully loaded and stable');
                    }, 300); // Increased delay for stability
                }
            }

            // Check 1: DOM Content Loaded
            document.addEventListener('DOMContentLoaded', function() {
                checksPassed.dom = true;
                console.log('✓ DOM ready');

                // Check for critical elements
                const cartItems = document.getElementById('cart-items');
                const productGrid = document.getElementById('products-grid');

                if (cartItems) {
                    checksPassed.cart = true;
                    console.log('✓ Cart initialized');
                }

                if (productGrid) {
                    const products = productGrid.querySelectorAll('.product-card');
                    if (products.length > 0) {
                        checksPassed.productGrid = true;
                        console.log('✓ Product grid ready with', products.length, 'products');
                    }
                }

                checkIfAllReady();
            });

            // Check 2: Wait for all resources (images, fonts, CSS)
            window.addEventListener('load', function() {
                checksPassed.images = true;
                console.log('✓ All resources loaded');

                // Give extra time for fonts to render
                setTimeout(function() {
                    checksPassed.fonts = true;
                    console.log('✓ Fonts rendered');
                    checkIfAllReady();
                }, 100);

                checkIfAllReady();
            });

            // Check 3: Wait for styles to be applied
            setTimeout(function() {
                const testElement = document.querySelector('.card');
                if (testElement) {
                    const styles = window.getComputedStyle(testElement);
                    if (styles.minHeight) {
                        checksPassed.styles = true;
                        console.log('✓ Styles applied');
                        checkIfAllReady();
                    }
                }
            }, 200);

            // Check 4: Wait for TomSelect to initialize
            let tomSelectCheckAttempts = 0;
            const tomSelectInterval = setInterval(function() {
                tomSelectCheckAttempts++;

                const customerSelect = document.getElementById('customer_id');
                if (customerSelect && customerSelect.tomselect) {
                    checksPassed.tomSelect = true;
                    console.log('✓ TomSelect initialized');
                    clearInterval(tomSelectInterval);
                    checkIfAllReady();
                }

                // Stop checking after 5 seconds
                if (tomSelectCheckAttempts > 50) {
                    checksPassed.tomSelect = true; // Assume it's ready
                    clearInterval(tomSelectInterval);
                    console.log('⚠ TomSelect timeout - proceeding anyway');
                    checkIfAllReady();
                }
            }, 100);

            // Fallback: Force show after 5 seconds (increased from 4)
            setTimeout(function() {
                if (!isPageReady) {
                    console.log('⚠ Fallback: Forcing page display after timeout');
                    const pageBody = document.querySelector('.page-body');
                    if (pageBody) {
                        pageBody.style.opacity = '1';
                        pageBody.style.visibility = 'visible';
                        pageBody.style.transition = 'opacity 0.5s ease-in';
                    }
                    isPageReady = true;
                }
            }, 5000);

            // Prevent layout shifts during Livewire updates
            document.addEventListener('livewire:load', function() {
                console.log('✓ Livewire loaded on POS page');
            });

            // Stabilize layout after everything loads
            window.addEventListener('load', function() {
                setTimeout(function() {
                    // Force recalculation of layout
                    const cards = document.querySelectorAll('.card');
                    cards.forEach(card => {
                        card.offsetHeight; // Force reflow
                    });
                }, 500);
            });

            // Monitor for layout shifts (for debugging)
            if ('PerformanceObserver' in window) {
                const observer = new PerformanceObserver((list) => {
                    for (const entry of list.getEntries()) {
                        if (entry.hadRecentInput) continue;
                        if (entry.value > 0.01) { // Only log significant shifts
                            console.warn('⚠ Layout Shift:', (entry.value * 100).toFixed(2) + '%');
                        }
                    }
                });

                try {
                    observer.observe({ entryTypes: ['layout-shift'] });
                } catch (e) {
                    // Layout shift API not supported
                }
            }
        })();
    </script>
@endpush
