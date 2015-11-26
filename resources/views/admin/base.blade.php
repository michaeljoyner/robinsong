<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    @section('title')
        <title>Robin Song | Admin</title>
    @show
    <link href='https://fonts.googleapis.com/css?family=Lato:400,300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="{{ elixir('css/app.css') }}"/>
    @yield('head')
</head>
<body>
    @if(Auth::check())
    @include('admin.partials.navbar')
    @endif
    <div class="container">
        @yield('content')
    </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
{{--<script src="/js/main.js"></script>--}}
@yield('bodyscripts')
</body>
</html>