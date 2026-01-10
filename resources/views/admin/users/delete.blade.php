@extends('layouts.admin')

@section('content')
<div class="page-header d-print-none">
    <div class="container-fluid">
        <div class="row g-2 align-items-center mb-3">
            <div class="col">
                <div class="page-pretitle">Admin Panel</div>
                <h2 class="page-title">Delete User Account</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-ghost-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M5 12l14 0"/>
                            <path d="M5 12l6 6"/>
                            <path d="M5 12l6 -6"/>
                        </svg>
                        Back to Users
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-fluid">
        <div class="row g-4">
            <!-- Main Form Column -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-status-top bg-danger"></div>
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-danger" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M4 7l16 0"/>
                                <path d="M10 11l0 6"/>
                                <path d="M14 11l0 6"/>
                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/>
                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/>
                            </svg>
                            Permanently Delete User
                        </h3>
                    </div>

                    <div class="card-body">
                        <!-- User Information Card -->
                        <div class="card mb-4 bg-red-lt">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <span class="avatar avatar-lg me-3 bg-danger text-white">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </span>
                                    <div class="flex-fill">
                                        <div class="h2 mb-1">{{ $user->name }}</div>
                                        <div class="text-muted">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z"/>
                                                <path d="M3 7l9 6l9 -6"/>
                                            </svg>
                                            {{ $user->email }}
                                        </div>
                                        <div class="text-muted mt-1">
                                            <strong>Username:</strong> {{ $user->username }}
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="badge bg-blue mb-1">{{ str_replace('_', ' ', ucfirst($user->role)) }}</div><br>
                                        @if($user->shop)
                                            <div class="badge bg-purple">{{ $user->shop->name }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Warning Messages -->
                        <div class="alert alert-danger mb-4">
                            <div class="d-flex">
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 9v4"/>
                                        <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z"/>
                                        <path d="M12 16h.01"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="alert-title">⚠️ Critical Warning: Permanent Deletion!</h4>
                                    <div class="text-secondary">
                                        <strong>This action CANNOT be undone!</strong> Deleting <strong>{{ $user->name }}</strong> will:
                                        <ul class="mb-0 mt-2">
                                            <li><strong>Permanently remove</strong> all user data from the system</li>
                                            <li><strong>Delete associated records</strong> including orders, payments, and activities</li>
                                            <li><strong>Immediately log out</strong> the user from all sessions</li>
                                            <li><strong>Free up</strong> the username and email for future use</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($user->role === 'shop_owner' && $user->ownedShop)
                        <div class="alert alert-danger mb-4">
                            <div class="d-flex">
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="12" cy="12" r="9"/>
                                        <line x1="12" y1="8" x2="12" y2="12"/>
                                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="alert-title">⚠️ CRITICAL WARNING: Shop Owner Deletion</h4>
                                    <div class="text-secondary mb-3">
                                        <strong>This user owns "{{ $user->ownedShop->name }}"</strong>
                                    </div>
                                    @if($relatedUsers->count() > 0)
                                        <div class="alert alert-warning mb-0">
                                            <h5 class="mb-2">⚠️ The following {{ $relatedUsers->count() }} user(s) will also be PERMANENTLY DELETED:</h5>
                                            <ul class="mb-0">
                                                @foreach($relatedUsers as $relatedUser)
                                                    <li>
                                                        <strong>{{ $relatedUser->name }}</strong>
                                                        ({{ $relatedUser->email }}) -
                                                        <span class="badge bg-{{ $relatedUser->role === 'manager' ? 'green' : 'yellow' }}">
                                                            {{ ucfirst(str_replace('_', ' ', $relatedUser->role)) }}
                                                        </span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="mt-3">
                                            <strong class="text-danger">⚠️ Shop "{{ $shopToDelete->name }}" will be completely removed from the system.</strong>
                                        </div>
                                    @else
                                        <div class="mt-2">
                                            <strong class="text-danger">⚠️ Shop "{{ $shopToDelete->name }}" will be completely removed from the system.</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- User Statistics -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"/>
                                                    <path d="M16 3l0 4"/>
                                                    <path d="M8 3l0 4"/>
                                                    <path d="M4 11l16 0"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-muted">Member Since</div>
                                                <div class="h3 m-0">{{ $user->created_at->format('M Y') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2"/>
                                                    <path d="M20 12h-13l3 -3m0 6l-3 -3"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-muted">Last Login</div>
                                                <div class="h3 m-0">
                                                    @if($user->last_login_at)
                                                        {{ $user->last_login_at->diffForHumans() }}
                                                    @else
                                                        Never
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <circle cx="12" cy="12" r="9"/>
                                                    @if($user->is_suspended)
                                                        <line x1="9" y1="9" x2="15" y2="15"/>
                                                        <line x1="15" y1="9" x2="9" y2="15"/>
                                                    @else
                                                        <path d="M9 12l2 2l4 -4"/>
                                                    @endif
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-muted">Status</div>
                                                <div class="h3 m-0">
                                                    @if($user->is_suspended)
                                                        <span class="badge bg-danger">Suspended</span>
                                                    @else
                                                        <span class="badge bg-success">Active</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Confirmation Form -->
                        <form action="{{ route('admin.users.delete', $user) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <div class="mb-4">
                                <label class="form-label required">Type "DELETE" to confirm</label>
                                <input type="text" name="confirm_delete" class="form-control form-control-lg @error('confirm_delete') is-invalid @enderror"
                                       placeholder="Type DELETE in capital letters" required>
                                @error('confirm_delete')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-hint text-danger">You must type <strong>DELETE</strong> (all caps) to proceed with deletion.</small>
                            </div>

                            <div class="mb-4">
                                <label class="form-check">
                                    <input type="checkbox" name="confirm_understand" class="form-check-input" required>
                                    <span class="form-check-label">
                                        I understand that this action is <strong>permanent and cannot be undone</strong>
                                        @if($user->role === 'shop_owner' && $relatedUsers->count() > 0)
                                            <br><strong class="text-danger">and will delete {{ $relatedUsers->count() }} additional user(s) and the entire shop</strong>
                                        @elseif($user->role === 'shop_owner' && $shopToDelete)
                                            <br><strong class="text-danger">and will delete the entire shop</strong>
                                        @endif
                                    </span>
                                </label>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M4 7l16 0"/>
                                        <path d="M10 11l0 6"/>
                                        <path d="M14 11l0 6"/>
                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/>
                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/>
                                    </svg>
                                    @if($user->role === 'shop_owner' && $relatedUsers->count() > 0)
                                        Delete User, {{ $relatedUsers->count() }} Related User(s) & Shop
                                    @elseif($user->role === 'shop_owner' && $shopToDelete)
                                        Delete User & Shop
                                    @else
                                        Permanently Delete User
                                    @endif
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="btn">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Side Information Column -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Alternative Actions</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h4 class="text-muted">Consider These Options</h4>
                            <p class="text-muted small">Instead of permanently deleting this user, you might want to:</p>
                        </div>

                        @if(!$user->is_suspended)
                        <a href="{{ route('admin.users.suspend', $user) }}" class="btn btn-white w-100 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                <line x1="9" y1="9" x2="15" y2="15"/>
                                <line x1="15" y1="9" x2="9" y2="15"/>
                            </svg>
                            Suspend Account Instead
                        </a>
                        <small class="d-block text-muted mb-3">Temporarily disable access without losing data</small>
                        @endif

                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-ghost-primary w-100 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <circle cx="12" cy="12" r="2"/>
                                <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7"/>
                            </svg>
                            View User Details
                        </a>
                        <small class="d-block text-muted mb-3">Review user information before deciding</small>

                        <hr>

                        <div class="alert alert-info mb-0">
                            <div class="d-flex">
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="12" cy="12" r="9"/>
                                        <line x1="12" y1="8" x2="12" y2="12"/>
                                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="alert-title">Need Help?</h4>
                                    <div class="text-secondary small">
                                        Contact system administrator at<br>
                                        <strong>077 022 1046</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
