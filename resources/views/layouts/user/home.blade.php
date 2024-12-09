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

                    @if($profile_image)
                        <img src="{{ asset('storage/' . $profile_image) }}" class="img-fluid rounded-circle" alt="Profile Image">
                    @else
                    <svg class="bd-placeholder-img rounded-circle" width="140" height="140" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false">
                        <title>Placeholder</title><rect width="100%" height="100%" fill="var(--bs-secondary-color)"></rect>
                    </svg>
                    @endif

                    <h1 class="fw-light">{{ __($family_name) }}</h1>
                    <h3><i class="bi bi-hand-thumbs-up"></i> {{ $totalLikes }}</h3>
                    <p>
                        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#editProfileImageModal">
                            <i class="bi bi-pencil-square"></i> Profile Image
                        </button>
                        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#editGearImageModal">
                            <i class="bi bi-pencil-square"></i> Gear Image
                        </button>
                        @include('layouts.user.modals.profile.edit-profile-image-modal')
                        @include('layouts.user.modals.profile.edit-gear-image-modal')
                    </p>
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
                        <div class="shadow-sm text-center">
                            @if($gear_image)
                                <img src="{{ asset('storage/' . $gear_image) }}" class="img-fluid" alt="Gear Image">
                                <form action="{{ route('user.delete.gear.image') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash "></i> Delete</button>
                                </form>
                            @else
                                <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false">
                                    <title>Placeholder</title>
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
