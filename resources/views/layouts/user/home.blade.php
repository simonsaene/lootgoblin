@extends('layouts.app')

@section('content')
    <div class="container">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        {{-- Page Header --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Family Name: {{ __($family_name) }}</h2>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCharacterModal">
                        Create New Character
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            
            {{-- Left Card --}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Favourite Spots</div>
                    <div class="card-body">
                        @if ($allFavourites->isEmpty())
                            <p>{{ __('No Favourites set yet') }}</p>
                        @else
                            @foreach ($allFavourites as $fav)
                                <p>
                                    <a href="{{ route('grind.location', ['id' => $fav->grindSpot->id]) }}">
                                        {{ $fav->grindSpot->name }}
                                    </a>
                                </p>
                            @endforeach
                        @endif
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addFavouriteModal">
                            Add Favourite
                        </button>
                        @include('layouts.user.modals.favourites.add-fav-modal')
                    </div>
                </div>
            </div>

            {{-- Right side, stacks cards depending on how many characters the user has --}}
            <div class="col-md-8">
                @if ($characters->isEmpty())
                    <div class="alert alert-info" role="alert">
                        No characters yet! Click the button below to create a new character.
                    </div>
                @else
                    @foreach ($characters as $character)
                        <div class="card mb-3">
                            <div class="card-header">{{ __($character->name) }}</div>
                            <div class="card-body">
                                <p>Level: {{ $character->level }}</p>
                                <p>Class: {{ $character->class }}</p>

                                <div class="card mb-3">
                                    <div class="card-header">
                                        Favourites
                                    </div>
                                    <div class="card-body">
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

                                <button type="submit" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editCharacterModal{{ $character->id }}">
                                    ^
                                </button>

                                @include('layouts.user.modals.characters.edit-char-modal')
                            
                                <form method="POST" action="{{ route('characters.delete', $character->id) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">-</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

        @include('layouts.user.modals.characters.add-char-modal')
    </div>
@endsection
