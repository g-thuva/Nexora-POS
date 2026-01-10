@props([
    'route'
])

<a href="{{ $route }}" {{ $attributes->class(['btn btn-white btn-icon']) }} title="Edit">
    <x-icon.pencil class="icon text-dark"/>
</a>
