@extends('layouts.nexora')

@section('content')
<div class="page-header d-print-none">
    <div class="container-fluid">
        <div class="row g-2 align-items-center mb-3">
            <div class="col">
                <h2 class="page-title">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                        <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                        <path d="M9 12h6" />
                        <path d="M9 16h6" />
                    </svg>
                    {{ __('Job Types') }}
                </h2>
                <div class="text-muted mt-1">Manage job type categories for organizing repair and service jobs</div>
            </div>
            <div class="col-auto ms-auto">
                <a href="{{ route('job-types.create') }}" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M12 5l0 14"/><path d="M5 12l14 0"/>
                    </svg>
                    Create Job Type
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
            <!-- Stats Cards -->
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total Types</div>
                        </div>
                        <div class="h1 mb-0 mt-2">{{ $types->total() }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total Jobs</div>
                        </div>
                        <div class="h1 mb-0 mt-2 text-primary">{{ \App\Models\Job::count() }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Active Jobs</div>
                        </div>
                        <div class="h1 mb-0 mt-2 text-success">{{ \App\Models\Job::whereIn('status', ['pending','in_progress'])->count() }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">This Month</div>
                        </div>
                        <div class="h1 mb-0 mt-2 text-info">{{ \App\Models\Job::whereMonth('created_at', now()->month)->count() }}</div>
                    </div>
                </div>
            </div>

            <!-- Job Types Table -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">All Job Types</h3>
                        <div class="card-actions">
                            <span class="text-muted">{{ $types->total() }} {{ Str::plural('type', $types->total()) }}</span>
                        </div>
                    </div>
                    @if($types->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-hover">
                            <thead>
                                <tr>
                                    <th class="w-1">#</th>
                                    <th>Name</th>
                                    <th>Default Duration</th>
                                    <th>Description</th>
                                    <th class="w-1">Jobs Count</th>
                                    <th class="w-1"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($types as $type)
                                    <tr>
                                        <td><span class="text-muted">{{ $loop->iteration }}</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="avatar avatar-sm me-2" style="background-color: {{ sprintf('#%06X', mt_rand(0, 0xFFFFFF)) }}20; color: {{ sprintf('#%06X', mt_rand(0, 0xFFFFFF)) }};">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                                        <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                                    </svg>
                                                </span>
                                                <div>
                                                    <div class="fw-bold">{{ $type->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($type->default_days)
                                                <span class="badge bg-blue-lt">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M12 8l0 4l2 2"/><path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5"/>
                                                    </svg>
                                                    {{ $type->default_days }} {{ Str::plural('day', $type->default_days) }}
                                                </span>
                                            @else
                                                <span class="text-muted">Not set</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 300px;" title="{{ $type->description }}">
                                                {{ $type->description ?: 'No description' }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ $type->jobs_count ?? 0 }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <a href="{{ route('job-types.edit', $type) }}" class="btn btn-sm btn-white" title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                        <path d="M16 5l3 3" />
                                                    </svg>
                                                </a>
                                                <form action="{{ route('job-types.destroy', $type) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this job type? This action cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-white text-danger" title="Delete">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                            <path d="M4 7l16 0" />
                                                            <path d="M10 11l0 6" />
                                                            <path d="M14 11l0 6" />
                                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="card-body">
                        <div class="empty">
                            <div class="empty-img">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                    <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                    <path d="M9 12h6" />
                                    <path d="M9 16h6" />
                                </svg>
                            </div>
                            <p class="empty-title">No job types found</p>
                            <p class="empty-subtitle text-muted">
                                Get started by creating your first job type to categorize your jobs.
                            </p>
                            <div class="empty-action">
                                <a href="{{ route('job-types.create') }}" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 5l0 14"/><path d="M5 12l14 0"/>
                                    </svg>
                                    Create your first job type
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($types->hasPages())
                    <div class="card-footer d-flex align-items-center">
                        <p class="m-0 text-muted">Showing {{ $types->firstItem() }} to {{ $types->lastItem() }} of {{ $types->total() }} entries</p>
                        <div class="ms-auto">
                            {{ $types->links() }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
