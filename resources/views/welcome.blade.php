<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- CSRF Token --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'lootgoblin') }}</title>


        {{-- Fonts --}}
        <link rel="dns-prefetch" href="//fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">


        {{-- Scripts --}}
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/chroma-js/2.1.0/chroma.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    </head>
    <body data-bs-theme="dark">
        <main >
            <div class="container">

                <hr class="featurette-divider">
        
                <div class="row featurette">
                    <div class="col-md-7">
                        <h2 class="featurette-heading">Manage your profile, <span class="text-muted">Flex your gear.</span></h2>
                        <p class="lead">Add charcters and manage favourite grind spots per character</p>
                        <!-- Login and Register buttons -->
                        @if (Route::has('login'))
                            <div class="mt-4">
                                @auth
                                    <a href="{{ route('user.home') }}" class="btn btn-outline-primary ml-2">Profile</a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary">Login</a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="btn btn-outline-secondary ml-2">Register</a>
                                    @endif
                                @endauth
                            </div>
                        @endif
                    </div>
                    <div class="col-md-5">
                        <img class="featurette-image img-fluid mx-auto" alt="Gear image" style="width: 500px; height: 500px;" src="{{ asset('storage/root_page_images/gear.png') }}" data-holder-rendered="true">
                    </div>
                </div>
        
                <hr class="featurette-divider">
        
                <div class="row featurette">
                    <div class="col-md-7 order-md-2">
                        <h2 class="featurette-heading">Track your loot, <span class="text-muted">Flex your skills.</span></h2>
                        <p class="lead">Create grind session to track your loot, the summary will show you where you spend most of your hours.</p>
        
                        <!-- Login and Register buttons -->
                        @if (Route::has('login'))
                            <div class="mt-4">
                                @auth
                                    <a href="{{ route('user.home') }}" class="btn btn-outline-primary ml-2">Profile</a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary">Login</a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="btn btn-outline-secondary ml-2">Register</a>
                                    @endif
                                @endauth
                            </div>
                        @endif
                    </div>
                    <div class="col-md-5 order-md-1">
                        <img class="featurette-image img-fluid mx-auto" alt="Loot image" style="width: 500px; height: 500px;" src="{{ asset('storage/root_page_images/loot.png') }}" data-holder-rendered="true">
                    </div>
                </div>
        
                <hr class="featurette-divider">
        
                <div class="row featurette">
                    <div class="col-md-7">
                        <h2 class="featurette-heading">View players grind sessions and profile, <span class="text-muted">Checkmate.</span></h2>
                        <p class="lead">Find other players, view their sessions, characters and favourites. How well are you doing in comparision?</p>
                        <!-- Login and Register buttons -->
                        @if (Route::has('login'))
                            <div class="mt-4">
                                @auth
                                    <a href="{{ route('user.home') }}" class="btn btn-outline-primary ml-2">Profile</a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary">Login</a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="btn btn-outline-secondary ml-2">Register</a>
                                    @endif
                                @endauth
                            </div>
                        @endif
                    </div>
                    <div class="col-md-5">
                        <img class="featurette-image img-fluid mx-auto" alt="Profile image" style="width: 500px; height: 500px;" src="{{ asset('storage/root_page_images/profile2.png') }}" data-holder-rendered="true">
                    </div>
                </div>
            <hr class="featurette-divider">
        </main>
    </body>
</html>
