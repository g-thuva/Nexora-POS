@extends('layouts.nexora')

@section('content')
<div class="page-wrapper">
    <div class="container-fluid py-4">

        <div class="page-header d-print-none mb-3">
            <div class="container-fluid">
                <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">SERVICES</div>
                <h2 class="page-title">Job: {{ $job->reference_number }}</h2>
                <p class="text-muted small">View job details and status history.</p>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ url('/jobs?filter=overdue') }}" class="btn btn-warning">Overdue Report</a>
                    <a href="{{ route('jobs.pdf-job-sheet', $job) }}" class="btn btn-white" title="Download PDF Job Sheet">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/><path d="M9 17h6"/><path d="M9 13h6"/></svg>
                        Download PDF
                    </a>
                    <button type="button" class="btn btn-white" title="View & Print" onclick="viewJobInModal({{ $job->id }})" data-bs-toggle="modal" data-bs-target="#jobReceiptModal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2h-14a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h2"/><path d="M17 9v-4a2 2 0 0 0-2-2h-6a2 2 0 0 0-2 2v4"/><rect x="7" y="13" width="10" height="8" rx="2"/></svg>
                        Print
                    </button>
                    <a href="{{ route('jobs.edit', $job) }}" class="btn btn-primary">Edit Job</a>
                </div>
            </div>
        </div>

        @include('partials._breadcrumbs')
    </div>
</div>

<div class="page-body">
    <div class="container-fluid">
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
        <x-alert/>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h3>{{ $job->type }}</h3>
                        <p class="text-muted">{{ ucfirst(str_replace('_', ' ', $job->status)) }} &middot; {{ $job->created_at->toDayDateTimeString() }}</p>

                        <h4>{{ __('Description') }}</h4>
                        <p>{{ $job->description }}</p>

                        <h4>{{ __('Estimated Duration') }}</h4>
                        <p>{{ $job->estimated_duration ? $job->estimated_duration . ' days' : __('Not provided') }}</p>
                    </div>
                    <div class="card-footer text-end">
                        <a href="{{ route('jobs.index') }}" class="btn btn-light">{{ __('Back') }}</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4>{{ __('Job Info') }}</h4>
                        <p><strong>{{ __('Reference') }}:</strong> {{ $job->reference_number }}</p>
                        <p><strong>{{ __('Status') }}:</strong> {{ ucfirst(str_replace('_', ' ', $job->status)) }}</p>
                        <p><strong>{{ __('Created') }}:</strong> {{ $job->created_at->toDayDateTimeString() }}</p>
                        <p><strong>{{ __('Updated') }}:</strong> {{ $job->updated_at->toDayDateTimeString() }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@include('partials._job_receipt_modal')

@endsection
