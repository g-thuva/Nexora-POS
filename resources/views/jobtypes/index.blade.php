@extends('layouts.nexora')

@section('content')
<div class="container mx-auto py-6">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold">Job Types</h1>
        <a href="{{ route('job-types.create') }}" class="btn btn-primary">Create job type</a>
    </div>

    <div class="bg-white shadow rounded">
        <table class="min-w-full table-auto">
            <thead>
                <tr>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Description</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($types as $type)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $type->name }}</td>
                        <td class="px-4 py-2">{{ $type->description }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('job-types.edit', $type) }}" class="btn btn-sm">Edit</a>
                            <form action="{{ route('job-types.destroy', $type) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this job type?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $types->links() }}</div>
</div>
@endsection
