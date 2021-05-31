@props(['actives'])

@php
$classes = ($attributes['active'])
            ? 'block lg:inline-block py-1 md:py-4 text-gray-100 mr-6 font-bold'
            : 'block lg:inline-block py-1 md:py-4 text-gray-400 hover:text-gray-100 mr-6';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
