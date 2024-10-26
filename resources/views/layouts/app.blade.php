<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'lootgoblin') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-..." crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-..." crossorigin="anonymous"></script>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'lootgoblin') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <!-- Profile link -->
                                    <a class="dropdown-item" href="{{ route('home') }}">
                                        {{ __('Profile') }}
                                    </a>
                                
                                    <!-- Settings link -->
                                    <a class="dropdown-item" href="{{ route('settings') }}">
                                        {{ __('Settings') }}
                                    </a>
                                
                                    <!-- Admin Dashboard link (only visible if user is admin) -->
                                    @if(auth()->user() && auth()->user()->is_admin)
                                        <a class="dropdown-item" href="{{ route('admin.home') }}">
                                            {{ __('Admin Dashboard') }}
                                        </a>
                                    @endif
                                
                                    <!-- Logout link -->
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

         <!-- Main container with sidebar and content -->
         <div class="container-fluid">
            <div class="row">
                <!-- Main container with sidebar and content -->
                <div class="d-flex">
                    @auth
                        <!-- Left Sidebar -->
                        <nav id="sidebar" class="bg-light sidebar" style="width: 200px;">
                            <div class="position-sticky">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link collapsed" data-bs-toggle="collapse" href="#userSection" role="button" aria-expanded="false" aria-controls="userSection">
                                            User
                                        </a>
                                        <div class="collapse" id="userSection">
                                            <ul class="nav flex-column ms-3">

                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ route('home') }}">
                                                        {{ __('Profile') }}
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ route('settings') }}">
                                                        {{ __('Settings') }}
                                                    </a>
                                                </li>

                                                @if(auth()->user() && auth()->user()->is_admin)
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="{{ route('admin.home') }}">
                                                            {{ __('Admin Dashboard') }}
                                                        </a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link collapsed" data-bs-toggle="collapse" href="#grindSpots" role="button" aria-expanded="false" aria-controls="grindSpots">
                                            {{ __('Grind') }}
                                        </a>
                                        <div class="collapse" id="grindSpots">
                                            <ul class="nav flex-column ms-3">
                                            <li class="nav-item">
                                                    <a class="nav-link" href="{{ route('grind.summary') }}">
                                                        {{ __('Summary') }}
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ route('settings') }}">
                                                        {{ __('Jade Forest') }}
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ route('settings') }}">
                                                        {{ __('Gyfin Rhasia Temple: Underground') }}
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ route('settings') }}">
                                                        {{ __('Dekia: Crescent Shrine') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    @endauth

                    <!-- Main Content Area -->
                    <main class="flex-grow-1 px-4 py-4">
                        @yield('content')
                    </main>
                </div>
            </div>
        </div>

        <footer class="py-16 text-center text-sm text-black dark:text-white/70">
            <span class="text-muted">Soubanty Saenephommachanh, 000089356 &copy; {{ date('Y') }}</span>
        </footer>
    </div>
</body>
</html>