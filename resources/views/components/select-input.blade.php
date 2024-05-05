@props(['disabled' => false, 'options' => ''])

<select
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge([
        'class' => '
            rounded-[3px]
            font-Spartan-Regular
            text-[13px]
            text-[#414141]
            border border-[#e2e2e2]'
    ]) !!}
>

    {!! $options !!}
</select>
