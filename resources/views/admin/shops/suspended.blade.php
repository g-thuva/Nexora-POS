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
                    Suspended Shops
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
                                Suspended Shops List
                            </h3>
                            <p class="text-muted">View and manage suspended shops</p>
                        </div>
                    </div>

                    <div class="card-body border-bottom py-3">
                        <form method="GET" action="{{ route('admin.shops.suspended') }}">
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
                                @if (request('search'))
                                    <div class="col-auto">
                                        <a href="{{ route('admin.shops.suspended') }}" class="btn btn-secondary">
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
                                    <th>Suspended Date</th>
                                    <th>Suspended By</th>
                                    <th>Reason</th>
                                    <th>Subscription Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($shops as $shop)
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
                                            @if ($shop->suspended_at)
                                                <div>{{ $shop->suspended_at->format('M d, Y') }}</div>
                                                <div class="text-muted">
                                                    <small>{{ $shop->suspended_at->diffForHumans() }}</small>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($shop->suspendedBy)
                                                <div>{{ $shop->suspendedBy->name }}</div>
                                                <div class="text-muted"><small>{{ $shop->suspendedBy->email }}</small>
                                                </div>
                                            @else
                                                <span class="badge bg-warning-lt">Auto-suspended</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($shop->suspension_reason)
                                                <div class="text-truncate" style="max-width: 200px;"
                                                    title="{{ $shop->suspension_reason }}">
                                                    {{ $shop->suspension_reason }}
                                                </div>
                                            @else
                                                <span class="text-muted">No reason provided</span>
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

                                            @if ($shop->subscription_end_date)
                                                <div class="text-muted">
                                                    <small>Ends: {{ $shop->subscription_end_date->format('M d, Y') }}</small>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-white dropdown-toggle"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                            data-bs-target="#unsuspendModal{{ $shop->id }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="icon icon-tabler icon-tabler-lock-open me-2"
                                                                width="24" height="24" viewBox="0 0 24 24"
                                                                stroke-width="2" stroke="currentColor" fill="none"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                <rect x="5" y="11" width="14" height="10"
                                                                    rx="2"></rect>
                                                                <circle cx="12" cy="16" r="1"></circle>
                                                                <path d="M8 11v-5a4 4 0 0 1 8 0"></path>
                                                            </svg>
                                                            Unsuspend Shop
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.shops.subscriptions') }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="icon icon-tabler icon-tabler-calendar me-2"
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
                                                            </svg>
                                                            Manage Subscription
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Unsuspend Modal --}}
                                    <div class="modal modal-blur fade" id="unsuspendModal{{ $shop->id }}" tabindex="-1"
                                        role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Unsuspend Shop</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form method="POST" action="{{ route('admin.shops.unsuspend', $shop->id) }}">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="alert alert-warning" role="alert">
                                                            <h4 class="alert-title">Warning!</h4>
                                                            <p>You are about to unsuspend: <strong>{{ $shop->name }}</strong>
                                                            </p>
                                                            <p>This will:</p>
                                                            <ul>
                                                                <li>Reactivate the shop</li>
                                                                <li>Allow the shop owner to login again</li>
                                                                <li>Restore access for all shop users ({{ $shop->users_count }} users)</li>
                                                            </ul>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Also unsuspend all shop users?</label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="unsuspend_users" value="1" id="unsuspendUsers{{ $shop->id }}" checked>
                                                                <label class="form-check-label" for="unsuspendUsers{{ $shop->id }}">
                                                                    Yes, unsuspend all {{ $shop->users_count }} users belonging to this shop
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Notes (optional)</label>
                                                            <textarea name="notes" class="form-control" rows="2"
                                                                placeholder="Add any notes about unsuspending this shop..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-white">Unsuspend
                                                            Shop</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-5">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-check mb-2" width="48"
                                                height="48" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M5 12l5 5l10 -10"></path>
                                            </svg>
                                            <div>No suspended shops found</div>
                                            @if (request('search'))
                                                <a href="{{ route('admin.shops.suspended') }}"
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
            </div>
        </div>
    </div>
</div>
@endsection
