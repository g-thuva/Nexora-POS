@extends('layouts.admin')

@section('content')
<div class="page-header d-print-none">
    <div class="container-fluid">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Admin Panel
                </div>
                <h2 class="page-title">
                    Subscription Management
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">
                            Subscription Management
                        </h3>
                        <p class="text-muted">Manage shop subscriptions and renewal dates</p>
                    </div>
                </div>

                <div class="card-body border-bottom py-3">
                    <form method="GET" action="{{ route('admin.shops.subscriptions') }}">
                        <div class="row g-2">
                            <div class="col">
                                <div class="input-icon">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <circle cx="10" cy="10" r="7"></circle>
                                            <line x1="21" y1="21" x2="15" y2="15"></line>
                                        </svg>
                                    </span>
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        class="form-control" placeholder="Search shop name or owner...">
                                </div>
                            </div>
                            <div class="col-auto">
                                <select name="status" class="form-select">
                                    <option value="">All Statuses</option>
                                    <option value="trial" {{ request('status') == 'trial' ? 'selected' : '' }}>Trial</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <circle cx="10" cy="10" r="7"></circle>
                                        <line x1="21" y1="21" x2="15" y2="15"></line>
                                    </svg>
                                    Search
                                </button>
                            </div>
                            @if (request('search') || request('status'))
                                <div class="col-auto">
                                    <a href="{{ route('admin.shops.subscriptions') }}" class="btn btn-secondary">
                                        Clear
                                    </a>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="table-responsive" style="padding-bottom: 180px;">
                    <table class="table card-table table-vcenter text-nowrap datatable">
                        <thead>
                            <tr>
                                <th>Shop Name</th>
                                <th>Owner</th>
                                <th>Users</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Days Left</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($shops as $shop)
                                @php
                                    $daysLeft = $shop->subscription_end_date ? now()->diffInDays($shop->subscription_end_date, false) : null;
                                    $isExpiring = $daysLeft !== null && $daysLeft <= 7 && $daysLeft >= 0;
                                    $isExpired = $daysLeft !== null && $daysLeft < 0;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex py-1 align-items-center">
                                            <span class="avatar me-2"
                                                style="background-image: url({{ $shop->photo ? asset('storage/' . $shop->photo) : asset('static/shop-icon.png') }})"></span>
                                            <div class="flex-fill">
                                                <div class="font-weight-medium">{{ $shop->name }}</div>
                                                <div class="text-muted"><small>{{ $shop->slug }}</small></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($shop->owner)
                                            <div>{{ $shop->owner->name }}</div>
                                            <div class="text-muted"><small>{{ $shop->owner->email }}</small></div>
                                        @else
                                            <span class="text-muted">No owner</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-blue-lt">{{ $shop->users_count }} users</span>
                                    </td>
                                    <td>
                                        @if ($shop->subscription_start_date)
                                            {{ $shop->subscription_start_date->format('M d, Y') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($shop->subscription_end_date)
                                            {{ $shop->subscription_end_date->format('M d, Y') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($daysLeft !== null)
                                            @if ($isExpired)
                                                <span class="badge bg-red-lt">Expired {{ abs($daysLeft) }} days ago</span>
                                            @elseif ($isExpiring)
                                                <span class="badge bg-warning-lt">{{ $daysLeft }} days left</span>
                                            @else
                                                <span class="badge bg-green-lt">{{ $daysLeft }} days</span>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($shop->subscription_status == 'trial')
                                            <span class="badge bg-info-lt">Trial</span>
                                        @elseif($shop->subscription_status == 'active')
                                            <span class="badge bg-success-lt">Active</span>
                                        @elseif($shop->subscription_status == 'expired')
                                            <span class="badge bg-danger-lt">Expired</span>
                                        @elseif($shop->subscription_status == 'cancelled')
                                            <span class="badge bg-secondary-lt">Cancelled</span>
                                        @else
                                            <span class="badge bg-gray-lt">Unknown</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-secondary dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-bs-target="#extendModal{{ $shop->id }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-calendar-plus me-2"
                                                            width="24" height="24" viewBox="0 0 24 24"
                                                            stroke-width="2" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <rect x="4" y="5" width="16" height="16"
                                                                rx="2"></rect>
                                                            <line x1="16" y1="3" x2="16" y2="7">
                                                            </line>
                                                            <line x1="8" y1="3" x2="8" y2="7">
                                                            </line>
                                                            <line x1="4" y1="11" x2="20" y2="11">
                                                            </line>
                                                            <line x1="10" y1="16" x2="14" y2="16">
                                                            </line>
                                                            <line x1="12" y1="14" x2="12" y2="18">
                                                            </line>
                                                        </svg>
                                                        Extend Subscription
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-bs-target="#statusModal{{ $shop->id }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-edit me-2" width="24"
                                                            height="24" viewBox="0 0 24 24" stroke-width="2"
                                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <path
                                                                d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1">
                                                            </path>
                                                            <path
                                                                d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z">
                                                            </path>
                                                            <path d="M16 5l3 3"></path>
                                                        </svg>
                                                        Change Status
                                                    </a>
                                                </li>
                                                @if ($shop->is_suspended)
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <span class="dropdown-item text-muted">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="icon icon-tabler icon-tabler-lock me-2"
                                                                width="24" height="24" viewBox="0 0 24 24"
                                                                stroke-width="2" stroke="currentColor" fill="none"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                <rect x="5" y="11" width="14" height="10"
                                                                    rx="2"></rect>
                                                                <circle cx="12" cy="16" r="1"></circle>
                                                                <path d="M8 11v-4a4 4 0 0 1 8 0v4"></path>
                                                            </svg>
                                                            Shop is Suspended
                                                        </span>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-database-off mb-2" width="48"
                                            height="48" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M12 6v-2"></path>
                                            <path d="M4 6v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-6"></path>
                                            <path d="M4 12v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-6"></path>
                                            <path d="M12 12v2"></path>
                                        </svg>
                                        <div>No shops found</div>
                                        @if (request('search') || request('status'))
                                            <a href="{{ route('admin.shops.subscriptions') }}"
                                                class="btn btn-sm btn-link">Clear filters</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer d-flex align-items-center">
                    {!! $shops->links('pagination::bootstrap-5') !!}
                </div>
            </div>

            {{-- Modals placed outside the table for proper DOM structure --}}
            @foreach($shops as $shop)
                {{-- Extend Subscription Modal --}}
                <div class="modal fade" id="extendModal{{ $shop->id }}" tabindex="-1"
                    aria-labelledby="extendModalLabel{{ $shop->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="extendModalLabel{{ $shop->id }}">Extend Subscription</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form method="POST" action="{{ route('admin.shops.extend-subscription', $shop->id) }}">
                                @csrf
                                <div class="modal-body">
                                    <p>Extend subscription for: <strong>{{ $shop->name }}</strong></p>
                                    <div class="mb-3">
                                        <label class="form-label" for="extend_days{{ $shop->id }}">Extend by</label>
                                        <select name="extend_days" id="extend_days{{ $shop->id }}" class="form-select" required>
                                            <option value="">Select duration...</option>
                                            <option value="30">30 days (1 month)</option>
                                            <option value="90">90 days (3 months)</option>
                                            <option value="180">180 days (6 months)</option>
                                            <option value="365">365 days (1 year)</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="notes{{ $shop->id }}">Notes (optional)</label>
                                        <textarea name="notes" id="notes{{ $shop->id }}" class="form-control" rows="2"
                                            placeholder="Add any notes about this extension..."></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-white">Extend Subscription</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Change Status Modal --}}
                <div class="modal fade" id="statusModal{{ $shop->id }}" tabindex="-1"
                    aria-labelledby="statusModalLabel{{ $shop->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="statusModalLabel{{ $shop->id }}">Change Subscription Status</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form method="POST" action="{{ route('admin.shops.change-status', $shop->id) }}">
                                @csrf
                                <div class="modal-body">
                                    <p>Change status for: <strong>{{ $shop->name }}</strong></p>
                                    <div class="mb-3">
                                        <label class="form-label" for="status{{ $shop->id }}">New Status</label>
                                        <select name="status" id="status{{ $shop->id }}" class="form-select" required>
                                            <option value="">Select status...</option>
                                            <option value="trial" {{ $shop->subscription_status == 'trial' ? 'selected' : '' }}>Trial</option>
                                            <option value="active" {{ $shop->subscription_status == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="expired" {{ $shop->subscription_status == 'expired' ? 'selected' : '' }}>Expired</option>
                                            <option value="cancelled" {{ $shop->subscription_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="reason{{ $shop->id }}">Reason (optional)</label>
                                        <textarea name="reason" id="reason{{ $shop->id }}" class="form-control" rows="2"
                                            placeholder="Add reason for status change..."></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-white">Update Status</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@section('scripts')
@endsection
@endsection
