
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
            <div class="nav-item d-flex align-items-center">
                <span class="avatar avatar-sm shadow-none me-2"
                      style="background-image: url({{ Avatar::create(Auth::user()->name)->toBase64() }})">
                </span>
                <div class="d-none d-xl-block">
                    <div class="small text-muted">{{ Auth::user()->name }}</div>
                </div>
            </div>
            <div class="nav-item">
                <form action="{{ route('logout') }}" method="post" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 0.5rem 1rem; border-radius: 0.5rem; font-weight: 500; box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);">
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
