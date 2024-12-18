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
                    <h3><i class="bi bi-hand-thumbs-up"></i> {{ $totalLikes }}</h3>
                    <h1 class="fw-light">{{ $user->family_name }}</h1>
                    @if($user->profile_image)
                        <img src="{{ asset('storage/' . $user->profile_image) }}" class="img-fluid rounded-circle" alt="Profile Image" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <svg class="bd-placeholder-img rounded-circle" width="140" height="140" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false">
                            <title>Placeholder</title><rect width="100%" height="100%" fill="var(--bs-secondary-color)"></rect>
                        </svg>
                    @endif
                    <p>Viewing Profile</p>
                </div>
            </div>
        </section>
        
        <div class="album py-5 bg-body-tertiary">
            <div class="container">
                <div class="row">
                    <div class="col-md-5">
                        <div class="shadow-sm text-center">
                            @if($user->gear_image)
                                <img src="{{ asset('storage/' . $user->gear_image) }}" class="img-fluid" alt="Gear Image">
                            @else
                                <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false">
                                    <title>Placeholder</title>
                                    <text x="50%" y="50%" fill="white" font-size="20" text-anchor="middle" dy=".3em">No Image</text>
                                    <rect width="100%" height="100%" fill="#55595c"></rect>
                                </svg>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div class="row row-cols-1 row-cols-sm-1 g-3">
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
                                                <img src="{{ asset('storage/' . $character->profile_image) }}" class="card-img-top" alt="Character Thumbnail" style="width: 100%; height: 225px; object-fit: contain;">
                                            @else
                                                <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false">
                                                    <title>Placeholder</title>
                                                    <text x="50%" y="50%" fill="white" font-size="20" text-anchor="middle" dy=".3em">No Image</text>
                                                    <rect width="100%" height="100%" fill="#55595c"></rect>
                                                </svg>
                                            @endif
                                            <div class="card-body">
                                                <h3 class="card-title">{{ __($character->name) }}</h3>
                                                <p class="card-text">{{ $character->level }}. {{ $character->class }}</p>
                                                <p>
                                                    @forelse ($character->favourites as $fav)
                                                    <p>
                                                        <a href="{{ route('grind.location', ['id' => $fav->grindSpot->id]) }}" class="btn btn-sm btn-outline-warning">
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
        </div>
    </div>
@endsection
