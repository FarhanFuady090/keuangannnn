@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-green-400 text-sm font-medium leading-5 text-white-900 focus:outline-none w-full p-4 rounded-md align-center bg-green-900 transition duration-150 ease-in-out '
            : 'flex items-center p-2 text-green-900 rounded-lg dark:text-white hover:bg-green-100 dark:hover:bg-green-700 group';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
