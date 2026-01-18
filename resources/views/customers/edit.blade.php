@extends('layouts.nexora')

@section('content')
<div class="page-header d-print-none">
    <div class="container-fluid">
        <div class="row g-2 align-items-center mb-3">
            <div class="col">
                <h2 class="page-title">
                    {{ __('Edit Customer') }}
                </h2>
            </div>
        </div>

        @include('partials._breadcrumbs', ['model' => $customer])
    </div>
</div>

<div class="page-body">
    <div class="container-fluid">
        <form action="{{ route('customers.update', $customer) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('put')

            <div class="row row-cards">
                <!-- Left Column: Tips & Guidelines -->
                <div class="col-12 col-lg-4">
                    <!-- Tips Card 1 -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex gap-2 mb-3">
                                <div class="bg-primary text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="12" cy="12" r="9"/>
                                        <path d="M12 7v5l3 3"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="mb-1">Accurate Information</h4>
                                    <p class="text-muted small mb-0">Keep customer details up-to-date to ensure smooth transactions and accurate records.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tips Card 2 -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex gap-2 mb-3">
                                <div class="bg-success text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="12" cy="12" r="9"/>
                                        <path d="M9 12l2 2l4 -4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="mb-1">Valid Contact Details</h4>
                                    <p class="text-muted small mb-0">Ensure email and phone number are correct for important communications and order notifications.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tips Card 3 -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex gap-2 mb-3">
                                <div class="bg-warning text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9"/>
                                        <line x1="12" y1="9" x2="12" y2="13"/>
                                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="mb-1">Complete Address</h4>
                                    <p class="text-muted small mb-0">Add complete address details for accurate deliveries and customer location tracking.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tips Card 4 -->
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex gap-2 mb-3">
                                <div class="bg-info text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3"/>
                                        <line x1="12" y1="12" x2="20" y2="7.5"/>
                                        <line x1="12" y1="12" x2="12" y2="21"/>
                                        <line x1="12" y1="12" x2="4" y2="7.5"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="mb-1">Bank Details (Premium)</h4>
                                    <p class="text-muted small mb-0">Add bank account details if this is an account holder for streamlined credit transactions.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Edit Form -->
                <div class="col-12 col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Customer Details') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ old('name', $customer->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Email address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" value="{{ old('email', $customer->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Phone number <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" class="form-control @error('phone') is-invalid @enderror" required>
                                    @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Address <span class="text-danger">*</span></label>
                                    <textarea name="address" rows="3" class="form-control @error('address') is-invalid @enderror" required>{{ old('address', $customer->address) }}</textarea>
                                    @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="isAccountHolder" name="is_account_holder" value="1" {{ $customer->account_holder ? 'checked' : '' }} onchange="toggleAccountFields()">
                                        <label class="form-check-label" for="isAccountHolder">
                                            This is an Account Holder
                                        </label>
                                    </div>
                                </div>

                                <div id="accountHolderFields" style="display: {{ $customer->account_holder ? 'block' : 'none' }}; width: 100%;">
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Account Holder Name</label>
                                        <input type="text" name="account_holder" value="{{ old('account_holder', $customer->account_holder) }}" class="form-control @error('account_holder') is-invalid @enderror">
                                        @error('account_holder')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label class="form-label">Account Number</label>
                                        <input type="text" name="account_number" value="{{ old('account_number', $customer->account_number) }}" class="form-control @error('account_number') is-invalid @enderror">
                                        @error('account_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 mb-0">
                                        <label class="form-label">Bank Name</label>
                                        <input type="text" name="bank_name" value="{{ old('bank_name', $customer->bank_name) }}" class="form-control @error('bank_name') is-invalid @enderror">
                                        @error('bank_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                    <path d="M16 5l3 3"/>
                                </svg>
                                {{ __('Update') }}
                            </button>

                            <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <polyline points="15 6 9 12 15 18"/>
                                </svg>
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('page-styles')
<style>
    .avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.375rem;
        flex-shrink: 0;
    }

    .card {
        border: 1px solid #e3e6f0;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e3e6f0;
    }

    .form-label {
        font-weight: 600;
        color: #212529;
    }

    @media (max-width: 992px) {
        .col-lg-4,
        .col-lg-8 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }
</style>
@endpush

@push('page-scripts')
<script>
function toggleAccountFields() {
    const checkbox = document.getElementById('isAccountHolder');
    const fields = document.getElementById('accountHolderFields');
    fields.style.display = checkbox.checked ? 'block' : 'none';
}
</script>
@endpush
