@extends('layouts.app')

@section('content')
    <div class="container">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        {{-- Page Header --}}
        <section class="py-5 text-center container">
            <div class="row py-lg-5">
                <div class="col-lg-6 col-md-8 mx-auto">

                    @include('layouts.user.modals.profile.edit-profile-image-modal')

                    <h3><i class="bi bi-hand-thumbs-up"></i> {{ $totalLikes }}</h3>
                    <h1 class="fw-light">{{ __($family_name) }}</h1>

                    @if($profile_image)
                        <img src="{{ asset('storage/' . $profile_image) }}" class="img-fluid rounded-circle mb-2" alt="Profile Image" style="width: 150px; height: 150px; object-fit: cover;">
                        <p>
                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editProfileImageModal">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                        </p>
                    @else
                        <svg class="bd-placeholder-img rounded-circle mb-2" width="140" height="140" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false">
                            <title>Placeholder</title>
                            <text x="50%" y="50%" fill="white" font-size="20" text-anchor="middle" dy=".3em">No Image</text>
                            <rect width="100%" height="100%" fill="var(--bs-secondary-color)"></rect>
                        </svg>
                        <p>
                            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#editProfileImageModal">
                                <i class="bi bi-plus-square-fill"></i> Profile Image
                            </button>
                        </p>
                    @endif
                </div>
            </div>
        </section>
        
        <div class="album py-5 bg-body-tertiary">
            <div class="container">
                <p class="text-center">
                    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#addCharacterModal">
                        <i class="bi bi-plus-square-fill"></i> Character
                    </button>
                    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#addFavouriteModal">
                        <i class="bi bi-plus-square-fill"></i> Favourite
                    </button>
                    @include('layouts.user.modals.favourites.add-fav-modal')
                </p>
                <div class="row">
                    <div class="col-md-5">
                        <div class="text-center">
                            @include('layouts.user.modals.profile.edit-gear-image-modal')
                            @if($gear_image)
                                <img src="{{ asset('storage/' . $gear_image) }}" class="img-fluid mb-2" alt="Gear Image">
                                <form action="{{ route('user.delete.gear.image') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editGearImageModal">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    <button type="submit" class="btn btn-outline-danger btn-sm text-end"><i class="bi bi-trash "></i> Delete</button>
                                </form>
                            @else
                                <p>Upload an image of your gear!</p>
                                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#editGearImageModal">
                                    <i class="bi bi-plus-square-fill"></i> Gear Image
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div class="row row-cols-1 row-cols-sm-1 g-3">
                            @if ($characters->isEmpty())
                                <div class="col">
                                    <div class="alert alert-info" role="alert">
                                        No characters yet! Click the button below to create a new character.
                                    </div>
                                </div>
                            @else
                                @foreach ($characters as $character)
                                    <div class="col">
                                        <div class="card shadow-sm">
                                            @if($character->profile_image)
                                                <img src="{{ asset('storage/'.$character->profile_image) }}" class="card-img-top" alt="Character Thumbnail" style="width: 100%; height: 225px; object-fit: contain;">
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
                                                        <form method="POST" action="{{ route('favourite.delete', $fav->id) }}" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')

                                                            <a href="{{ route('grind.location', ['id' => $fav->grindSpot->id]) }}" class="btn btn-outline-warning btn-sm">
                                                                {{ $fav->grindSpot->name }}
                                                            </a>
                                                            <button type="submit" class="btn">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </p>
                                                    @empty
                                                        <p>No Favourites set yet</p>
                                                    @endforelse
                                                </p>
                
                                                <div class="d-flex justify-content-end">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editCharacterModal{{ $character->id }}">
                                                            <i class="bi bi-pencil-square"></i> Edit
                                                        </button>
                                                    </div>
                                        
                                                    @include('layouts.user.modals.characters.edit-char-modal', ['character' => $character])
                
                                                    <form method="POST" action="{{ route('characters.delete', $character->id) }}" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="bi bi-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
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
        

        {{-- Modal for adding character --}}
        @include('layouts.user.modals.characters.add-char-modal')
    </div>
@endsection
