
<header class="navbar navbar-expand-md sticky-top d-print-none" style="z-index: 1100; border: none; overflow: visible !important;">
    <div class="container-xxl" style="overflow: visible !important;">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
            <a href="{{ url('/') }}" class="d-flex align-items-center">
                <span class="navbar-brand-text fs-4 fw-bold text-dark">NexoraLabs</span>
            </a>
        </h1>

        <div class="navbar-nav flex-row order-md-last align-items-center" style="position: relative; z-index: 1150; overflow: visible !important; gap: 1rem;">

            {{-- Shop Switcher for Multi-Shop Owners --}}
            @if(Auth::check() && Auth::user()->isShopOwner() && Auth::user()->ownsMultipleShops())
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link d-flex align-items-center px-2" data-bs-toggle="dropdown" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M3 21l18 0" />
                            <path d="M3 7v1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1h-18l2 -4h14l2 4" />
                            <path d="M5 21l0 -10.5" />
                            <path d="M19 21l0 -10.5" />
                            <path d="M9 21v-4a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v4" />
                        </svg>
                        <span class="d-none d-md-inline">
                            {{ Auth::user()->getActiveShop() ? Auth::user()->getActiveShop()->name : 'Select Shop' }}
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm ms-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <polyline points="6 9 12 15 18 9" />
                        </svg>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" style="min-width: 280px;">
                        <h6 class="dropdown-header">Your Shops</h6>
                        @php
                            $currentShopId = Auth::user()->getActiveShop()?->id;
                            $userShops = Auth::user()->getOwnedShops();
                        @endphp
                        @foreach($userShops as $shop)
                            <a href="#" class="dropdown-item shop-switch-item {{ $shop->id === $currentShopId ? 'active' : '' }}" data-shop-id="{{ $shop->id }}">
                                <div class="d-flex align-items-center">
                                    <span class="avatar avatar-sm me-2" style="background-image: url({{ asset('static/avatars/shop-default.png') }})"></span>
                                    <div class="flex-fill">
                                        <div class="fw-bold">{{ $shop->name }}</div>
                                        <div class="text-muted small">{{ $shop->email }}</div>
                                    </div>
                                    @if($shop->id === $currentShopId)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-success" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <circle cx="12" cy="12" r="9" />
                                            <path d="M9 12l2 2l4 -4" />
                                        </svg>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('shop.select') }}" class="dropdown-item">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <circle cx="12" cy="12" r="9" />
                                <path d="M8 12h8" />
                            </svg>
                            View All Shops
                        </a>
                    </div>
                </div>
            @endif

            <div class="nav-item d-flex align-items-center">
                @if(Auth::user()->photo)
                    <img src="{{ asset('storage/profile/' . Auth::user()->photo) }}?t={{ time() }}"
                         alt="{{ Auth::user()->name }}"
                         class="avatar avatar-sm rounded-circle shadow-sm me-2"
                         style="width: 32px; height: 32px; object-fit: cover;">
                @else
                    <span class="avatar avatar-sm shadow-none me-2"
                          style="background-image: url({{ Avatar::create(Auth::user()->name)->toBase64() }})">
                    </span>
                @endif
                <div class="d-none d-xl-block">
                    <div class="small text-muted">{{ Auth::user()->name }}</div>
                </div>
            </div>
            <div class="nav-item">
                <form action="{{ route('logout') }}" method="post" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                            <path d="M9 12h12l-3 -3" />
                            <path d="M18 15l3 -3" />
                        </svg>
                        <span class="d-none d-sm-inline ms-1">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
