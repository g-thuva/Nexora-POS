@props([
    'route',
    'confirmMessage' => 'Are you sure you want to delete this item? This action cannot be undone.'
])

<form action="{{ $route }}" method="POST" class="d-inline-block" onsubmit="return confirm('{{ $confirmMessage }}')">
    @csrf
    @method('delete')
    <button type="submit" {{ $attributes->class(['btn btn-white btn-icon']) }} title="Delete">
        <x-icon.trash class="icon text-dark"/>
    </button>
</form>
