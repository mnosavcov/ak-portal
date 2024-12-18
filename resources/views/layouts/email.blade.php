<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email {{ config('app.name') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 25px;
        }
    </style>
</head>
<body>
<div class="container">
    {!! $slot !!}

    @if(!empty($unsubscribeUrl))
        <br>
        <br>
        <br>
        <div style="color: #888888; font-size: 12px;">
            {{ __('Nechcete být informováni o nových projektech, událostech v projektech, nebo nechcete dostávat obecné
            informační e-maily? Nastavte si zasílání informačních e-mailů') }}
            <a href="{{ $unsubscribeUrl }}">{{ __('ZDE') }}</a>
            {{ __('Upozorňujeme, že příjem systémových zpráv, které jsou klíčové pro poskytování služby, zrušit nelze.') }}
        </div>
    @endif
</div>
</body>
</html>
