@extends('layouts.nexora')

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <x-alert/>

        <!-- Page Header Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 5l0 14" /><path d="M5 12l14 0" />
                            </svg>
                            Create New Category
                        </h3>
                        <div class="card-actions">
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M9 14l-4 -4l4 -4"/>
                                    <path d="M5 10h11a4 4 0 1 1 0 8h-1"/>
                                </svg>
                                Back to Categories
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-0">Create a new category to organize your products. Categories help customers find products easily and improve your inventory management.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Side - Tips and Guidelines -->
            <div class="col-lg-4">
                <!-- Quick Tips Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="card-title">💡 Quick Tips</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex align-items-start mb-3">
                                <div class="me-3">
                                    <span class="avatar avatar-sm bg-primary-lt">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M12 9h.01"/>
                                            <path d="M11 12h1v4h1"/>
                                            <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z"/>
                                        </svg>
                                    </span>
                                </div>
                                <div>
                                    <strong>Use Clear Names</strong>
                                    <p class="text-muted small mb-0">Choose descriptive names that customers will easily understand and search for.</p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start mb-3">
                                <div class="me-3">
                                    <span class="avatar avatar-sm bg-success-lt">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M5 12l5 5l10 -10"/>
                                        </svg>
                                    </span>
                                </div>
                                <div>
                                    <strong>Auto-Generated Slug</strong>
                                    <p class="text-muted small mb-0">The slug is automatically created from your category name for URL-friendly links.</p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start">
                                <div class="me-3">
                                    <span class="avatar avatar-sm bg-warning-lt">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M12 9v4"/>
                                            <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z"/>
                                            <path d="M12 16h.01"/>
                                        </svg>
                                    </span>
                                </div>
                                <div>
                                    <strong>Unique Categories</strong>
                                    <p class="text-muted small mb-0">Each category name should be unique to avoid confusion in your product catalog.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Naming Examples Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="card-title">📝 Naming Examples</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="card bg-success-lt">
                                    <div class="card-body py-2 px-3">
                                        <div class="text-success mb-1"><strong>✅ Good Examples:</strong></div>
                                        <ul class="mb-0 small">
                                            <li>Electronics & Accessories</li>
                                            <li>Home & Kitchen</li>
                                            <li>Sports Equipment</li>
                                            <li>Office Supplies</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card bg-danger-lt">
                                    <div class="card-body py-2 px-3">
                                        <div class="text-danger mb-1"><strong>❌ Avoid:</strong></div>
                                        <ul class="mb-0 small">
                                            <li>Misc Items</li>
                                            <li>Other</li>
                                            <li>Category 1</li>
                                            <li>Temp</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Best Practices Card -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">🎯 Best Practices</h4>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="badge bg-blue">1</span>
                                    </div>
                                    <div class="col">
                                        <small>Keep names between 2-4 words</small>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="badge bg-blue">2</span>
                                    </div>
                                    <div class="col">
                                        <small>Use title case (capitalize each word)</small>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="badge bg-blue">3</span>
                                    </div>
                                    <div class="col">
                                        <small>Think from customer perspective</small>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="badge bg-blue">4</span>
                                    </div>
                                    <div class="col">
                                        <small>Plan for 5-15 main categories</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Category Form -->
            <div class="col-lg-8">
                <form method="POST" action="{{ route('categories.store') }}">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Category Information</h3>
                            <div class="card-actions">
                                <span class="text-muted small">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="12" cy="12" r="9"/>
                                        <path d="M12 8v4"/>
                                        <path d="M12 16h.01"/>
                                    </svg>
                                    Required fields are marked with <span class="text-danger">*</span>
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-12">
                                    <livewire:name />
                                </div>

                                <div class="col-12">
                                    <livewire:slug />
                                </div>

                                <div class="col-12">
                                    <div class="alert alert-info mb-0">
                                        <div class="d-flex">
                                            <div>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <circle cx="12" cy="12" r="9"/>
                                                    <path d="M12 8h.01"/>
                                                    <path d="M11 12h1v4h1"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="alert-title">About URL Slug</h4>
                                                <div class="text-muted">The slug is automatically generated from your category name. It creates SEO-friendly URLs and should remain unique across all categories. For example, "Electronics & Accessories" becomes "electronics-accessories".</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex gap-2 justify-content-between">
                                        <a href="{{ route('categories.index') }}" class="btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M18 6l-12 12"/>
                                                <path d="M6 6l12 12"/>
                                            </svg>
                                            Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                                <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                <path d="M14 4l0 4l-6 0l0 -4" />
                                            </svg>
                                            Save Category
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
