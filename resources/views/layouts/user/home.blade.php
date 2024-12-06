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
                    <h1 class="fw-light">{{ __($family_name) }}</h1>
                    <p>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCharacterModal">
                            <i class="bi bi-plus-square-fill"></i> Character
                        </button>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addFavouriteModal">
                            <i class="bi bi-plus-square-fill"></i> Favourite
                        </button>
                        @include('layouts.user.modals.favourites.add-fav-modal')
                    </p>
                </div>
            </div>
        </section>
        
        <div class="album py-5 bg-body-tertiary">
            <div class="container">
                <div class="row row-cols-1 row-cols-sm-2 g-3">
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
                                        <!-- If profile image exists, display it -->
                                        <img src="storage/{{ $character->profile_image }}" class="card-img-top" alt="Character Thumbnail" style="width: 100%; height: 225px; object-fit: contain;">
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
        
                                        <div class="d-flex justify-content-end">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editCharacterModal{{ $character->id }}">
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
        

        {{-- Modal for adding character --}}
        @include('layouts.user.modals.characters.add-char-modal')
    </div>
@endsection
