@extends('layouts.nexora')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4>Create New Shop</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ request()->routeIs('admin.*') ? route('admin.shops.store') : route('shops.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Shop Name <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name') }}"
                                           required
                                           placeholder="Enter shop name">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email') }}"
                                           required
                                           placeholder="shop@example.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           id="phone"
                                           name="phone"
                                           value="{{ old('phone') }}"
                                           required
                                           placeholder="+1-234-567-8900">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('address') is-invalid @enderror"
                                              id="address"
                                              name="address"
                                              rows="3"
                                              required
                                              placeholder="Enter complete shop address">{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        @if(auth()->user()->isSuperAdmin() && safe_count($availableOwners) > 0)
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="owner_id" class="form-label">Shop Owner</label>
                                        <select class="form-select @error('owner_id') is-invalid @enderror"
                                                id="owner_id"
                                                name="owner_id">
                                            <option value="">Select Shop Owner (Leave empty to assign to yourself)</option>
                                            @foreach($availableOwners as $owner)
                                                <option value="{{ $owner->id }}" {{ old('owner_id') == $owner->id ? 'selected' : '' }}>
                                                    {{ $owner->name }} ({{ $owner->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('owner_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            Super admins can assign shops to other users or leave empty to own the shop themselves.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between">
                            <a href="{{ request()->routeIs('admin.*') ? route('admin.shops.index') : route('shops.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Shops
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Shop
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
