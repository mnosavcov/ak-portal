<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @isset($htmlDescription)
        <meta name="description" content="{{ $htmlDescription }}">
    @endisset

    <title>{{ config('app.name') }}</title>

    <!-- Scripts -->
    @vite(['resources/css/app.less', 'resources/js/app.js'])

    <link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon-16x16.png">
    <link rel="manifest" href="/favicons/site.webmanifest">
    <link rel="mask-icon" href="/favicons/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="/favicons/favicon.ico">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-config" content="/favicons/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">

    <script src="/js/tinymce/tinymce.min.js"></script>
</head>
<body class="font-sans antialiased bg-[#f8f8f8] text-[#31363A]">
<div class="min-h-screen">
    @include('layouts.app-navigation')

    <!-- Page Content -->
    <main>
        {{ $slot }}
    </main>

    @include('app.@footer')
</div>
</body>
</html>
