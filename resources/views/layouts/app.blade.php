@props(['htmlTitle', 'htmlDescription'])<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @if(false && !App::environment('local'))
        <script>
            let ad_storage = 'denied';
            let ad_user_data = 'denied';
            let ad_personalization = 'denied';
            let analytics_storage = 'denied';
            let personalization_storage = 'denied';
            @if(isset($_COOKIE['cookies']) && (($_COOKIE['cookies'] & App\Lists\Cookies::ANALYTIC) === App\Lists\Cookies::ANALYTIC))
                analytics_storage = 'granted';
            @endif
                @if(isset($_COOKIE['cookies']) && (($_COOKIE['cookies'] & App\Lists\Cookies::MARKETING) === App\Lists\Cookies::MARKETING))
                ad_storage = 'granted';
            personalization_storage = 'granted';
            ad_user_data = 'granted';
            ad_personalization = 'granted';
            @endif
        </script>

        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-3N75X51ES0"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

            gtag('consent', 'default', {
                'ad_storage': ad_storage,
                'ad_user_data': ad_user_data,
                'ad_personalization': ad_personalization,
                'analytics_storage': analytics_storage,
                'personalization_storage': personalization_storage
            });

            gtag('js', new Date());

            gtag('config', 'G-3N75X51ES0');
        </script>

        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=AW-11102871922"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

            gtag('consent', 'default', {
                'ad_storage': ad_storage,
                'ad_user_data': ad_user_data,
                'ad_personalization': ad_personalization,
                'analytics_storage': analytics_storage,
                'personalization_storage': 'denied'
            });

            gtag('js', new Date());

            gtag('config', 'AW-11102871922');
        </script>

        <!-- Google Tag Manager -->
        <script>(function (w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    'gtm.start':
                        new Date().getTime(), event: 'gtm.js'
                });
                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src =
                    'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', 'GTM-KSW47KX');</script>
        <!-- End Google Tag Manager -->

        @if(isset($_COOKIE['cookies']) && (($_COOKIE['cookies'] & App\Lists\Cookies::MARKETING) === App\Lists\Cookies::MARKETING))
            <script type="text/javascript">
                !function (g, t) {
                    g.type = "text/javascript";
                    g.async = true;
                    g.src =
                        "https://c" + "t.l" + "eady.com/J1Es8vSUxwD2qHf3/L" + ".js";
                    t = t[0];
                    t.parentNode.insertBefore(g, t);
                }(document.createElement("script"),
                    document.getElementsByTagName("script"));
            </script>
        @endif
    @endif

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @isset($htmlDescription)
        <meta name="description" content="{{ $htmlDescription }}">
    @endisset

    <title>{{ config('app.name') . (($htmlTitle ?? null) ? (' - ' . $htmlTitle) : '') }}</title>

    @include('layouts.@fb')

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

@include('layouts.@cookies')
</body>
</html>
