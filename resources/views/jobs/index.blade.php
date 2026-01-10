@extends('layouts.nexora')

@section('content')
<div class="page-header d-print-none">
    <div class="container-fluid">
        <div class="row g-2 align-items-center mb-3">
            <div class="col">
                <h2 class="page-title">
                    {{ __('Create Job') }}
                </h2>
            </div>
        </div>

        @include('partials._breadcrumbs')
    </div>
</div>

<div class="page-body">
    <div class="container-fluid">
        <x-alert/>

        <div class="row row-cards">
            <form action="{{ route('jobs.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                        <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                        <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" />
                                    </svg>
                                    Quick Stats
                                </h3>

                                <!-- Stats Section -->
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <div class="card card-sm bg-primary-lt">
                                            <div class="card-body text-center">
                                                <div class="text-muted small">Total</div>
                                                <div class="h3 m-0 text-primary">{{ \App\Models\Job::count() }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card card-sm bg-warning-lt">
                                            <div class="card-body text-center">
                                                <div class="text-muted small">Pending</div>
                                                <div class="h3 m-0 text-warning">{{ \App\Models\Job::where('status', 'pending')->count() }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card card-sm bg-info-lt">
                                            <div class="card-body text-center">
                                                <div class="text-muted small">This Month</div>
                                                <div class="h3 m-0 text-info">{{ \App\Models\Job::whereMonth('created_at', now()->month)->count() }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card card-sm bg-success-lt">
                                            <div class="card-body text-center">
                                                <div class="text-muted small">Open</div>
                                                <div class="h3 m-0 text-success">{{ \App\Models\Job::whereIn('status', ['pending','in_progress'])->count() }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tips Section -->
                                <h3 class="card-title text-success mt-4 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M3 12h1m8 -9v1m8 8h1m-15.4 -6.4l.7 .7m12.1 -.7l-.7 .7" />
                                        <path d="M9 16a5 5 0 1 1 6 0a3.5 3.5 0 0 0 -1 3a2 2 0 0 1 -4 0a3.5 3.5 0 0 0 -1 -3" />
                                        <path d="M9.7 17l4.6 0" />
                                    </svg>
                                    Best Practices
                                </h3>

                                <div class="list-group list-group-flush mb-3">
                                    <div class="list-group-item px-0">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="avatar bg-blue text-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="text-truncate">
                                                    <strong>Customer Details</strong>
                                                </div>
                                                <div class="text-muted small">Complete contact info</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="list-group-item px-0">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="avatar bg-orange text-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                                        <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="text-truncate">
                                                    <strong>Select Job Type</strong>
                                                </div>
                                                <div class="text-muted small">Choose service category</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="list-group-item px-0">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="avatar bg-cyan text-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                                        <path d="M12 7l0 5l3 3" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="text-truncate">
                                                    <strong>Set Duration</strong>
                                                </div>
                                                <div class="text-muted small">Estimated completion time</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="list-group-item px-0">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="avatar bg-teal text-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M8 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h5.5" />
                                                        <path d="M16 3l0 4" />
                                                        <path d="M8 3l0 4" />
                                                        <path d="M4 11l16 0" />
                                                        <path d="M11 15l0 .01" />
                                                        <path d="M12 15l0 .01" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="text-truncate">
                                                    <strong>Detailed Notes</strong>
                                                </div>
                                                <div class="text-muted small">Add job requirements</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-success mb-0">
                                    <div class="d-flex">
                                        <div>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M5 12l5 5l10 -10" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="alert-title">Pro Tip!</h4>
                                            <div class="text-muted">Clear job descriptions and accurate duration estimates help improve customer satisfaction and workflow planning.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <div>
                                    <h3 class="card-title">
                                        {{ __('Create New Job') }}
                                    </h3>
                                </div>

                                <div class="card-actions">
                                    <a href="{{ route('jobs.list') }}" class="btn-action" title="View All Jobs">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M9 6l6 6l-6 6" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row row-cards">
                                    <!-- Customer Information Section -->
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <h4 class="text-primary mb-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler me-1" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                                </svg>
                                                Customer Information
                                            </h4>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="new_customer_name" class="form-label required">
                                                {{ __('Customer Name') }}
                                            </label>
                                            <input type="text"
                                                   name="new_customer_name"
                                                   id="new_customer_name"
                                                   class="form-control @error('new_customer_name') is-invalid @enderror"
                                                   value="{{ old('new_customer_name') }}"
                                                   placeholder="Enter customer full name"
                                                   required>
                                            @error('new_customer_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="mb-3">
                                            <label for="new_customer_phone" class="form-label">
                                                {{ __('Phone Number') }}
                                            </label>
                                            <input type="text"
                                                   name="new_customer_phone"
                                                   id="new_customer_phone"
                                                   class="form-control @error('new_customer_phone') is-invalid @enderror"
                                                   value="{{ old('new_customer_phone') }}"
                                                   placeholder="Enter phone number">
                                            @error('new_customer_phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="mb-3">
                                            <label for="new_customer_address" class="form-label">
                                                {{ __('Address') }}
                                            </label>
                                            <input type="text"
                                                   name="new_customer_address"
                                                   id="new_customer_address"
                                                   class="form-control @error('new_customer_address') is-invalid @enderror"
                                                   value="{{ old('new_customer_address') }}"
                                                   placeholder="Enter customer address">
                                            @error('new_customer_address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Job Details Section -->
                                    <div class="col-12">
                                        <hr class="my-3">
                                        <div class="mb-3">
                                            <h4 class="text-success mb-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler me-1" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                                    <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                                </svg>
                                                Service Details
                                            </h4>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="mb-3">
                                            <label for="job_type_id" class="form-label">
                                                {{ __('Job Type') }}
                                            </label>
                                            <select name="job_type_id"
                                                    id="job_type_id"
                                                    class="form-select @error('job_type_id') is-invalid @enderror">
                                                <option value="">-- {{ __('Select job type') }} --</option>
                                                @foreach(\App\Models\JobType::all() as $type)
                                                    <option value="{{ $type->id }}"
                                                            data-default="{{ $type->default_days ?? '' }}"
                                                            @if(old('job_type_id') == $type->id) selected @endif>
                                                        {{ $type->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="form-hint">Manage job types in Settings → Job Types</small>
                                            @error('job_type_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="mb-3">
                                            <label for="estimated_duration" class="form-label">
                                                {{ __('Estimated Duration (Days)') }}
                                            </label>
                                            <input type="number"
                                                   name="estimated_duration"
                                                   id="estimated_duration"
                                                   class="form-control @error('estimated_duration') is-invalid @enderror"
                                                   min="0"
                                                   value="{{ old('estimated_duration') }}"
                                                   placeholder="Enter number of days">
                                            @error('estimated_duration')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">
                                                {{ __('Status') }}
                                            </label>
                                            <select name="status"
                                                    id="status"
                                                    class="form-select @error('status') is-invalid @enderror">
                                                @foreach(\App\Models\Job::statuses() as $status)
                                                    <option value="{{ $status }}" @if(old('status') == $status) selected @endif>
                                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description" class="form-label">
                                                {{ __('Job Description') }}
                                            </label>
                                            <textarea name="description"
                                                      id="description"
                                                      rows="6"
                                                      class="form-control @error('description') is-invalid @enderror"
                                                      placeholder="Enter detailed job description, requirements, and any special instructions...">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-end">
                                <button type="reset" class="btn btn-outline-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                                        <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                                    </svg>
                                    {{ __('Reset') }}
                                </button>

                                <button type="submit" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                        <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                        <path d="M14 4l0 4l-6 0l0 -4" />
                                    </svg>
                                    {{ __('Create Job') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

@push('page-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('job_type_id');
            const estInput = document.getElementById('estimated_duration');

            if (!typeSelect || !estInput) return;

            typeSelect.addEventListener('change', function() {
                const opt = typeSelect.options[typeSelect.selectedIndex];
                const def = opt ? opt.dataset.default : null;

                // Only autofill if estimated duration is empty
                if ((estInput.value === null || estInput.value === '' ) && def !== undefined && def !== '') {
                    estInput.value = def;
                }
            });
        });
    </script>
@endpush

@endsection
