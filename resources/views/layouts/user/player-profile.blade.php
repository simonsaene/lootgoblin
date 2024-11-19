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
                <h2>{{ $user->family_name }}'s Profile</h2>
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
                </div>
            </div>
        </div>

        {{-- Right side, stacks cards depending on how many characters the user has --}}
        <div class="col-md-8">
            @if ($characters->isEmpty())
                <div class="alert alert-info" role="alert">
                    No characters yet!
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
                                    @forelse ($character->favourites as $fav)
                                        <p>
                                            <a href="{{ route('grind.location', ['id' => $fav->grindSpot->id]) }}">
                                                {{ $fav->grindSpot->name }}
                                            </a>
                                        </p>
                                    @empty
                                        <p>No Favourites set yet</p>
                                    @endforelse
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

@endsection