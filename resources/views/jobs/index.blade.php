@extends('layouts.nexora')

@section('content')
<div class="page-wrapper">
    <div class="container-fluid py-4">

        <div class="page-header d-print-none mb-3">
            <div class="container-fluid">
                <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">{{ __('Services') }}</div>
                <h2 class="page-title">{{ __('Jobs') }}</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('job-letterhead.index') }}" class="btn btn-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/></svg>
                        {{ __('Job Letterhead') }}
                    </a>
                    <a href="{{ route('jobs.index') }}" class="btn btn-primary">
                        {{ __('New Job') }}
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
                    <div class="text-muted small">THIS MONTH</div>
                    <div class="h5">{{ \App\Models\Job::whereMonth('created_at', now()->month)->count() }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <div class="text-muted small">OPEN</div>
                    <div class="h5">{{ \App\Models\Job::whereIn('status', ['pending','in_progress'])->count() }}</div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- 60% width for Create Job Form --}}
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Create New Job</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('jobs.store') }}">
                            @csrf

                            {{-- include the job form partial --}}
                            @include('jobs._form')

                            <div class="d-flex justify-content-end mt-3">
                                <button class="btn btn-secondary me-2" type="reset">Reset</button>
                                <button class="btn btn-primary" type="submit">Create Job</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- 40% width for Recent Jobs List --}}
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Recent Jobs</h3>
                        <div class="card-actions">
                            <a href="{{ route('jobs.list') }}" class="btn btn-sm btn-primary">View All</a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @php $recent = \App\Models\Job::with(['customer', 'jobType'])->latest()->limit(10)->get(); @endphp

                        @if($recent->isNotEmpty())
                            <div class="list-group list-group-flush">
                                @foreach($recent as $r)
                                    <div class="list-group-item">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <div class="text-truncate">
                                                    <strong>{{ $r->reference_number }}</strong>
                                                </div>
                                                <div class="text-muted text-truncate">{{ $r->customer->name ?? 'N/A' }}</div>
                                                <div class="text-muted small">{{ $r->jobType->name ?? $r->type ?? 'N/A' }}</div>
                                            </div>
                                            <div class="col-auto">
                                                <span class="badge badge-sm
                                                    @if($r->status === 'completed') bg-success
                                                    @elseif($r->status === 'in_progress') bg-blue
                                                    @elseif($r->status === 'on_hold') bg-warning
                                                    @elseif($r->status === 'cancelled') bg-danger
                                                    @else bg-secondary
                                                    @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $r->status)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <div class="btn-list">
                                                <a href="{{ route('jobs.pdf-job-sheet', $r) }}" class="btn btn-sm btn-primary" title="Download PDF">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"/><path d="M7 11l5 5l5 -5"/><path d="M12 4l0 12"/></svg>
                                                    PDF
                                                </a>
                                                <a href="{{ route('jobs.edit', $r) }}" class="btn btn-sm btn-white" title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/><path d="M16 5l3 3"/></svg>
                                                    Edit
                                                </a>
                                                <a href="{{ route('jobs.show', $r) }}" class="btn btn-sm btn-white" title="View">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2"/><path d="M22 12c-2.667 4.667-6 7-10 7s-7.333-2.333-10-7c2.667-4.667 6-7 10-7s7.333 2.333 10 7"/></svg>
                                                    View
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty p-4">
                                <p class="empty-title">No jobs found</p>
                                <p class="empty-subtitle text-muted">Create your first job using the form.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@include('partials._job_receipt_modal')

@endsection
