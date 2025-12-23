@extends('layouts.nexora')

@section('title', 'Orders Management')

@section('content')
    <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Sales Management
                </div>
                <h2 class="page-title">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M7 9m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" />
                                    <path d="M14 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M17 9v-2a2 2 0 0 0 -2 -2h-10a2 2 0 0 0 -2 2v6a2 2 0 0 0 2 2h2" />
                    </svg>
                    Orders Management
                </h2>
                <p class="text-muted">Manage orders, track payments, and monitor order status</p>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('orders.create') }}" class="btn btn-success d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M3 3h4l2 7h7a1 1 0 0 1 .962 1.275l-1.5 6A1 1 0 0 1 15.5 18h-7A1 1 0 0 1 7.538 17.275l-1.5-6L4 5H3" />
                                <circle cx="10" cy="21" r="1" />
                                <circle cx="17" cy="21" r="1" />
                            </svg>
                            Point Of Sale
                        </a>
                        <a href="{{ route('credit-sales.index') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M7 9m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" />
                                <path d="M14 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M17 9v-2a2 2 0 0 0 -2 -2h-10a2 2 0 0 0 -2 2v6a2 2 0 0 0 2 2h2" />
                            </svg>
                            CREDIT SALES
                        </a>
                        <a href="{{ route('customers.index') }}" class="btn btn-info d-none d-sm-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                            </svg>
                            Customers
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($orders->isEmpty())
        <x-empty title="No orders found"
            message="Try adjusting your search or filter to find what you're looking for."
            button_label="{{ __('Add your first Order') }}" button_route="{{ route('orders.create') }}" />
    @else
        <x-alert />

        <!-- Orders List Table -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Orders</h3>
                <div class="card-actions">
                    <a href="{{ route('orders.create') }}" class="btn btn-primary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Add Order
                    </a>
                </div>
            </div>

            <!-- Advanced Filters Section -->
            <div class="card-body border-bottom py-3">
                <form method="GET" action="{{ route('orders.index') }}" id="filterForm">
                    <!-- Search and Date Filter Row -->
                            <div class="row g-2 mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Search</label>
                                    <input type="text" name="search" class="form-control"
                                           placeholder="Invoice No., Customer Name, Phone Number"
                                           value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Date From</label>
                                    <input type="date" name="filter_date_from" class="form-control"
                                           value="{{ request('filter_date_from') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Date To</label>
                                    <input type="date" name="filter_date_to" class="form-control"
                                           value="{{ request('filter_date_to') }}">
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <circle cx="10" cy="10" r="7"/>
                                            <line x1="21" y1="21" x2="15" y2="15"/>
                                        </svg>
                                    </button>
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <a href="{{ route('orders.index') }}" class="btn btn-outline-danger w-100" title="Reset Filters">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"/>
                                            <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <!-- Entries per page Row -->
                            <div class="row g-2">
                                <div class="col-md-12 d-flex justify-content-end">
                                    <div class="text-muted">
                                        Show
                                        <select name="per_page" class="form-select form-select-sm d-inline-block w-auto mx-2" onchange="this.form.submit()">
                                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                            <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                        </select>
                                        entries
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Loading indicator -->
                    <div id="loading-indicator" class="text-center py-4" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <!-- Table Content Wrapper for AJAX -->
                    <div id="orders-table-content">
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th class="w-1">NO.</th>
                                    <th>INVOICE NO.</th>
                                    <th>CUSTOMER</th>
                                    <th>DATE</th>
                                    <th>PAYMENT</th>
                                    <th>TOTAL</th>
                                    <th>CREATED BY</th>
                                    <th class="w-1">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $index => $order)
                                    <tr>
                                        <td><span class="text-muted">{{ $index + 1 }}</span></td>
                                        <td><span class="text-muted">{{ $order->invoice_no ?? 'ORD' . str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</span></td>
                                        <td>
                                            <div class="d-flex py-1 align-items-center">
                                                <span class="avatar me-2">{{ substr($order->customer->name ?? 'Guest', 0, 2) }}</span>
                                                <div class="flex-fill">
                                                    <div class="font-weight-medium">{{ $order->customer->name ?? 'Guest Customer' }}</div>
                                                    <div class="text-muted">{{ $order->customer->phone ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $order->created_at->format('d-m-Y') }}</td>
                                        <td>
                                            @php
                                                $ptype = strtolower($order->payment_type ?? '');
                                            @endphp
                                            @if ($ptype === 'credit sales' || $ptype === 'credit')
                                                <span class="badge bg-warning">Credit Sales</span>
                                            @elseif ($ptype === 'card')
                                                <span class="badge bg-purple">Card</span>
                                            @elseif ($ptype === 'bank transfer')
                                                <span class="badge bg-info text-dark">Bank Transfer</span>
                                            @else
                                                <span class="badge bg-success">Cash</span>
                                            @endif
                                        </td>
                                        <td><strong>LKR {{ number_format($order->total, 2) }}</strong></td>
                                        <td>
                                            @if($order->creator)
                                                <span class="badge bg-info text-dark">{{ $order->creator->name }} <span class="text-muted small">({{ $order->creator->getRoleDisplayName() }})</span></span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <button type="button" class="btn btn-white btn-sm" title="View & Print" onclick="viewOrderInModal({{ $order->id }})" data-bs-toggle="modal" data-bs-target="#orderReceiptModal">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667-6 7-10 7s-7.333-2.333-10-7c2.667-4.667 6-7 10-7s7.333 2.333 10 7" /></svg>
                                                </button>
                                                <a href="{{ route('orders.edit', $order) }}" class="btn btn-warning btn-sm" title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M7 7h-1a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0-2.97-2.97l-8.415 8.385v3h3l8.385-8.415z" /><path d="M16 5l3 3" /></svg>
                                                </a>
                                                <button type="button" class="btn btn-info btn-sm" title="Quick Print" onclick="printOrder({{ $order->id }})">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M17 17h2a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2h-14a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0-2-2h-6a2 2 0 0 0-2 2v4" /><rect x="7" y="13" width="10" height="8" rx="2" /></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg mb-2"
                                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <circle cx="12" cy="12" r="9" />
                                                    <line x1="9" y1="9" x2="9.01" y2="9" />
                                                    <line x1="15" y1="9" x2="15.01" y2="9" />
                                                    <path d="M8 13a4 4 0 1 0 8 0" />
                                                </svg>
                                                <p>No orders found</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer d-flex align-items-center">
                        <p class="m-0 text-muted">Showing <span>{{ $orders->firstItem() ?? 0 }}</span> to <span>{{ $orders->lastItem() ?? 0 }}</span> of
                            <span>{{ $orders->total() ?? safe_count($orders) }}</span> entries</p>
                        <ul class="pagination m-0 ms-auto">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <polyline points="15,6 9,12 15,18" />
                                    </svg>
                                    prev
                                </a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item disabled">
                                <a class="page-link" href="#">
                                    next <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <polyline points="9,6 15,12 9,18" />
                                    </svg>
                                </a>
                            </li>
                        </ul>
                    </div>
                    </div><!-- End orders-table-content -->
                </div>
            </div>
    @endif

    @push('scripts')
    <script>
        // AJAX search functionality has been disabled to fix modal functionality
        // The hot search was interfering with Bootstrap modal event handlers
        // Standard form submission is now used instead
        
        /* REMOVED - Hot Search AJAX functionality that was breaking modal
        let searchTimeout;
        const searchInput = document.querySelector('input[name="search"]');
        const filterForm = document.getElementById('filterForm');
        const loadingIndicator = document.getElementById('loading-indicator');
        const tableContent = document.getElementById('orders-table-content');

        function fetchOrders(url = null) {
            const formData = new FormData(filterForm);
            const params = new URLSearchParams(formData);
            const fetchUrl = url || `{{ route('orders.index') }}?${params.toString()}`;

            // Show loading indicator
            if (loadingIndicator) loadingIndicator.style.display = 'block';
            if (tableContent) tableContent.style.opacity = '0.5';

            fetch(fetchUrl, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            })
            .then(response => response.text())
            .then(html => {
                // Parse the HTML response
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContent = doc.querySelector('#orders-table-content');

                if (newContent && tableContent) {
                    tableContent.innerHTML = newContent.innerHTML;

                    // Update URL without page reload
                    if (history.pushState) {
                        history.pushState(null, null, fetchUrl);
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching orders:', error);
            })
            .finally(() => {
                // Hide loading indicator
                if (loadingIndicator) loadingIndicator.style.display = 'none';
                if (tableContent) tableContent.style.opacity = '1';
            });
        }

        // Responsive search with debounce
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    fetchOrders();
                }, 500); // Wait 500ms after user stops typing
            });
        }

        // Handle form submission
        if (filterForm) {
            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                fetchOrders();
            });
        }

        // Handle per page change
        const perPageSelect = document.querySelector('select[name="per_page"]');
        if (perPageSelect) {
            perPageSelect.removeAttribute('onchange');
            perPageSelect.addEventListener('change', function() {
                fetchOrders();
            });
        }

        // Handle pagination clicks
        document.addEventListener('click', function(e) {
            const paginationLink = e.target.closest('.pagination a');
            if (paginationLink && !paginationLink.parentElement.classList.contains('disabled')) {
                e.preventDefault();
                const url = paginationLink.getAttribute('href');
                if (url && url !== '#') {
                    fetchOrders(url);
                }
            }
        });
        */
    </script>
    @endpush

    <!-- Order Receipt Modal -->
    <div class="modal fade" id="orderReceiptModal" tabindex="-1" aria-labelledby="orderReceiptModalLabel" aria-hidden="true" data-bs-keyboard="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
                <div class="modal-header" style="background: #f8f9fa; border-radius: 12px 12px 0 0;">
                    <h5 class="modal-title" id="orderReceiptModalLabel" style="font-size: 16px; font-weight: 600; color: #495057;">Order Receipt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="order-receipt-content" class="receipt-container">
                        <div class="text-center p-4 text-muted">
                            <p>Select an order to view details</p>
                        </div>
                    </div>
                    <!-- Print-only wrapper -->
                    <div id="print-receipt-wrapper" style="display:none;"></div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('page-scripts')
    <script>
        function printOrder(orderId) {
            // Open receipt in a new tab/window and request it to auto-print.
            // Use a query param ?auto=1 so the receipt page can call window.print() on load.
            try {
                // Navigate in the same tab to the POS-sized receipt and auto-print.
                // Include return URL so the receipt page can navigate back after printing.
                try {
                    const returnUrl = encodeURIComponent(window.location.href);
                    const url = `/orders/${orderId}/receipt?auto=1&pos=1&return=${returnUrl}`;
                    window.location.href = url;
                } catch (e) {
                    console.error('printOrder navigation failed, falling back to open in new tab', e);
                    window.open(`/orders/${orderId}/receipt?auto=1&pos=1`, '_blank', 'noopener');
                }
            } catch (e) {
                console.error('printOrder open new tab failed', e);
                // Last resort: navigate current tab
                window.location.href = `/orders/${orderId}/receipt`;
            }
        }
    </script>
@endpush

@push('page-styles')
    <style>
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

        /* Modal header styling */
        #orderReceiptModal .modal-header {
            background: #f8f9fa;
            border-radius: 12px 12px 0 0;
        }

        #orderReceiptModal .modal-title {
            font-size: 16px;
            font-weight: 600;
            color: #495057;
        }

        #orderReceiptModal .receipt-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
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

        #orderReceiptModal .warranty {
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

        /* Print styles for modal */
        @media print {
            body * {
                display: none !important;
                visibility: hidden !important;
            }
            #print-receipt-wrapper, #print-receipt-wrapper * {
                display: block !important;
                visibility: visible !important;
            }
            #print-receipt-wrapper {
                position: fixed !important;
                left: 0 !important;
                top: 0 !important;
                width: 80mm !important;
                min-width: 80mm !important;
                max-width: 100vw !important;
                background: #fff !important;
                box-shadow: none !important;
                padding: 10px !important;
                margin: 0 auto !important;
                z-index: 9999 !important;
                font-family: 'Courier New', monospace !important;
            }
        }
    </style>
@endpush

@push('page-scripts')
    <script>
        // Function to view order in modal
        function viewOrderInModal(orderId) {
            // Save the element that opened the modal so we can restore focus later
            try {
                window.lastFocusedElement = document.activeElement;
            } catch (e) {
                window.lastFocusedElement = null;
            }

            // Show loading state
            document.getElementById('order-receipt-content').innerHTML = `
        <div class="text-center p-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;

            // Fetch order data
            fetch(`/orders/${orderId}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showOrderReceiptModal(data.order);
                    } else {
                        showError('Failed to load order details');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError('Failed to load order details');
                });
        }

        // Function to show order receipt modal
        function showOrderReceiptModal(orderData) {
            // Store order data globally for PDF download
            window.currentOrderData = orderData;

            const customer = orderData.customer;
            const invoiceNo = orderData.invoice_no;
            const dateTime = orderData.order_date;
            const items = orderData.details;
            // The controller/model already provide currency values (cents -> units),
            // so use them directly without dividing by 100.
            const subtotal = orderData.sub_total;
            const discount = (orderData.discount || 0);
            const serviceCharges = (orderData.service_charges || 0);
            const total = orderData.total;

            // Generate receipt HTML (build incrementally to avoid complex nested template literals)
            let receiptHTML = '';

            receiptHTML += `\n        <div class="receipt-header">\n            <div class="company-logo">${{ /* placeholder replaced by Blade */ '' }}${""}{{ $shop ? strtoupper(substr($shop->name, 0, 1)) : 'S' }}</div>\n            <div class="company-name">${{ /* placeholder */ '' }}${""}{{ $shop ? $shop->name : 'Shop Name' }}</div>\n            <div class="company-address">${{ /* placeholder */ '' }}${""}{{ $shop ? $shop->address : 'Shop Address' }}</div>\n            <div class="company-address">${{ /* placeholder */ '' }}${""}{{ $shop ? $shop->phone : 'Phone' }} | {{ $shop ? $shop->email : 'Email' }}</div>\n        </div>\n\n        <div class="receipt-info">\n            <div>\n                <strong>Receipt #:</strong><br>\n                <strong>Date:</strong>\n            </div>\n            <div>\n                ${invoiceNo}<br>\n                ${new Date(dateTime).toLocaleDateString()}\n            </div>\n        </div>\n\n        <div class="customer-section">\n            <div class="customer-title">Customer Details</div>\n            <div class="customer-info">\n                <div><strong>Name:</strong> ${customer.name}</div>\n`;

            if (customer.phone) {
                receiptHTML += `                <div><strong>Phone:</strong> ${customer.phone}</div>\n`;
            }
            if (customer.email) {
                receiptHTML += `                <div><strong>Email:</strong> ${customer.email}</div>\n`;
            }

            receiptHTML += `            </div>\n        </div>\n\n        <div class="items-section">\n`;

            // Build items list safely (no nested template-literal complexity)
            for (let i = 0; i < items.length; i++) {
                const item = items[i];
                const index = i;
                // item.unitcost and item.total are already provided as currency values by the backend
                const unitPrice = Number(item.unitcost).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                const lineTotal = Number(item.total).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

                receiptHTML += `            <div class="item-container">\n                <div class="item-main-row">\n                    <span class="item-number">${index + 1}.</span>\n                    <span class="item-name">${item.product && item.product.name ? item.product.name : ''}</span>\n                    <span class="item-qty">Qty: ${item.quantity}</span>\n                    <span class="item-price">LKR ${unitPrice}</span>\n                    <span class="item-total">LKR ${lineTotal}</span>\n                </div>\n`;

                // details row (serial / warranty)
                const hasSerial = item.product && item.product.serial_number;
                const warrantyYears = item.product && item.product.warranty_years ? Number(item.product.warranty_years) : 0;
                if (hasSerial || warrantyYears > 0) {
                    receiptHTML += `                <div class="item-details-row">\n`;
                    if (hasSerial) {
                        receiptHTML += `                    <div class="serial-info">Serial No: ${item.product.serial_number}</div>\n`;
                    }
                    if (warrantyYears > 0) {
                        receiptHTML += `                    <div class="warranty-info">Warranty: ${warrantyYears} ${warrantyYears === 1 ? 'year' : 'years'}</div>\n`;
                    }
                    receiptHTML += `                </div>\n`;
                }

                receiptHTML += `            </div>\n`;
            }

            receiptHTML += `        </div>\n\n        <div class="totals-section">\n            <div class="total-row">\n                <span>Subtotal:</span>\n                <span>LKR ${Number(subtotal).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>\n            </div>\n`;

            if (discount && discount > 0) {
                receiptHTML += `            <div class="total-row">\n                <span>Discount:</span>\n                <span>-LKR ${Number(discount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>\n            </div>\n`;
            }
            if (serviceCharges && serviceCharges > 0) {
                receiptHTML += `            <div class="total-row">\n                <span>Service Charges:</span>\n                <span>+LKR ${Number(serviceCharges).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>\n            </div>\n`;
            }

            receiptHTML += `            <div class="total-row final">\n                <span>TOTAL:</span>\n                <span>LKR ${Number(total).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>\n            </div>\n        </div>\n\n        <div class="print-actions">\n            <button class="print-btn" onclick="(window.currentOrderData && window.currentOrderData.id) ? printOrder(window.currentOrderData.id) : printOrderReceipt()">🖨️ Print Receipt</button>\n            <button class="pdf-btn" onclick="if(window.currentOrderData && window.currentOrderData.id) { const url = '/orders/' + window.currentOrderData.id + '/download-pdf-bill'; const w = window.open(url, '_blank'); setTimeout(() => { try { w.close(); } catch(e) {} }, 2000); } else { alert('Unable to download: missing order id'); }">📄 Download PDF</button>\n        </div>\n`;

            // Insert receipt content
            document.getElementById('order-receipt-content').innerHTML = receiptHTML;

            // Enable PDF download button now that order data is loaded
            const downloadBtn = document.getElementById('downloadPdfBtn');
            if (downloadBtn) {
                downloadBtn.disabled = false;
            }

            // Dispatch event to signal the receipt has been rendered and is ready for printing
            try {
                setTimeout(function() {
                    document.dispatchEvent(new CustomEvent('receipt.rendered', { detail: { orderId: orderData.id } }));
                }, 0);
            } catch (e) {
                // ignore if dispatch fails for any reason
                console.warn('receipt.rendered dispatch failed', e);
            }
        }

        // Function to download order PDF with letterhead
        function downloadOrderPdf() {
            if (window.currentOrderData && window.currentOrderData.id) {
                const orderId = window.currentOrderData.id;
                const downloadUrl = `/orders/${orderId}/download-pdf-bill`;

                // Open in a new window that will auto-close after download starts
                const downloadWindow = window.open(downloadUrl, '_blank');

                // Close the window after a short delay (enough time for download to start)
                if (downloadWindow) {
                    setTimeout(() => {
                        try {
                            downloadWindow.close();
                        } catch (e) {
                            console.log('Could not auto-close download window:', e);
                        }
                    }, 2000); // 2 seconds delay
                }
            } else {
                alert('Unable to download PDF: Order ID not found');
            }
        }

        // Function to print order from modal
        function printOrderFromModal() {
            if (window.currentOrderData && window.currentOrderData.id) {
                printOrder(window.currentOrderData.id);
            } else {
                alert('Unable to print: Order ID not found');
            }
        }

        // Function to show error
        function showError(message) {
            document.getElementById('order-receipt-content').innerHTML = `
        <div class="text-center p-4">
            <div class="text-danger mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <circle cx="12" cy="12" r="9"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
            </div>
            <p class="text-muted">${message}</p>
            <button class="btn btn-sm btn-secondary" onclick="closeOrderReceiptModal()">Close</button>
        </div>
    `;
        }

        // Function to close order receipt modal
        function closeOrderReceiptModal() {
            try {
                const modalElement = document.getElementById('orderReceiptModal');

                // IMPORTANT: move focus out of the modal BEFORE it is hidden/aria-hidden is toggled.
                // This prevents accessibility issues where an element inside a now-hidden ancestor
                // retains focus (assistive tech users can't reach it). Prefer restoring focus to
                // the element that opened the modal (if available), otherwise move focus to body.
                try {
                    const active = document.activeElement;
                    if (modalElement && active && modalElement.contains(active)) {
                        if (window.lastFocusedElement && typeof window.lastFocusedElement.focus === 'function') {
                            window.lastFocusedElement.focus();
                        } else if (document.body && typeof document.body.focus === 'function') {
                            document.body.focus();
                        } else {
                            // As a last resort blur the active element so it no longer holds focus
                            try { active.blur(); } catch (e) { /* ignore */ }
                        }
                    }
                } catch (e) {
                    // swallow any focus-moving errors
                }

                // Try Bootstrap 5 method first
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) {
                        modal.hide();
                    } else {
                        // Create new modal instance and hide it
                        const newModal = new bootstrap.Modal(modalElement);
                        newModal.hide();
                    }
                }
                // Fallback: try jQuery Bootstrap (Bootstrap 4)
                else if (typeof $ !== 'undefined' && $.fn.modal) {
                    $(modalElement).modal('hide');
                }
                // Last resort: manually hide the modal
                else {
                    modalElement.style.display = 'none';
                    modalElement.classList.remove('show');
                    document.body.classList.remove('modal-open');

                    // Remove backdrop if exists
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) {
                        backdrop.remove();
                    }
                }

                // Clear the content to reset for next use
                setTimeout(() => {
                    const contentElement = document.getElementById('order-receipt-content');
                    if (contentElement) {
                        contentElement.innerHTML = `
                    <div class="text-center p-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading order details...</p>
                    </div>
                `;
                    }
                }, 300);

            } catch (error) {
                console.error('Error closing modal:', error);
                // Force close as last resort
                const modalElement = document.getElementById('orderReceiptModal');
                if (modalElement) {
                    // attempt to remove focus from any focused element inside the modal
                    try {
                        const active = document.activeElement;
                        if (active && modalElement.contains(active)) {
                            if (window.lastFocusedElement && typeof window.lastFocusedElement.focus === 'function') {
                                window.lastFocusedElement.focus();
                            } else if (document.body && typeof document.body.focus === 'function') {
                                document.body.focus();
                            } else {
                                try { active.blur(); } catch (e) {}
                            }
                        }
                    } catch (e) {}

                    modalElement.style.display = 'none';
                    modalElement.classList.remove('show');
                    document.body.classList.remove('modal-open');

                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) {
                        backdrop.remove();
                    }
                }
            }
        }

        // Function to print order receipt
        function printOrderReceipt() {
            var receiptContent = document.getElementById('order-receipt-content').innerHTML;
            var printWrapper = document.getElementById('print-receipt-wrapper');
            if (!receiptContent.trim()) {
                printWrapper.innerHTML = '<div style="color:red;font-weight:bold;padding:20px;">DEBUG: Receipt content is empty!</div>';
            } else {
                printWrapper.innerHTML = '<div class="receipt-print-debug" style="background:#fff;border:2px dashed #007bff;padding:10px;">' + receiptContent + '</div>';
            }
            printWrapper.style.display = 'block';
            console.debug('printOrderReceipt: wrapper prepared; invoking window.print()');
            try { window.print(); } catch (e) { console.error('printOrderReceipt window.print failed', e); }
            setTimeout(function() {
                printWrapper.innerHTML = '';
                printWrapper.style.display = 'none';
            }, 500);
        }

        // Function to download order PDF using fetch to avoid popup blockers and
        // to detect authentication redirects (login) which would return HTML.
        async function downloadOrderPDF(invoiceNo) {
            if (!window.currentOrderData || !window.currentOrderData.id) {
                alert('Unable to download PDF. Order information not available.');
                return;
            }

            const orderId = window.currentOrderData.id;
            const downloadUrl = `/orders/${orderId}/download-pdf-bill`;

            console.debug('downloadOrderPDF: fetching', downloadUrl, 'orderId=', orderId);

            try {
                const resp = await fetch(downloadUrl, {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/pdf, application/octet-stream, */*'
                    }
                });

                // If redirected to login or another HTML page, inform the user
                if (resp.redirected) {
                    const redirectedUrl = resp.url || '';
                    if (redirectedUrl.includes('/login')) {
                        alert('Your session has expired. Please login again and retry the download.');
                        window.location = redirectedUrl;
                        return;
                    }
                }

                const contentType = resp.headers.get('Content-Type') || '';
                // If server returned HTML (e.g., login page), show error
                if (!contentType.includes('pdf')) {
                    const text = await resp.text();
                    console.error('downloadOrderPDF: expected PDF, got:', contentType);
                    alert('Failed to download PDF. Server returned unexpected response. Check console for details.');
                    console.debug(text);
                    return;
                }

                const blob = await resp.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `invoice-${invoiceNo}.pdf`;
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);

            } catch (err) {
                console.error('downloadOrderPDF fetch error', err);
                alert('Failed to download PDF. See console for details.');
            }
        }

        // Add event listeners when document is ready
        document.addEventListener('DOMContentLoaded', function() {
            const orderReceiptModal = document.getElementById('orderReceiptModal');

            // When modal is shown, move focus into it (to the print button or close button)
            orderReceiptModal.addEventListener('shown.bs.modal', function() {
                try {
                    const focusTarget = orderReceiptModal.querySelector('.print-btn, .btn-close, button, a');
                    if (focusTarget && typeof focusTarget.focus === 'function') {
                        focusTarget.focus();
                    } else {
                        orderReceiptModal.focus();
                    }
                } catch (e) {
                    // no-op
                }
            });

            // Listen for modal hide events to clean up
            orderReceiptModal.addEventListener('hidden.bs.modal', function() {
                // Clear any stored order data
                window.currentOrderData = null;

                // Reset modal content to empty state
                document.getElementById('order-receipt-content').innerHTML = `
            <div class="text-center p-4 text-muted">
                <p>Select an order to view details</p>
            </div>
        `;

                // Restore focus to the element that opened the modal (if any)
                try {
                    if (window.lastFocusedElement && typeof window.lastFocusedElement.focus === 'function') {
                        window.lastFocusedElement.focus();
                    }
                } catch (e) {
                    // ignore
                }
                window.lastFocusedElement = null;
            });

            // Note: ESC key handling removed to allow Bootstrap modal native ESC key functionality
            // Bootstrap handles ESC key by default if keyboard option is enabled in modal config
        });

        // Check if we need to auto-show order modal from URL parameter
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const showOrderId = urlParams.get('show_order');

            if (showOrderId) {
                // Remove the parameter from URL without reload
                const url = new URL(window.location.href);
                url.searchParams.delete('show_order');
                window.history.replaceState({}, document.title, url.toString());

                // Load and show the order modal
                setTimeout(() => {
                    loadOrderDetails(showOrderId);
                }, 300);
            }
        });
    </script>
@endpush
