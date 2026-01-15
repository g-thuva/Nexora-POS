@extends('layouts.admin')

@section('content')
<div class="page-header d-print-none">
    <div class="container-fluid">
        <div class="row g-2 align-items-center mb-3">
            <div class="col">
                <h2 class="page-title">
                    {{ __('Create New Shop') }}
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body" style="min-height: auto; padding-bottom: 1rem;">
    <div class="container-fluid">
        <x-alert/>

        <div class="row row-cards">
            <form method="POST" action="{{ route('admin.shops.store') }}">
                @csrf

                <div class="row">
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-bulb me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M3 12h1m8 -9v1m8 8h1m-15.4 -6.4l.7 .7m12.1 -.7l-.7 .7" />
                                        <path d="M9 16a5 5 0 1 1 6 0a3.5 3.5 0 0 0 -1 3a2 2 0 0 1 -4 0a3.5 3.5 0 0 0 -1 -3" />
                                        <path d="M9.7 17l4.6 0" />
                                    </svg>
                                    Quick Tips
                                </h3>

                                <div class="list-group list-group-flush mb-3">
                                    <div class="list-group-item px-0">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="avatar bg-success text-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l18 0" /><path d="M3 7v1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1h-18l2 -4h14l2 4" /><path d="M5 21l0 -10.15" /><path d="M19 21l0 -10.15" /><path d="M9 21v-4a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v4" /></svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="text-truncate">
                                                    <strong>Unique Shop Name</strong>
                                                </div>
                                                <div class="text-muted small">Each shop needs unique name</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="list-group-item px-0">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="avatar bg-info text-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="text-truncate">
                                                    <strong>Assign Shop Owner</strong>
                                                </div>
                                                <div class="text-muted small">Select from existing users</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="list-group-item px-0">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="avatar bg-warning text-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M8 7v-2a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v2" /><path d="M12 12l0 .01" /><path d="M3 13a20 20 0 0 0 18 0" /></svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="text-truncate">
                                                    <strong>Contact Details</strong>
                                                </div>
                                                <div class="text-muted small">Email and phone required</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="list-group-item px-0">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="avatar bg-purple text-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /><path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" /></svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="text-truncate">
                                                    <strong>Complete Address</strong>
                                                </div>
                                                <div class="text-muted small">Full location required</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-info mb-0">
                                    <div class="d-flex">
                                        <div>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 9h.01" /><path d="M11 12h1v4h1" /></svg>
                                        </div>
                                        <div>
                                            <h4 class="alert-title">Pro Tip!</h4>
                                            <div class="text-muted">After creating the shop, you can assign multiple users to it. The shop owner will have full control over shop operations.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <div>
                                    <h3 class="card-title">
                                        {{ __('Shop Information') }}
                                    </h3>
                                </div>

                                <div class="card-actions">
                                    <a href="{{ route('admin.shops.index') }}" class="btn-action">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M18 6l-12 12"></path><path d="M6 6l12 12"></path></svg>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row row-cards">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">
                                                {{ __('Shop Name') }}
                                            </label>
                                            <input type="text"
                                                   class="form-control @error('name') is-invalid @enderror"
                                                   name="name"
                                                   value="{{ old('name') }}"
                                                   placeholder="My Store"
                                                   required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">
                                                {{ __('Email') }}
                                            </label>
                                            <input type="email"
                                                   class="form-control @error('email') is-invalid @enderror"
                                                   name="email"
                                                   value="{{ old('email') }}"
                                                   placeholder="shop@example.com"
                                                   required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">
                                                {{ __('Phone') }}
                                            </label>
                                            <input type="text"
                                                   class="form-control @error('phone') is-invalid @enderror"
                                                   name="phone"
                                                   value="{{ old('phone') }}"
                                                   placeholder="+1234567890"
                                                   required>
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">
                                                {{ __('Shop Owner') }}
                                            </label>
                                            <select class="form-select @error('owner_id') is-invalid @enderror"
                                                    name="owner_id"
                                                    required>
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

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label required">
                                                {{ __('Address') }}
                                            </label>
                                            <textarea class="form-control @error('address') is-invalid @enderror"
                                                      name="address"
                                                      rows="3"
                                                      placeholder="123 Main Street, City, State, ZIP"
                                                      required>{{ old('address') }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-end">
                                <div class="d-flex">
                                    <a href="{{ route('admin.shops.index') }}" class="btn btn-link">Cancel</a>
                                    <button type="submit" class="btn btn-white ms-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M12 5l0 14" />
                                            <path d="M5 12l14 0" />
                                        </svg>
                                        {{ __('Create Shop') }}
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
@endsection