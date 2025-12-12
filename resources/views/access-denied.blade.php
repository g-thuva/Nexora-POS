@extends('layouts.nexora')

@section('content')
<div class="page page-center">
    <div class="container container-tight py-4">
        <div class="empty">
            <div class="empty-header">
                <svg class="icon icon-tabler icon-tabler-lock" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2H7a2 2 0 0 1 -2 -2v-6z"></path>
                    <path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0"></path>
                    <path d="M8 11v-4a4 4 0 1 1 8 0v4"></path>
                </svg>
            </div>
            <div class="empty-title">Access Denied</div>
            
            @if(session('error_message'))
                <p class="empty-subtitle text-muted">
                    {{ session('error_message') }}
                </p>
            @endif

            @if(session('shop') && session('shop')->isSuspended())
                <div class="alert alert-warning mt-3">
                    <h4 class="alert-title">Shop Suspended</h4>
                    <div class="text-muted">
                        Your shop access has been suspended on {{ session('shop')->suspended_at->format('M d, Y') }}.
                        @if(session('shop')->suspension_reason)
                            <br><strong>Reason:</strong> {{ session('shop')->suspension_reason }}
                        @endif
                    </div>
                </div>
            @endif

            @if(session('shop') && session('shop')->isExpired())
                <div class="alert alert-danger mt-3">
                    <h4 class="alert-title">Subscription Expired</h4>
                    <div class="text-muted">
                        Your subscription expired on {{ session('shop')->subscription_end_date->format('M d, Y') }}.
                        Please contact the administrator to renew your subscription.
                    </div>
                </div>
            @endif

            <div class="empty-action">
                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                    <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <line x1="5" y1="12" x2="11" y2="18"></line>
                        <line x1="5" y1="12" x2="11" y2="6"></line>
                    </svg>
                    Back to Dashboard
                </a>

                @if(!auth()->user()->isAdmin())
                    <a href="mailto:admin@nexoralabs.com?subject=Shop Access Request" class="btn btn-outline-primary ms-2">
                        Contact Administrator
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection