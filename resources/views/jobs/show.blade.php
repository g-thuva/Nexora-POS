@extends('layouts.nexora')

@section('content')
<style>
.timeline {
    position: relative;
    padding: 20px 0;
}
.timeline-item {
    position: relative;
    padding-left: 40px;
    padding-bottom: 20px;
}
.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: 12px;
    top: 30px;
    bottom: -10px;
    width: 2px;
    background: #e9ecef;
}
.timeline-badge {
    position: absolute;
    left: 0;
    top: 0;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    z-index: 1;
}
.timeline-content {
    padding: 12px 16px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 3px solid #dee2e6;
}
</style>
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
                            <a href="{{ route('jobs.pdf-job-sheet', $job) }}" class="btn btn-white" title="Download PDF Job Sheet">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/><path d="M9 17h6"/><path d="M9 13h6"/></svg>
                                Download PDF
                            </a>
                            <button type="button" class="btn btn-white" title="POS Print" onclick="posPrintJobReceipt({{ $job->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2h-14a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h2"/><path d="M17 9v-4a2 2 0 0 0-2-2h-6a2 2 0 0 0-2 2v4"/><rect x="7" y="13" width="10" height="8" rx="2"/></svg>
                                POS Print
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
                <x-alert/>

                <div class="row row-cards">
                    <!-- Sidebar with Job Stats & Tips -->
                    <div class="col-lg-4">
                        <!-- Job Quick Stats -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="9 11 12 14 20 6" /><path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9" /></svg>
                                    Quick Stats
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="card card-sm">
                                            <div class="card-body text-center">
                                                <div class="text-muted small">Status</div>
                                                <div class="h4 m-0">{{ ucfirst(str_replace('_', ' ', $job->status)) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card card-sm">
                                            <div class="card-body text-center">
                                                <div class="text-muted small">Type</div>
                                                <div class="h4 m-0">{{ $job->type }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card card-sm">
                                            <div class="card-body text-center">
                                                <div class="text-muted small">Duration</div>
                                                <div class="h4 m-0">{{ $job->estimated_duration ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card card-sm">
                                            <div class="card-body text-center">
                                                <div class="text-muted small">Reference</div>
                                                <div class="h5 m-0 small">{{ $job->reference_number }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Job Timeline -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><polyline points="12 7 12 12 15 15" /></svg>
                                    Timeline
                                </h3>
                            </div>
                            <div class="card-body p-3">
                                <div class="timeline">
                                    <!-- Status History -->
                                    @if($job->statusHistories && $job->statusHistories->count() > 0)
                                        @foreach($job->statusHistories as $history)
                                        <div class="timeline-item">
                                            <div class="timeline-badge
                                                @if($history->new_status === 'completed') bg-success
                                                @elseif($history->new_status === 'in_progress') bg-info
                                                @elseif($history->new_status === 'on_hold') bg-warning
                                                @elseif($history->new_status === 'cancelled') bg-danger
                                                @else bg-secondary
                                                @endif">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="9 11 12 14 20 6" /><path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9" /></svg>
                                            </div>
                                            <div class="timeline-content">
                                                <div class="d-flex justify-content-between align-items-start mb-1">
                                                    <div>
                                                        <strong>Status changed</strong>
                                                        @if($history->old_status)
                                                        <span class="text-muted">from</span>
                                                        <span class="badge bg-secondary-lt">{{ ucfirst(str_replace('_', ' ', $history->old_status)) }}</span>
                                                        @endif
                                                        <span class="text-muted">to</span>
                                                        <span class="badge
                                                            @if($history->new_status === 'completed') bg-success
                                                            @elseif($history->new_status === 'in_progress') bg-info
                                                            @elseif($history->new_status === 'on_hold') bg-warning
                                                            @elseif($history->new_status === 'cancelled') bg-danger
                                                            @else bg-secondary
                                                            @endif">
                                                            {{ ucfirst(str_replace('_', ' ', $history->new_status)) }}
                                                        </span>
                                                    </div>
                                                    <small class="text-muted">{{ $history->created_at->diffForHumans() }}</small>
                                                </div>
                                                @if($history->changedBy)
                                                <div class="text-muted small">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="7" r="4" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                                                    By {{ $history->changedBy->name ?? 'Unknown' }} • {{ $history->created_at->format('M d, Y h:i A') }}
                                                </div>
                                                @else
                                                <div class="text-muted small">{{ $history->created_at->format('M d, Y h:i A') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    @endif

                                    <!-- Last Updated -->
                                    <div class="timeline-item">
                                        <div class="timeline-badge bg-blue">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 8l0 4l2 2" /><path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5" /></svg>
                                        </div>
                                        <div class="timeline-content">
                                            <strong>Last Updated</strong>
                                            <div class="text-muted small">{{ $job->updated_at->format('M d, Y h:i A') }}</div>
                                        </div>
                                    </div>

                                    <!-- Created -->
                                    <div class="timeline-item">
                                        <div class="timeline-badge bg-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                                        </div>
                                        <div class="timeline-content">
                                            <strong>Job Created</strong>
                                            <div class="text-muted small">{{ $job->created_at->format('M d, Y h:i A') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content Area -->
                    <div class="col-lg-8">
                        <!-- Job Information Card -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><rect x="9" y="3" width="6" height="4" rx="2" /><line x1="9" y1="12" x2="9.01" y2="12" /><line x1="13" y1="12" x2="15" y2="12" /><line x1="9" y1="16" x2="9.01" y2="16" /><line x1="13" y1="16" x2="15" y2="16" /></svg>
                                    Job Information
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted">Reference Number</label>
                                            <div class="form-control-plaintext"><strong>{{ $job->reference_number }}</strong></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted">Job Type</label>
                                            <div class="form-control-plaintext"><strong>{{ $job->type }}</strong></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted">Status</label>
                                            <div class="form-control-plaintext">
                                                <span class="badge
                                                    @if($job->status === 'completed') bg-success
                                                    @elseif($job->status === 'in_progress') bg-info
                                                    @elseif($job->status === 'pending') bg-warning
                                                    @else bg-secondary
                                                    @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted">Estimated Duration</label>
                                            <div class="form-control-plaintext"><strong>{{ $job->estimated_duration ? $job->estimated_duration . ' days' : 'Not specified' }}</strong></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Job Description Card -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><line x1="9" y1="9" x2="10" y2="9" /><line x1="9" y1="13" x2="15" y2="13" /><line x1="9" y1="17" x2="15" y2="17" /></svg>
                                    Job Description
                                </h3>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">{{ $job->description ?? 'No description provided.' }}</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="card">
                            <div class="card-footer">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('jobs.index') }}" class="btn btn-light">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="5" y1="12" x2="19" y2="12" /><line x1="5" y1="12" x2="11" y2="18" /><line x1="5" y1="12" x2="11" y2="6" /></svg>
                                        Back to Jobs
                                    </a>
                                    <div class="btn-list">
                                        <a href="{{ route('jobs.pdf-job-sheet', $job) }}" class="btn btn-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/><path d="M9 17h6"/><path d="M9 13h6"/></svg>
                                            PDF
                                        </a>
                                        <button type="button" class="btn btn-white" onclick="viewJobInModal({{ $job->id }})">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2h-14a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h2"/><path d="M17 9v-4a2 2 0 0 0-2-2h-6a2 2 0 0 0-2 2v4"/><rect x="7" y="13" width="10" height="8" rx="2"/></svg>
                                            Print
                                        </button>
                                        <a href="{{ route('jobs.edit', $job) }}" class="btn btn-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                            Edit Job
                                        </a>
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

@include('partials._job_receipt_modal')

@endsection
