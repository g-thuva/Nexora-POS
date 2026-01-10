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
                    <td><span class="text-muted">{{ $orders->firstItem() + $index }}</span></td>
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
                            <span class="badge bg-info text-dark">{{ $order->creator->name }}</span>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-list flex-nowrap">
                            <button type="button" class="btn btn-white btn-sm" title="View & Print" onclick="viewOrderInModal({{ $order->id }})" data-bs-toggle="modal" data-bs-target="#orderReceiptModal">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667-6 7-10 7s-7.333-2.333-10-7c2.667-4.667 6-7 10-7s7.333 2.333 10 7" /></svg>
                            </button>
                            <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M7 7h-1a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0-2.97-2.97l-8.415 8.385v3h3l8.385-8.415z" /><path d="M16 5l3 3" /></svg>
                            </a>
                            <button type="button" class="btn btn-sm" title="Quick Print" onclick="printOrder({{ $order->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M17 17h2a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2h-14a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0-2-2h-6a2 2 0 0 0-2 2v4" /><rect x="7" y="13" width="10" height="8" rx="2" /></svg>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-4">
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
        <span>{{ $orders->total() }}</span> entries</p>
    <ul class="pagination m-0 ms-auto">
        @if ($orders->onFirstPage())
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
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $orders->previousPageUrl() }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <polyline points="15,6 9,12 15,18" />
                    </svg>
                    prev
                </a>
            </li>
        @endif

        @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
            <li class="page-item {{ $page == $orders->currentPage() ? 'active' : '' }}">
                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
            </li>
        @endforeach

        @if ($orders->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $orders->nextPageUrl() }}">
                    next <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <polyline points="9,6 15,12 9,18" />
                    </svg>
                </a>
            </li>
        @else
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
        @endif
    </ul>
</div>
