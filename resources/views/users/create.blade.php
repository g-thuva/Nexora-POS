@extends('layouts.nexora')

@section('content')
<div class="page-header d-print-none">
    <div class="container-fluid">
        <div class="row g-2 align-items-center mb-3">
            <div class="col">
                <h2 class="page-title">
                    {{ __('Create User') }}
                </h2>
            </div>
        </div>

        @include('partials._breadcrumbs')
    </div>
</div>

<div class="page-body">
    <div class="container-fluid">
        <div class="row row-cards">

            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">
                                    {{ __('User Image') }}
                                </h3>

                                <img class="img-account-profile mb-2"
                                     src="{{ asset('assets/img/demo/user-placeholder.svg') }}"
                                     alt=""
                                     id="image-preview"
                                >

                                <div class="small font-italic text-muted mb-2">
                                    JPG or PNG no larger than 1 MB
                                </div>

                                <input type="file"
                                       id="image"
                                       name="photo"
                                       accept="image/*"
                                       onchange="previewImage();"
                                       class="form-control @error('photo') is-invalid @enderror"
                                >

                                @error('photo')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">
                                    {{ __('User Details') }}
                                </h3>

                                <div class="row row-cards">
                                    <div class="col-md-12">

                                        <x-input name="name" :value="old('name')" required="true"/>

                                        <x-input name="email" :value="old('email')" required="true"/>

                                        <x-input name="username" :value="old('username')" required="true"/>

                                        <div class="mb-3">
                                            <label class="form-label required">{{ __('Role/Privilege Level') }}</label>
                                            <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                                <option value="">{{ __('Select Role') }}</option>
                                                @foreach($availableRoles as $value => $label)
                                                    <option value="{{ $value }}" {{ old('role') == $value ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="form-hint">
                                                <strong>Super Admin:</strong> Full system access, can create shops and shop owners<br>
                                                <strong>Shop Owner:</strong> Full access to their shop's features<br>
                                                <strong>Manager:</strong> Inventory management + POS access<br>
                                                <strong>Employee:</strong> POS system access only
                                            </div>
                                            @error('role')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        @if(safe_count($availableShops) > 1)
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Assign to Shop') }}</label>
                                            <select name="shop_id" class="form-select @error('shop_id') is-invalid @enderror">
                                                <option value="">{{ __('Select Shop (Optional for Shop Owner)') }}</option>
                                                @foreach($availableShops as $shop)
                                                    <option value="{{ $shop->id }}" {{ old('shop_id') == $shop->id ? 'selected' : '' }}>
                                                        {{ $shop->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="form-hint">
                                                Leave empty for Shop Owner role - they will own a shop instead of being assigned to one.
                                            </div>
                                            @error('shop_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        @elseif(safe_count($availableShops) == 1)
                                            <input type="hidden" name="shop_id" value="{{ $availableShops->first()->id }}">
                                        @endif

                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <x-input type="password" name="password"/>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <x-input type="password" name="password_confirmation" label="Password Confirmation"/>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-end">
                                <x-button.save type="submit">
                                    {{ __('Save') }}
                                </x-button.save>

                                <x-button.back route="{{ route('users.index') }}">
                                    {{ __('Cancel') }}
                                </x-button.back>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@pushonce('page-scripts')
<script src="{{ asset('assets/js/img-preview.js') }}"></script>
@endpushonce
