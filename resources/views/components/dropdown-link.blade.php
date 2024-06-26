@props(['active'])

@php
    $classes = ($active ?? false)
                ? 'block mt-[5px] w-full px-[15px] py-[10px] text-left leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out font-Spartan-SemiBold text-[13px]'
                : 'block mt-[5px] w-full px-[15px] py-[10px] text-left leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out font-Spartan-Regular text-[13px]';
@endphp


<a {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
