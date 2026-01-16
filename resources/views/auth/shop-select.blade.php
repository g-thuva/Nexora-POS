@extends('layouts.auth')

@section('content')
<div class="card card-md" style="box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
    <div class="card-body">
        <div class="text-center mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg mb-3" width="64" height="64" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" style="color: #0054a6;">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M3 21l18 0" />
                <path d="M3 7v1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1h-18l2 -4h14l2 4" />
                <path d="M5 21l0 -10.5" />
                <path d="M19 21l0 -10.5" />
                <path d="M9 21v-4a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v4" />
            </svg>
            <h2 class="h2 text-center mb-2" style="color: #1a1a1a; font-weight: 600;">
                Select Your Shop
            </h2>
            <p class="text-muted" style="font-size: 0.9rem;">You own multiple shops. Please select which shop you want to access.</p>
        </div>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible" role="alert">
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
                        <h4 class="alert-title">Error!</h4>
                        <div class="text-muted">{{ session('error') }}</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('shop.select.post') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold" style="color: #495057; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px;">Choose a shop to continue</label>
                <div class="shop-list-container">
                    @foreach($shops as $shop)
                        <label class="shop-item" for="shop_{{ $shop->id }}">
                            <div class="form-check">
                                <input class="form-check-input"
                                       type="radio"
                                       name="shop_id"
                                       id="shop_{{ $shop->id }}"
                                       value="{{ $shop->id }}"
                                       {{ $loop->first ? 'checked' : '' }}
                                       required>
                            </div>
                            <div class="shop-avatar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M3 21l18 0" />
                                    <path d="M3 7v1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1h-18l2 -4h14l2 4" />
                                    <path d="M5 21l0 -10.5" />
                                    <path d="M19 21l0 -10.5" />
                                </svg>
                            </div>
                            <div class="shop-details">
                                <div class="shop-name" title="{{ $shop->name }}">{{ $shop->name }}</div>
                                <div class="shop-info">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <rect x="3" y="5" width="18" height="14" rx="2" />
                                        <polyline points="3 7 12 13 21 7" />
                                    </svg>
                                    <span title="{{ $shop->email }}">{{ $shop->email }}</span>
                                </div>
                                <div class="shop-info">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" />
                                    </svg>
                                    <span>{{ $shop->phone }}</span>
                                </div>
                                @if($shop->subscription_status)
                                    <div class="mt-2">
                                        @if($shop->subscription_status === 'active')
                                            <span class="badge bg-success-lt">Active</span>
                                        @elseif($shop->subscription_status === 'trial')
                                            <span class="badge bg-info-lt">Trial</span>
                                        @elseif($shop->subscription_status === 'expired')
                                            <span class="badge bg-warning-lt">Expired</span>
                                        @else
                                            <span class="badge bg-secondary-lt">{{ ucfirst($shop->subscription_status) }}</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="shop-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <polyline points="9 6 15 12 9 18" />
                                </svg>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('shop_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-footer mt-4">
                <button type="submit" class="btn btn-primary w-100" style="background: #0054a6; border-color: #0054a6; padding: 0.75rem 1rem; font-weight: 500;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M9 11l3 3l8 -8" />
                        <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9" />
                    </svg>
                    Continue to Dashboard
                </button>
            </div>
        </form>
    </div>
</div>

<div class="text-center mt-4">
    <p class="text-muted" style="font-size: 0.875rem;">
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="text-muted text-decoration-none"
           style="transition: color 0.2s;">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                <path d="M9 12h12l-3 -3" />
                <path d="M18 15l3 -3" />
            </svg>
            Sign out instead
        </a>
    </p>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
@endsection

@push('page-scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add click handler to shop items to select radio button
    document.querySelectorAll('.shop-item').forEach(function(item) {
        item.addEventListener('click', function(e) {
            if (e.target.type !== 'radio') {
                const radio = this.querySelector('input[type="radio"]');
                if (radio) {
                    radio.checked = true;
                }
            }
        });
    });
});
</script>

<style>
/* Shop Selection Container */
.shop-list-container {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

/* Shop Item Card */
.shop-item {
    display: flex;
    align-items: center;
    padding: 1rem 1.25rem;
    background: #ffffff;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    margin: 0;
    gap: 0.75rem;
    overflow: hidden;
}

.shop-item:hover {
    border-color: #0054a6;
    background: #f8f9fa;
    box-shadow: 0 2px 8px rgba(0, 84, 166, 0.08);
    transform: translateY(-1px);
}

.shop-item:has(input[type="radio"]:checked) {
    border-color: #0054a6;
    background: #f0f7ff;
    box-shadow: 0 2px 12px rgba(0, 84, 166, 0.12);
}

/* Form Check Styling */
.shop-item .form-check {
    margin: 0;
    padding: 0;
    min-width: 24px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
}

.shop-item .form-check-input {
    width: 20px;
    height: 20px;
    margin: 0;
    cursor: pointer;
    border: 2px solid #cbd5e0;
}

.shop-item .form-check-input:checked {
    background-color: #0054a6;
    border-color: #0054a6;
}

.shop-item .form-check-input:focus {
    box-shadow: 0 0 0 3px rgba(0, 84, 166, 0.1);
}

/* Shop Details */
.shop-details {
    margin-left: 1rem;
    flex: 1;
    min-width: 0; /* Important for flex text truncation */
    max-width: 100%;
}

.shop-details > div {
    max-width: 100%;
}

.shop-avatar {
    width: 48px;
    height: 48px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f0f7ff;
    border-radius: 8px;
    color: #0054a6;
}

.shop-name {
    font-size: 1rem;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 0.25rem;
    line-height: 1.4;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    max-width: 100%;
}

.shop-info {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 0.125rem;
    display: flex;
    align-items: center;
    gap: 0.375rem;
    max-width: 100%;
    overflow: hidden;
}

.shop-info .icon-inline {
    color: #adb5bd;
    flex-shrink: 0;
}

/* Email and phone text wrapping */
.shop-info {
    word-break: break-all;
    overflow-wrap: break-word;
    white-space: normal;
}

/* Shop Arrow */
.shop-arrow {
    opacity: 0.4;
    transition: all 0.2s ease;
    color: #6c757d;
    flex-shrink: 0;
    margin-left: 0.5rem;
}

.shop-item:hover .shop-arrow {
    opacity: 1;
    transform: translateX(2px);
    color: #0054a6;
}

.shop-item:has(input[type="radio"]:checked) .shop-arrow {
    opacity: 1;
    color: #0054a6;
}

/* Badge Styling */
.badge {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.25rem 0.625rem;
    border-radius: 4px;
    white-space: nowrap;
}

.bg-success-lt {
    background-color: #d4edda;
    color: #155724;
}

.bg-info-lt {
    background-color: #d1ecf1;
    color: #0c5460;
}

.bg-warning-lt {
    background-color: #fff3cd;
    color: #856404;
}

.bg-secondary-lt {
    background-color: #e9ecef;
    color: #495057;
}

/* Button Hover */
.btn-primary:hover {
    background: #003d7a !important;
    border-color: #003d7a !important;
    box-shadow: 0 4px 12px rgba(0, 84, 166, 0.2);
    transform: translateY(-1px);
}

/* Link Hover */
a.text-muted:hover {
    color: #0054a6 !important;
}

/* Responsive */
@media (max-width: 576px) {
    .shop-item {
        padding: 0.875rem 1rem;
    }

    .shop-avatar {
        width: 40px;
        height: 40px;
    }

    .shop-name {
        font-size: 0.9375rem;
    }

    .shop-info {
        font-size: 0.8125rem;
    }

    .shop-arrow {
        display: none;
    }
}
</style>
@endpush
