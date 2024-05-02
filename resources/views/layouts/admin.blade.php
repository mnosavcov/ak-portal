<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.less', 'resources/js/app.js'])

    <title>{{ config('app.name') }}</title>
</head>
<body class="antialiased bg-gray-100">
<div class="flex relative" x-data="{navOpen: false}">
    <!-- NAV -->
    @include('layouts.admin-navigation')
    <!-- END OF NAV -->

    <!-- PAGE CONTENT -->
    {{ $slot }}
</div>
<!-- END OF TABLE -->


<!-- END OF PAGE CONTENT -->
</body>
</html>
