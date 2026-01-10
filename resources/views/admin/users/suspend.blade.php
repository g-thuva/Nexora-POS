@extends('layouts.admin')

@section('content')
<div class="page-header d-print-none">
    <div class="container-fluid">
        <div class="row g-2 align-items-center mb-3">
            <div class="col">
                <div class="page-pretitle">Admin Panel</div>
                <h2 class="page-title">{{ $user->is_suspended ? 'Unsuspend' : 'Suspend' }} User Account</h2>
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
                    <div class="card-status-top {{ $user->is_suspended ? 'bg-green' : 'bg-danger' }}"></div>
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 {{ $user->is_suspended ? 'text-green' : 'text-danger' }}" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"/>
                                @if($user->is_suspended)
                                    <path d="M9 12l6 0"/>
                                @else
                                    <path d="M9 12h6"/>
                                @endif
                            </svg>
                            {{ $user->is_suspended ? 'Lift Account Suspension' : 'Account Suspension' }}
                        </h3>
                    </div>

                    <div class="card-body">
                        <!-- User Information Card -->
                        <div class="card mb-4 {{ $user->is_suspended ? 'bg-green-lt' : 'bg-red-lt' }}">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <span class="avatar avatar-lg me-3 {{ $user->is_suspended ? 'bg-green' : 'bg-danger' }} text-white">
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
                                    </div>
                                    <div class="text-end">
                                        <div class="badge bg-blue mb-1">{{ str_replace('_', ' ', ucfirst($user->role)) }}</div><br>
                                        @if($user->is_suspended)
                                            <div class="badge bg-danger">Currently Suspended</div>
                                        @else
                                            <div class="badge bg-green">Active</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($user->is_suspended)
                            <!-- Current Suspension Details -->
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
                                        <h4 class="alert-title">Current Suspension Details</h4>
                                        <div class="text-secondary">
                                            <strong>Reason:</strong> {{ $user->suspension_reason }}<br>
                                            <strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $user->suspension_type)) }}<br>
                                            <strong>Suspended On:</strong> {{ $user->suspended_at->format('F d, Y \a\t H:i A') }}<br>
                                            @if($user->suspension_ends_at)
                                                <strong>Expires:</strong> {{ $user->suspension_ends_at->format('F d, Y \a\t H:i A') }} ({{ $user->suspension_ends_at->diffForHumans() }})<br>
                                            @endif
                                            @if($user->suspendedBy)
                                                <strong>Suspended By:</strong> {{ $user->suspendedBy->name }}<br>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Unsuspend Form -->
                            <form action="{{ route('admin.users.unsuspend', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to unsuspend {{ $user->name }}? They will be able to log in immediately.');">
                                @csrf

                                <div class="alert alert-info">
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
                                            <h4 class="alert-title">Unsuspend User</h4>
                                            <div class="text-secondary">
                                                Lifting this suspension will:
                                                <ul class="mb-0 mt-2">
                                                    <li>Allow the user to log in immediately</li>
                                                    <li>Clear all suspension records</li>
                                                    <li>Restore full account access</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6z"/>
                                            <path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0"/>
                                            <path d="M8 11v-5a4 4 0 0 1 8 0"/>
                                        </svg>
                                        Unsuspend User
                                    </button>
                                    <a href="{{ route('admin.users.index') }}" class="btn">Cancel</a>
                                </div>
                            </form>
                        @else
                            <!-- Suspension Form -->
                            <form action="{{ route('admin.users.suspend.store', $user) }}" method="POST">
                                @csrf

                            <div class="mb-4">
                                <label class="form-label required">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"/>
                                        <path d="M12 8v4"/>
                                        <path d="M12 16h.01"/>
                                    </svg>
                                    Suspension Reason
                                </label>
                                <textarea class="form-control @error('suspension_reason') is-invalid @enderror"
                                          name="suspension_reason"
                                          rows="4"
                                          required
                                          placeholder="Enter detailed reason for suspension...">{{ old('suspension_reason', $user->suspension_reason) }}</textarea>
                                @error('suspension_reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-hint">This reason will be displayed to the user when they try to log in.</small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label required">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"/>
                                        <path d="M16 3l0 4"/>
                                        <path d="M8 3l0 4"/>
                                        <path d="M4 11l16 0"/>
                                    </svg>
                                    Suspension Type
                                </label>
                                <select class="form-select @error('suspension_type') is-invalid @enderror"
                                        name="suspension_type"
                                        id="suspensionType"
                                        required>
                                    <option value="">Choose suspension type...</option>
                                    <option value="days" {{ old('suspension_type', $user->suspension_type) == 'days' ? 'selected' : '' }}>
                                        Temporary (Days)
                                    </option>
                                    <option value="months" {{ old('suspension_type', $user->suspension_type) == 'months' ? 'selected' : '' }}>
                                        Temporary (Months)
                                    </option>
                                    <option value="until_payment" {{ old('suspension_type', $user->suspension_type) == 'until_payment' ? 'selected' : '' }}>
                                        Until Payment
                                    </option>
                                    <option value="lifetime" {{ old('suspension_type', $user->suspension_type) == 'lifetime' ? 'selected' : '' }}>
                                        Lifetime Ban
                                    </option>
                                    <option value="manual" {{ old('suspension_type', $user->suspension_type) == 'manual' ? 'selected' : '' }}>
                                        Manual Review Required
                                    </option>
                                </select>
                                @error('suspension_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4" id="durationField" style="display: none;">
                                <label class="form-label required">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"/>
                                        <path d="M12 7v5l3 3"/>
                                    </svg>
                                    Duration <span id="durationLabel">(Days)</span>
                                </label>
                                <input type="number"
                                       class="form-control @error('suspension_duration') is-invalid @enderror"
                                       name="suspension_duration"
                                       id="suspensionDuration"
                                       min="1"
                                       value="{{ old('suspension_duration', $user->suspension_duration) }}"
                                       placeholder="Enter number">
                                @error('suspension_duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-hint" id="durationHint">User will be automatically unsuspended after this period.</small>
                            </div>

                            <!-- Warning Messages -->
                            <div class="alert alert-danger">
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
                                        <h4 class="alert-title">⚠️ Account Suspension Warning!</h4>
                                        <div class="text-secondary">
                                            Suspending <strong>{{ $user->name }}</strong> will:
                                            <ul class="mb-0 mt-2">
                                                <li>Immediately <strong>log them out</strong> of all sessions</li>
                                                <li><strong>Block all login attempts</strong> with the suspension reason displayed</li>
                                                <li>Prevent any system access until suspension is lifted</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-footer">
                                <button type="submit" class="btn btn-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"/>
                                        <path d="M9 12h6"/>
                                    </svg>
                                    Suspend User Account
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-link">Cancel</a>
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Information Sidebar -->
            <div class="col-lg-4">
                <!-- Suspension Types -->
                <div class="card mb-3">
                    <div class="card-status-top bg-info"></div>
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-info" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"/>
                                <path d="M12 9h.01"/>
                                <path d="M11 12h1v4h1"/>
                            </svg>
                            Suspension Types
                        </h3>
                    </div>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="badge bg-blue">Days</span>
                                </div>
                                <div class="col">
                                    <small>Temporary suspension for specified number of days</small>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="badge bg-azure">Months</span>
                                </div>
                                <div class="col">
                                    <small>Temporary suspension for specified number of months</small>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="badge bg-yellow">Payment</span>
                                </div>
                                <div class="col">
                                    <small>Suspended until payment is received</small>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="badge bg-red">Lifetime</span>
                                </div>
                                <div class="col">
                                    <small>Permanent ban, requires manual unsuspension</small>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="badge bg-purple">Manual</span>
                                </div>
                                <div class="col">
                                    <small>Requires admin review to lift suspension</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Status -->
                @if($user->is_suspended)
                <div class="card mb-3">
                    <div class="card-status-top bg-danger"></div>
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-danger" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 9v4"/>
                                <path d="M12 16h.01"/>
                                <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z"/>
                            </svg>
                            Current Suspension
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <span class="text-muted">Type:</span>
                            <strong class="text-capitalize">{{ str_replace('_', ' ', $user->suspension_type) }}</strong>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">Suspended:</span>
                            <strong>{{ $user->suspended_at ? $user->suspended_at->diffForHumans() : 'N/A' }}</strong>
                        </div>
                        @if($user->suspension_ends_at)
                        <div class="mb-2">
                            <span class="text-muted">Ends:</span>
                            <strong>{{ $user->suspension_ends_at->format('M d, Y H:i') }}</strong>
                        </div>
                        @endif
                        <div>
                            <span class="text-muted">By:</span>
                            <strong>{{ $user->suspendedBy->name ?? 'System' }}</strong>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Quick Actions -->
                @if($user->is_suspended)
                <div class="card">
                    <div class="card-status-top bg-success"></div>
                    <div class="card-header">
                        <h3 class="card-title">Quick Actions</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.users.unsuspend', $user) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-white w-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M5 12l5 5l10 -10"/>
                                </svg>
                                Lift Suspension Now
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const suspensionType = document.getElementById('suspensionType');
        const durationField = document.getElementById('durationField');
        const durationInput = document.getElementById('suspensionDuration');
        const durationLabel = document.getElementById('durationLabel');
        const durationHint = document.getElementById('durationHint');

        function updateDurationField() {
            const type = suspensionType.value;

            if (type === 'days' || type === 'months') {
                durationField.style.display = 'block';
                durationInput.required = true;

                if (type === 'days') {
                    durationLabel.textContent = '(Days)';
                    durationHint.textContent = 'User will be automatically unsuspended after this many days.';
                } else {
                    durationLabel.textContent = '(Months)';
                    durationHint.textContent = 'User will be automatically unsuspended after this many months.';
                }
            } else {
                durationField.style.display = 'none';
                durationInput.required = false;
                durationInput.value = '';
            }
        }

        suspensionType.addEventListener('change', updateDurationField);
        updateDurationField(); // Check on page load
    });
</script>
@endpush
@endsection
