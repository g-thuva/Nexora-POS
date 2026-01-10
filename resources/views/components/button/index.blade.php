@props([
    'type' => null ?? 'button',
    'route'
])

@isset($route)
    <a href="{{ $route }}" {{ $attributes->class(['btn btn-white']) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->class(['btn btn-white']) }}>
        {{ $slot }}
    </button>
@endisset
