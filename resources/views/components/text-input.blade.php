@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded-[3px] font-Spartan-Regular text-[13px] leading-[45px] h-[45px] text-[#414141] border border-[#e2e2e2]']) !!}>
