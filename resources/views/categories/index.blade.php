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
                                <path d="M4 4h6v6h-6z" /><path d="M14 4h6v6h-6z" /><path d="M4 14h6v6h-6z" /><path d="M14 14h6v6h-6z" />
                            </svg>
                            Categories Management
                        </h3>
                        <div class="card-actions">
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary me-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5"/>
                                    <path d="M12 12l8 -4.5"/>
                                    <path d="M12 12l0 9"/>
                                    <path d="M12 12l-8 -4.5"/>
                                </svg>
                                View Products
                            </a>
                            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M12 5l0 14" /><path d="M5 12l14 0" />
                                </svg>
                                New Category
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-0">Organize your products into categories for better management and customer navigation. Create logical groupings that help you and your customers find products quickly.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Tips Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-primary-lt">
                    <div class="card-body">
                        <h4 class="card-title">💡 Category Management Tips</h4>
                        <div class="row g-3 mt-2">
                            <div class="col-md-4">
                                <div class="d-flex align-items-start">
                                    <div class="me-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"/>
                                            <rect x="9" y="3" width="6" height="4" rx="2"/>
                                            <path d="M9 14l2 2l4 -4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <strong>Use Clear Names</strong>
                                        <p class="text-muted small mb-0">Choose descriptive category names that customers can easily understand and search for.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-start">
                                    <div class="me-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-success" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <circle cx="12" cy="12" r="9"/>
                                            <path d="M9 12l2 2l4 -4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <strong>Keep It Simple</strong>
                                        <p class="text-muted small mb-0">Avoid creating too many categories. 5-15 main categories work best for most businesses.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-start">
                                    <div class="me-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-info" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <polyline points="9 11 12 14 20 6"/>
                                            <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <strong>Regular Review</strong>
                                        <p class="text-muted small mb-0">Periodically review and reorganize categories based on product performance and customer behavior.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body">
        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-primary text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M4 4h6v6h-6z" /><path d="M14 4h6v6h-6z" /><path d="M4 14h6v6h-6z" /><path d="M14 14h6v6h-6z" />
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    Total Categories
                                </div>
                                <div class="text-muted">
                                    @if($categories->isEmpty())
                                        0 Categories
                                    @else
                                        {{ $categories->count() }} Categories
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-success text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M5 12l5 5l10 -10"/>
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    Active Categories
                                </div>
                                <div class="text-muted">
                                    @if($categories->isEmpty())
                                        0 Active
                                    @else
                                        {{ $categories->count() }} Active
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-info text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5"/>
                                        <path d="M12 12l8 -4.5"/>
                                        <path d="M12 12l0 9"/>
                                        <path d="M12 12l-8 -4.5"/>
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    Total Products
                                </div>
                                <div class="text-muted">
                                    @if($categories->isEmpty())
                                        0 Items
                                    @else
                                        {{ $categories->sum(function($category) { return $category->products->count(); }) }} Items
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-warning text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"/>
                                        <path d="M12 9h.01"/>
                                        <path d="M11 12h1v4h1"/>
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    Avg. Products
                                </div>
                                <div class="text-muted">
                                    @if($categories->isEmpty())
                                        0 Items/Category
                                    @else
                                        {{ round($categories->sum(function($category) { return $category->products->count(); }) / $categories->count(), 1) }} Items/Category
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($categories->isEmpty())
            <!-- Empty State with Enhanced Tips -->
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="empty">
                        <div class="empty-img">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-muted" width="96" height="96" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M4 4h6v6h-6z" /><path d="M14 4h6v6h-6z" /><path d="M4 14h6v6h-6z" /><path d="M14 17h6" /><path d="M17 14v6" />
                            </svg>
                        </div>
                        <h3 class="empty-title">No Categories Yet</h3>
                        <p class="empty-subtitle text-muted">
                            Start organizing your products by creating your first category.<br>
                            Categories help you manage inventory and make it easier for customers to browse products.
                        </p>
                        <div class="empty-action">
                            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M12 5l0 14" /><path d="M5 12l14 0" />
                                </svg>
                                Create Your First Category
                            </a>
                        </div>
                    </div>

                    <!-- Getting Started Guide -->
                    <div class="row g-3 mt-4 text-start">
                        <div class="col-md-6 offset-md-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h4 class="card-title">🚀 Quick Start Guide</h4>
                                    <div class="steps steps-vertical">
                                        <div class="step-item">
                                            <div class="h4 m-0">1</div>
                                            <div>
                                                <div class="font-weight-bold">Create Categories</div>
                                                <div class="text-muted small">Click the button above to create your first product category</div>
                                            </div>
                                        </div>
                                        <div class="step-item">
                                            <div class="h4 m-0">2</div>
                                            <div>
                                                <div class="font-weight-bold">Add Products</div>
                                                <div class="text-muted small">Assign products to categories for better organization</div>
                                            </div>
                                        </div>
                                        <div class="step-item">
                                            <div class="h4 m-0">3</div>
                                            <div>
                                                <div class="font-weight-bold">Manage & Track</div>
                                                <div class="text-muted small">Monitor category performance and adjust as needed</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Categories Table -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">All Categories</h3>
                    <div class="card-actions">
                        <span class="text-muted">{{ $categories->count() }} total</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @livewire('tables.category-table')
                </div>
            </div>

            <!-- Best Practices Section -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">📚 Category Management Best Practices</h3>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="card bg-success-lt">
                                        <div class="card-body">
                                            <h5 class="text-success">✅ Do's</h5>
                                            <ul class="mb-0">
                                                <li>Use specific, descriptive names</li>
                                                <li>Group similar products together</li>
                                                <li>Keep category structure flat and simple</li>
                                                <li>Review and update regularly</li>
                                                <li>Consider customer search behavior</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-danger-lt">
                                        <div class="card-body">
                                            <h5 class="text-danger">❌ Don'ts</h5>
                                            <ul class="mb-0">
                                                <li>Create too many overlapping categories</li>
                                                <li>Use vague or confusing names</li>
                                                <li>Leave products uncategorized</li>
                                                <li>Create categories with single products</li>
                                                <li>Forget to delete unused categories</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
