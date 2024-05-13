@props(['breadcrumbs' => [], 'color' => 'text-[#414141]', 'mark' => 'breadcrumbs-mark'])

@isset($breadcrumbs)
    <ul id="breadcrumbs">
        <li><a href="{{ route('homepage') }}" class="{{ $color }}">Hlavní stránka</a></li>
        @foreach($breadcrumbs as $title => $url)
            <li class="{{ $mark }} {{ $color }}"><a href="{{ $url }}" class="{{ $color }}">{{ $title }}</a></li>
        @endforeach
    </ul>
@endisset
