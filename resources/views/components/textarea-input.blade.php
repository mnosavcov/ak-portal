@props(['disabled' => false])

<textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded-[3px] font-Spartan-Regular text-[13px] leading-[2.25] h-[245px] text-[#414141] border border-[#e2e2e2]']) !!}>{{ $slot }}</textarea>
