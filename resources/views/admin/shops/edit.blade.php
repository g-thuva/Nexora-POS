@extends('layouts.nexora')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Shop: {{ $shop->name }}</h4>
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

                    <form action="{{ route('admin.shops.update', $shop) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Shop Name <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $shop->name) }}"
                                           required>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email', $shop->email) }}"
                                           required>
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           id="phone"
                                           name="phone"
                                           value="{{ old('phone', $shop->phone) }}"
                                           required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="owner_id" class="form-label">Shop Owner <span class="text-danger">*</span></label>
                                    <select class="form-select @error('owner_id') is-invalid @enderror"
                                            id="owner_id"
                                            name="owner_id"
                                            required>
                                        <option value="">Select Owner</option>
                                        @foreach($availableOwners as $owner)
                                            <option value="{{ $owner->id }}"
                                                {{ (old('owner_id', $shop->owner_id) == $owner->id) ? 'selected' : '' }}>
                                                {{ $owner->name }} ({{ $owner->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="monthly_fee" class="form-label">Monthly Fee <span class="text-danger">*</span></label>
                                    <input type="number"
                                           class="form-control @error('monthly_fee') is-invalid @enderror"
                                           id="monthly_fee"
                                           name="monthly_fee"
                                           value="{{ old('monthly_fee', $shop->monthly_fee) }}"
                                           step="0.01"
                                           min="0"
                                           required>
                                </div>

                                <div class="mb-3">
                                    <label for="grace_period_days" class="form-label">Grace Period (Days) <span class="text-danger">*</span></label>
                                    <input type="number"
                                           class="form-control @error('grace_period_days') is-invalid @enderror"
                                           id="grace_period_days"
                                           name="grace_period_days"
                                           value="{{ old('grace_period_days', $shop->grace_period_days) }}"
                                           min="0"
                                           max="30"
                                           required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                    id="address"
                                    name="address"
                                    rows="3"
                                    required>{{ old('address', $shop->address) }}</textarea>
                        </div>

                        <div class="text-end">
                            <a href="{{ route('admin.shops.show', $shop) }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Shop</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
