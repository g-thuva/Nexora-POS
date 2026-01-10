@extends('layouts.nexora')

@section('content')
<div class="page-header d-print-none">
    <div class="container-fluid">
        <div class="row g-2 align-items-center mb-3">
            <div class="col">
                <h2 class="page-title">
                    {{ __('Create Job Type') }}
                </h2>
            </div>
            <div class="col-auto ms-auto">
                <a href="{{ route('job-types.index') }}" class="btn btn-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1"/></svg>
                    Back to Job Types
                </a>
            </div>
        </div>

        @include('partials._breadcrumbs')
    </div>
</div>

<div class="page-body">
    <div class="container-fluid">
        <x-alert/>

        <div class="row row-cards">
            <!-- Left Column: Stats & Tips -->
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h3 class="card-title text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                <path d="M9 12h6" />
                                <path d="M9 16h6" />
                            </svg>
                            Quick Stats
                        </h3>

                        <div class="row g-2">
                            <div class="col-6">
                                <div class="card card-sm bg-primary-lt">
                                    <div class="card-body text-center">
                                        <div class="text-muted small">Total Types</div>
                                        <div class="h3 m-0 text-primary">{{ \App\Models\JobType::count() }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card card-sm bg-success-lt">
                                    <div class="card-body text-center">
                                        <div class="text-muted small">Active Jobs</div>
                                        <div class="h3 m-0 text-success">{{ \App\Models\Job::whereIn('status', ['pending','in_progress'])->count() }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-success">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M3 12h1m8 -9v1m8 8h1m-15.4 -6.4l.7 .7m12.1 -.7l-.7 .7" />
                                <path d="M9 16a5 5 0 1 1 6 0a3.5 3.5 0 0 0 -1 3a2 2 0 0 1 -4 0a3.5 3.5 0 0 0 -1 -3" />
                                <path d="M9.7 17l4.6 0" />
                            </svg>
                            Best Practices
                        </h3>

                        <div class="list-group list-group-flush">
                            <div class="list-group-item px-0">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="avatar bg-blue text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M12 5l0 14"/><path d="M5 12l14 0"/>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="text-truncate">
                                            <strong>Keep Names Short</strong>
                                        </div>
                                        <div class="text-muted small">Use concise, clear names like "Repair" or "Warranty"</div>
                                    </div>
                                </div>
                            </div>

                            <div class="list-group-item px-0">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="avatar bg-green text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M12 8l0 4l2 2"/><path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5"/>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="text-truncate">
                                            <strong>Set Default Days</strong>
                                        </div>
                                        <div class="text-muted small">Pre-fill typical duration to speed up job creation</div>
                                    </div>
                                </div>
                            </div>

                            <div class="list-group-item px-0">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="avatar bg-orange text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"/><path d="M12 8h.01"/><path d="M11 12h1v4h1"/>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="text-truncate">
                                            <strong>Add Description</strong>
                                        </div>
                                        <div class="text-muted small">Help team understand when to use this type</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Form -->
            <div class="col-lg-8">
                <form action="{{ route('job-types.store') }}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                    <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                    <path d="M9 12h6" />
                                    <path d="M9 16h6" />
                                </svg>
                                Job Type Information
                            </h3>
                        </div>
                        <div class="card-body">
                            @include('jobtypes._form')
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M5 12l5 5l10 -10"/>
                                    </svg>
                                    Create Job Type
                                </button>
                                <a href="{{ route('job-types.index') }}" class="btn btn-white">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
