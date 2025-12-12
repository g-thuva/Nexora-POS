@extends('layouts.admin')

@section('content')
<div class="page-header d-print-none">
    <div class="container-fluid">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Create New Shop
                </h2>
                <div class="text-muted mt-1">Add a new shop to the system</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('admin.shops.index') }}" class="btn btn-outline-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M5 12l14 0" />
                        <path d="M5 12l6 6" />
                        <path d="M5 12l6 -6" />
                    </svg>
                    Back to Shops
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-fluid">
        <div class="row row-deck row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Shop Information</h3>
                    </div>
                    <form action="{{ route('admin.shops.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Shop Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                               name="email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Phone</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                               name="phone" value="{{ old('phone') }}" required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Shop Owner</label>
                                        <select class="form-select @error('owner_id') is-invalid @enderror"
                                                name="owner_id" required>
                                            <option value="">Select Shop Owner</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ old('owner_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }} ({{ $user->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('owner_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror"
                                          name="address" rows="3" required>{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                        </div>

                        <div class="card-footer text-end">
                            <a href="{{ route('admin.shops.index') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M12 5l0 14" />
                                    <path d="M5 12l14 0" />
                                </svg>
                                Create Shop
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
