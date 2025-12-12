@extends('layouts.nexora')

@section('content')
<div class="page-header d-print-none">
    <div class="container-fluid">
        <div class="row g-2 align-items-center mb-3">
            <div class="col">
                <div class="page-pretitle">SERVICES</div>
                <h2 class="page-title">Edit Job</h2>
                <p class="text-muted small">Modify job details, update status or estimated duration.</p>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ url('/jobs?filter=overdue') }}" class="btn btn-warning">Overdue Report</a>
                    <a href="{{ route('jobs.index') }}" class="btn btn-primary">+ New Job</a>
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

        <div class="row row-cards">
            <form action="{{ route('jobs.update', $job) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Job Details') }}</h3>
                            <div class="card-actions">
                                <a href="{{ route('jobs.index') }}" class="btn-action">{{ __('Back') }}</a>
                            </div>
                        </div>
                        <div class="card-body">
                            @include('jobs._form', ['job' => $job])
                        </div>

                        <div class="card-footer text-end">
                            <x-button.save type="submit">{{ __('Update') }}</x-button.save>
                            <x-button.back route="{{ route('jobs.index') }}">{{ __('Cancel') }}</x-button.back>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
