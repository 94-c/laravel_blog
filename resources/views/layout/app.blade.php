<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="Authorization">
        <title>{{ config('app.name', 'Laravel-Blog') }}</title>
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="Authorization:bearer" content="{{ $token ?? '' }}">
        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <!-- Styles -->
        {{--         <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <div id="app">
            <nav id="navbar">
                <div class="navbar-logo">
                    <a href="{{ url('/') }}">
                        <h1>{{ config('app.name', 'Laravel') }}</h1>
                    </a>
                    <ul class="navbar-nav">
                        {{ $auth->email ??'' }}
                        @guest
                            <li class="nav-item">
                                <a href="login">Login</a>
                            </li>
                                <li class="nav-item">
                                    <a href="register">Register</a>
                                </li>
                        @else
                            <li class="nav-item">
                                {{ Auth::user()->name }} <span class="caret"></span>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        @endguest
                            <li class="nav-item"><a href="posts">Post List</a></li>
                            <li class="nav-item"><a href=""></a></li>
                            <li class="nav-item"><a href=""></a></li>
                            <li class="nav-item"><a href=""></a></li>
                            <li class="nav-item"><a href=""></a></li>
                    </ul>
                </div>
            </nav>

            <div class="content">
                @yield('content')
            </div>
        </div>
    </body>
</html>
