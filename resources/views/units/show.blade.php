@extends('layouts.nexora')

@section('content')
<div class="page-body">
    <div class="container-fluid">
        @livewire('tables.product-by-unit-table', ['unit' => $unit])
    </div>
</div>
@endsection
