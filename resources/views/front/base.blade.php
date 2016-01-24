<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Robin Song Creations</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ elixir('css/fapp.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js"></script>
    <script>
        WebFont.load({
            google: {
                families: ["Bitter:400,700,400italic", "Montserrat:400,700", "Bree Serif:regular", "Arvo:regular,700", "Nunito:300,regular,700", "Oxygen:300,regular,700", "Arimo:regular,700", "Lobster:regular", "Patua One:regular", "Playball:regular", "Special Elite:regular", "Oleo Script:regular,700", "Pacifico:regular", "Amatic SC:regular,700", "Dancing Script:regular,700"]
            }
        });
    </script>
    <script type="text/javascript" src="/js/modernizr.js"></script>
    <link rel="shortcut icon" type="image/x-icon" href="https://daks2k3a4ib2z.cloudfront.net/img/favicon.ico">
    <link rel="apple-touch-icon" href="https://daks2k3a4ib2z.cloudfront.net/img/webclip.png">
    @yield('head')
</head>
<body class="body">
@yield('content')
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript" src="/js/webflow.js"></script>
<!--[if lte IE 9]>
<script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif]-->
<script type="text/javascript" src="/js/jquery.shuffle.modernizr.js"></script>
<script src="{{ asset('js/front.js') }}"></script>
@yield('bodyscripts')
</body>
</html>