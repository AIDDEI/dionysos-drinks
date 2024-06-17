<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dionysos Drinks')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <!-- Icon -->
    <link rel="icon" href="img/logo.png" type="image/png">

</head>

<body>

    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-#CDAC50 shadow-sm">
            <div class="container">

                <a class="navbar-brand" style="display: flex; align-items: center;" href="{{ url('/') }}">
                    <img src="{{ asset('img/logo.png') }}" alt="Dionysos Drinks" style="max-height: 100px; max-width: 100px;">
                    {{ config('app.name', 'Dionysos Drinks') }}
                </a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" style="color: white;" href="{{ url('/beers') }}">Beers</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" style="color: white;" href="{{ url('/recommendations') }}">Recommendations</a>
                        </li>
                    @endauth
                </ul>

                <!-- Right Side of Navbar -->
                <ul class="navbar-nav ml-auto">
                    @auth
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" style="color: white;" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Uitloggen
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="nav-link" style="color: white;">Log in</a>
                            </li>
                            @if (Route::has('register'))
                            <li class="nav-item">
                                <a href="{{ route('register') }}" class="nav-link" style="color: white;">Register</a>
                            </li>
                        @endif
                    @endauth
                </ul>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    
</body>

</html>