@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-Spartan-Bold text-[11px] tablet:text-[13px] leading-29px text-[#676464]']) }}>
    {{ $value ?? $slot }}
</label>
