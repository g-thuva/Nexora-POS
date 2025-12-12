@extends('layouts.nexora')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-3xl mx-auto bg-white shadow-sm rounded-lg">
        <div class="px-6 py-5 border-b">
            <h1 class="text-2xl font-semibold">{{ $type->name }}</h1>
            <p class="text-sm text-gray-500 mt-1">Job type details</p>
        </div>

        <div class="p-6">
            <div class="prose">
                <p>{{ $type->description ?: '-' }}</p>
            </div>

            <div class="mt-6">
                <a href="{{ route('job-types.index') }}" class="btn">Back</a>
            </div>
        </div>
    </div>
</div>
@endsection
