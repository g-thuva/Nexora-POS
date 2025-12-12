@extends('layouts.nexora')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-3xl mx-auto bg-white shadow-sm rounded-lg">
        <div class="px-6 py-5 border-b">
            <h1 class="text-2xl font-semibold">Create Job Type</h1>
            <p class="text-sm text-gray-500 mt-1">Define a job type to categorize jobs (e.g. Repair, Warranty, Maintenance).</p>
        </div>

        <div class="p-6">
            <form action="{{ route('job-types.store') }}" method="POST">
                @csrf

                @include('jobtypes._form')

                <div class="mt-6 flex items-center">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="{{ route('job-types.index') }}" class="btn ml-3">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
