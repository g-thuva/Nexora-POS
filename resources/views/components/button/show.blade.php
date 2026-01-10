@props([
    'route'
])

<a href="{{ $route }}" {{ $attributes->class(['btn btn-white btn-icon']) }} title="View">
    <x-icon.eye class="icon text-dark"/>
</a>
