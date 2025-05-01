@props(['route'])

@php
    $isActive = Route::is($route) ? 'text-white bg-blue-500 px-4 py-2 rounded-lg' : 'text-gray-700 hover:text-blue-600';
@endphp

<a href="{{ $route ? route($route) : '#' }}" class="{{ $isActive }}">
    {{ $slot }}
</a>
