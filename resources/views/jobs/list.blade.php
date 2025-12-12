@extends('layouts.nexora')

@section('content')
<div class="page-wrapper">
    <div class="container-fluid py-4">

        <div class="page-header d-print-none mb-3">
            <div class="container-fluid">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <div class="page-pretitle">{{ __('Services') }}</div>
                        <h2 class="page-title">{{ __('All Jobs') }}</h2>
                        <p class="text-muted small">View and manage all jobs in the system.</p>
                    </div>
                    <div class="col-auto ms-auto d-print-none">
                        <div class="btn-list">
                            <a href="{{ route('jobs.index') }}" class="btn btn-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14"/><path d="M5 12l14 0"/></svg>
                                {{ __('Create New Job') }}
                            </a>
                            <a href="{{ route('job-letterhead.index') }}" class="btn btn-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/></svg>
                                {{ __('Job Letterhead') }}
                            </a>
                        </div>
                    </div>
                </div>

                @include('partials._breadcrumbs')
            </div>
        </div>

        <x-alert/>

        {{-- Summary cards --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card p-3">
                    <div class="text-muted small">TOTAL JOBS</div>
                    <div class="h5">{{ \App\Models\Job::count() }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <div class="text-muted small">PENDING JOBS</div>
                    <div class="h5">{{ \App\Models\Job::where('status', 'pending')->count() }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <div class="text-muted small">IN PROGRESS</div>
                    <div class="h5">{{ \App\Models\Job::where('status', 'in_progress')->count() }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <div class="text-muted small">COMPLETED</div>
                    <div class="h5">{{ \App\Models\Job::where('status', 'completed')->count() }}</div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('jobs.list') }}" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" onchange="this.form.submit()">
                                    <option value="">All Statuses</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="on_hold" {{ request('status') === 'on_hold' ? 'selected' : '' }}>On Hold</option>
                                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Search</label>
                                <input type="text" name="search" class="form-control" placeholder="Ref, Customer, Description..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Date From</label>
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Date To</label>
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="10" cy="10" r="7"/><path d="M21 21l-6 -6"/></svg>
                                    Apply Filters
                                </button>
                                <a href="{{ route('jobs.list') }}" class="btn btn-white">Clear Filters</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Jobs Table --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Jobs List</h3>
                        <div class="card-actions">
                            <span class="text-muted">{{ $jobs->total() }} total jobs</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-vcenter table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('Ref') }}</th>
                                        <th>{{ __('Customer') }}</th>
                                        <th>{{ __('Phone') }}</th>
                                        <th>{{ __('Type') }}</th>
                                        <th>{{ __('Description') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Duration') }}</th>
                                        <th>{{ __('Created') }}</th>
                                        <th class="text-end">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($jobs as $job)
                                    <tr>
                                        <td><strong class="text-primary">{{ $job->reference_number }}</strong></td>
                                        <td>
                                            <div>{{ $job->customer->name ?? 'N/A' }}</div>
                                            @if($job->customer && $job->customer->email)
                                                <div class="text-muted small">{{ $job->customer->email }}</div>
                                            @endif
                                        </td>
                                        <td>{{ $job->customer->phone ?? 'N/A' }}</td>
                                        <td>{{ $job->jobType->name ?? $job->type ?? 'N/A' }}</td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 200px;" title="{{ $job->description }}">
                                                {{ $job->description }}
                                            </div>
                                        </td>
                                        <td>
                                            <form action="{{ route('jobs.update', $job) }}" method="POST" class="d-inline-block" onchange="this.submit()">
                                                @csrf
                                                @method('PUT')
                                                <select name="status" class="form-select form-select-sm" style="width: auto; min-width: 130px;">
                                                    <option value="pending" {{ $job->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="in_progress" {{ $job->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                    <option value="completed" {{ $job->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                                    <option value="on_hold" {{ $job->status === 'on_hold' ? 'selected' : '' }}>On Hold</option>
                                                    <option value="cancelled" {{ $job->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td>{{ $job->estimated_duration ?? 'N/A' }}</td>
                                        <td>
                                            <div>{{ $job->created_at->format('Y-m-d') }}</div>
                                            <div class="text-muted small">{{ $job->created_at->format('h:i A') }}</div>
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-list flex-nowrap">
                                                <a href="{{ route('jobs.pdf-job-sheet', $job) }}" class="btn btn-primary btn-sm" title="Download PDF">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"/><path d="M7 11l5 5l5 -5"/><path d="M12 4l0 12"/></svg>
                                                </a>
                                                <a href="{{ route('jobs.edit', $job) }}" class="btn btn-white btn-sm" title="Edit Job">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/><path d="M16 5l3 3"/></svg>
                                                </a>
                                                <a href="{{ route('jobs.show', $job) }}" class="btn btn-white btn-sm" title="View Details">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2"/><path d="M22 12c-2.667 4.667-6 7-10 7s-7.333-2.333-10-7c2.667-4.667 6-7 10-7s7.333 2.333 10 7"/></svg>
                                                </a>
                                                <form action="{{ route('jobs.destroy', $job) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this job?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete Job">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0"/><path d="M10 11l0 6"/><path d="M14 11l0 6"/><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <div class="empty">
                                                <p class="empty-title">No jobs found</p>
                                                <p class="empty-subtitle text-muted">Try adjusting your filters or create a new job.</p>
                                                <div class="empty-action">
                                                    <a href="{{ route('jobs.index') }}" class="btn btn-primary">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14"/><path d="M5 12l14 0"/></svg>
                                                        Create New Job
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($jobs->hasPages())
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted">
                                    Showing {{ $jobs->firstItem() }} to {{ $jobs->lastItem() }} of {{ $jobs->total() }} jobs
                                </div>
                                <div>
                                    {{ $jobs->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@include('partials._job_receipt_modal')

@endsection
