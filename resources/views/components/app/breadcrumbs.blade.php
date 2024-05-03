@props(['breadcrumbs' => []])

@isset($breadcrumbs)
    <ul id="breadcrumbs">
        <li><a href="{{ route('homepage') }}">Hlavní stránka</a></li>
        @foreach($breadcrumbs as $title => $url)
            <li class="breadcrumbs-mark"><a href="{{ $url }}" class="">{{ $title }}</a></li>
        @endforeach
    </ul>
@endisset
