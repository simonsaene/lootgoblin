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

        <!-- Button to trigger the modal for creating a new character -->
        <button type="submit" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCharacterModal">
            Create New Character
        </button>
    @else
        @foreach ($characters as $character)
            <div class="card mb-3">
                <div class="card-header">{{ __($character->name) }}</div>
                <div class="card-body">
                    <p>Level: {{ $character->level }}</p>
                    <p>Class: {{ $character->class }}</p>

                    <button type="submit" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addCharacterModal">
                        Edit
                    </button>

                    <!-- Delete Button -->
                    <form method="POST" action="{{ route('characters.destroy', $character->id) }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        @endforeach
    @endif
</div>

<!-- Add/Edit Character Modal -->
<div class="modal fade" id="addCharacterModal" tabindex="-1" aria-labelledby="addCharacterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCharacterModalLabel">{{ isset($character) ? 'Edit Character' : 'Add Character' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ isset($character) ? route('characters.edit', $character->id) : route('characters.create') }}">
                    @csrf
                    @if (isset($character))
                        @method('PUT')
                    @endif

                    <div class="mb-3">
                        <label for="characterName" class="form-label">Character Name:</label>
                        <input type="text" class="form-control" id="characterName" name="name" 
                               value="{{ isset($character) ? $character->name : '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="characterLevel" class="form-label">Level:</label>
                        <input type="number" class="form-control" id="characterLevel" name="level" 
                               value="{{ isset($character) ? $character->level : '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="characterClass" class="form-label">Class:</label>
                        <select class="form-select" id="characterClass" name="class" required>
                            <option value="" disabled {{ !isset($character) ? 'selected' : '' }}>Select Class</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class }}" {{ isset($character) && $character->class == $class ? 'selected' : '' }}>{{ $class }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        {{ isset($character) ? 'Edit Character' : 'Add Character' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
