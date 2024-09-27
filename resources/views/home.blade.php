@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <div class="row">
        <!-- Left card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">{{ __($family_name) }}</div>
                <div class="card-body">
                    {{ __('No Favourite set yet') }}
                </div>
            </div>
        </div>

<!-- Right side with 3 stacked cards -->
<div class="col-md-8">
    @if ($characters->isEmpty())
        <div class="alert alert-info" role="alert">
            No characters yet! Click the button below to create a new character.
        </div>
        <!-- Button to trigger the modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCharacterModal">
            Create New Character
        </button>
    @else
        @foreach ($characters as $character)
            <div class="card mb-3"> <!-- Add mb-3 for spacing between the cards -->
                <div class="card-header">{{ __($character->name) }}</div>
                <div class="card-body">
                    <p>Level: {{ $character->level }}</p> <!-- Example character level -->
                    <p>Class: {{ $character->class }}</p>
                </div>
            </div>
        @endforeach
    @endif
</div>

<!-- Add Character Modal -->
<div class="modal fade" id="addCharacterModal" tabindex="-1" aria-labelledby="addCharacterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCharacterModalLabel">Add Character</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addCharacterForm" method="POST" action="{{ route('characters.create') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="characterName" class="form-label">Character Name</label>
                        <input type="text" class="form-control" id="characterName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="characterLevel" class="form-label">Level</label>
                        <input type="number" class="form-control" id="characterLevel" name="level" required>
                    </div>
                    <div class="mb-3">
                        <label for="characterClass" class="form-label">Class</label>
                        <select class="form-control" id="characterClass" name="class" required>
                            <option value="" disabled selected>Select Class</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class }}">{{ $class }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Character</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
