@props(['active'])

<a {{ $attributes->merge(['class' => 'block w-full px-4 pt-[25px] pb-[15px] text-left leading-5 ' . (($active ?? false) ? 'text-app-orange' : 'text-gray-700') . ' focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out font-Spartan-Bold text-[15px]']) }}>{{ $slot }}</a>
