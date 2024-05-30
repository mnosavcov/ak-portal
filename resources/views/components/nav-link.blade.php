@props(['active'])

@php
$classes = ($active ?? false)
            ? '!text-[15px] inline-flex items-center px-1 pt-1 border-b-2 border-app-blue text-sm font-medium leading-5 text-[#31363A] transition duration-150 ease-in-out font-Spartan-SemiBold'
            : '!text-[15px] inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-[#31363A]/80 hover:text-[#31363A] hover:border-gray-300 transition duration-150 ease-in-out font-Spartan-SemiBold';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
