@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-Spartan-Bold text-[13px] leading-29px text-[#676464]']) }}>
    {{ $value ?? $slot }}
</label>
