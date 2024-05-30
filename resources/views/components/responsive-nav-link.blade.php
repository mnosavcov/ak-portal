@props(['active'])

@php
    $classes = ($active ?? false)
                ? 'text-[13px] font-Spartan-SemiBold block w-full px-[15px] text-left text-app-orange transition duration-150 ease-in-out'
                : 'text-[13px] font-Spartan-Regular block w-full px-[15px] text-left text-[#31363A]/80 hover:text-[#31363A] transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
