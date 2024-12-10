<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.less', 'resources/js/app.js'])
    <script src="/js/tinymce/tinymce.min.js"></script>

    <title>{{ config('app.name') }}</title>
</head>
<body class="antialiased bg-gray-100">
<div class="flex relative" x-data="{navOpen: false}">
    <!-- NAV -->
    @if(auth()->user()->isSuperadmin()):
        @include('layouts.admin-navigation')
    @else
        @include('layouts.translator-navigation')
    @endif
    <!-- END OF NAV -->

    <!-- PAGE CONTENT -->
    {{ $slot }}
</div>
<!-- END OF TABLE -->


<!-- END OF PAGE CONTENT -->
<div x-data id="app-loader" x-show="Alpine.store('app').appLoaderShow" x-cloak>
    <span class="loader"></span>
</div>
</body>
</html>
