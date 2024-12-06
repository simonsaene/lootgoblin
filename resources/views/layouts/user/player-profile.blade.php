@extends('layouts.app')

@section('content')
    <div class="container">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        {{-- Page Header --}}
        <section class="py-5 text-center container">
            <div class="row py-lg-5">
                <div class="col-lg-6 col-md-8 mx-auto">
                    <h1 class="fw-light">{{ $user->family_name }}</h1>
                    <p>Viewing Profile</p>
                </div>
            </div>
        </section>
        
        <div class="album py-5 bg-body-tertiary">
            <div class="container">
                <div class="row row-cols-1 row-cols-sm-2 g-3">
                    @if ($characters->isEmpty())
                        <div class="col">
                            <div class="alert alert-info" role="alert">
                                {{ $user->family_name }} Currently has no characters.
                            </div>
                        </div>
                    @else
                        @foreach ($characters as $character)
                            <div class="col">
                                <div class="card shadow-sm">
                                    @if($character->profile_image)
                                        <!-- If profile image exists, display it -->
                                        <img src="{{ asset('storage/'.$character->profile_image) }}" class="card-img-top" alt="Character Thumbnail" style="width: 100%; height: 225px; object-fit: contain;">
                                    @else
                                        <!-- Fallback to SVG if no image exists -->
                                        <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false">
                                            <title>Placeholder</title>
                                            <rect width="100%" height="100%" fill="#55595c"></rect>
                                        </svg>
                                    @endif
                                    <div class="card-body">
                                        <h3 class="card-title">{{ __($character->name) }}</h3>
                                        <p class="card-text">{{ $character->level }}. {{ $character->class }}</p>
                                        <p>
                                            @forelse ($character->favourites as $fav)
                                            <p>
                                                <a href="{{ route('grind.location', ['id' => $fav->grindSpot->id]) }}">
                                                    {{ $fav->grindSpot->name }}
                                                </a>
                                            </p>
                                            @empty
                                                <p>No Favourites set yet</p>
                                            @endforelse
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
