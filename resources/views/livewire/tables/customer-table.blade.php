<!-- Professional Customer Table - Styled to match Credit Sales -->
<div>
    <div class="table-responsive">
        <!-- Search and Filter Bar -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <label class="form-label mb-0 me-2">Show:</label>
                    <select wire:model.live="perPage" class="form-select form-select-sm d-inline-block w-auto">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="25">25</option>
                    </select>
                    <span class="text-muted ms-2">entries</span>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <label class="form-label mb-0 me-2">Search:</label>
                <input type="text"
                       wire:model.live="search"
                       class="form-control form-control-sm"
                       style="width: 250px;"
                       placeholder="Search customers..."
                       aria-label="Search customers">
            </div>
        </div>

    <x-spinner.loading-spinner/>

    <!-- Professional Table -->
    <table wire:loading.remove class="table table-vcenter card-table">
        <thead>
            <tr>
                <th class="w-1">#</th>
                <th>Customer Details</th>
                <th>Contact Information</th>
                <th>Registration</th>
                <th>Purchase History</th>
                <th class="w-1">Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($customers as $customer)
            <tr>
                <td class="text-secondary">
                    {{ ($customers->currentPage() - 1) * $customers->perPage() + $loop->iteration }}
                </td>
                <td>
                    <div class="d-flex flex-column">
                        <div class="fw-bold text-dark">{{ $customer->name }}</div>
                        @if($customer->email)
                            <div class="text-secondary small">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z"/>
                                    <path d="M3 7l9 6l9 -6"/>
                                </svg>
                                {{ $customer->email }}
                            </div>
                        @endif
                    </div>
                </td>
                <td>
                    <div class="d-flex flex-column">
                        @if($customer->phone)
                            <div class="text-dark">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs me-1 text-primary" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2"/>
                                </svg>
                                {{ $customer->phone }}
                            </div>
                        @else
                            <span class="text-muted">No phone</span>
                        @endif
                        @if($customer->address)
                            <div class="text-secondary small mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"/>
                                    <path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z"/>
                                </svg>
                                {{ Str::limit($customer->address, 50) }}
                            </div>
                        @else
                            <div class="text-muted small mt-1">No address provided</div>
                        @endif
                    </div>
                </td>
                <td>
                    <div class="d-flex flex-column">
                        <div class="text-dark">{{ $customer->created_at->format('d/m/Y') }}</div>
                        <div class="text-secondary small">{{ $customer->created_at->diffForHumans() }}</div>
                    </div>
                </td>
                <td>
                    <div class="d-flex flex-column">
                        @if(($customer->orders_count ?? 0) > 0)
                            <div class="fw-bold text-success">{{ $customer->orders_count }} Orders</div>
                            <div class="text-secondary small">
                                LKR {{ number_format(($customer->orders_sum_total ?? 0) / 100, 2) }}
                            </div>
                        @else
                            <span class="text-muted">No purchases</span>
                        @endif
                    </div>
                </td>
                <td>
                    <div class="btn-list flex-nowrap">
                        <a href="{{ route('customers.show', $customer) }}"
                           class="btn btn-sm btn-outline-primary"
                           title="View Customer Details">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                            </svg>
                        </a>
                        <a href="{{ route('customers.edit', $customer) }}"
                           class="btn btn-sm btn-outline-warning"
                           title="Edit Customer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                <path d="M16 5l3 3" />
                            </svg>
                        </a>
                        <form method="POST"
                              action="{{ route('customers.destroy', $customer) }}"
                              style="display: inline-block;"
                              onsubmit="return confirm('Are you sure you want to delete this customer?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="btn btn-sm btn-outline-danger"
                                    title="Delete Customer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 7l16 0" />
                                    <path d="M10 11l0 6" />
                                    <path d="M14 11l0 6" />
                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center py-5">
                    <div class="empty">
                        <div class="empty-img">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="64" height="64"
                                viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                            </svg>
                        </div>
                        <p class="empty-title">No customers found</p>
                        <p class="empty-subtitle text-muted">
                            {{ $search ? 'Try adjusting your search terms' : 'Start by adding your first customer' }}
                        </p>
                    </div>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

        <!-- Professional Pagination Footer -->
        @if($customers->hasPages() || $customers->total() > 0)
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-secondary">
                    @if($customers->total() > 0)
                        Showing <strong>{{ $customers->firstItem() }}</strong> to <strong>{{ $customers->lastItem() }}</strong>
                        of <strong>{{ $customers->total() }}</strong> customers
                    @endif
                </div>
                @if($customers->hasPages())
                    <div class="ms-auto">
                        {{ $customers->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
