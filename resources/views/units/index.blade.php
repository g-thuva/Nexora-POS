@extends('layouts.nexora')

@section('content')
<div class="page-body">
    <div class="container-fluid mb-3">
        <div class="alert alert-info d-flex">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M8 6l4 -4l4 4"/>
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M12 15l8 2l-2 -8"/>
                    <path d="M12 15l-8 2l2 -8"/>
                </svg>
            </div>
            <div>
                <h4 class="alert-title">Super Admin Only</h4>
                <div class="text-muted">Units management is restricted to super administrators. Units are available for selection in product forms by all users.</div>
            </div>
        </div>
    </div>

    @if($units->isEmpty())
        <x-empty
            title="No units found"
            message="Try adjusting your search or filter to find what you're looking for."
            button_label="{{ __('Add your first Unit') }}"
            button_route="{{ route('units.create') }}"
        />
    @else
        <div class="container-fluid">
            <x-alert/>

            @livewire('tables.unit-table')
        </div>
    @endif
</div>
@endsection
