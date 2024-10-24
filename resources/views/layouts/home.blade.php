@extends('layouts.app')

@section('content')
    <div class="container">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <!-- Page Header with Create Button -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Profile</h2>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCharacterModal">
                        Create New Character
                    </button>
                </div>
            </div>
        </div>

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
            @else
                @foreach ($characters as $character)
                    <div class="card mb-3">
                        <div class="card-header">{{ __($character->name) }}</div>
                        <div class="card-body">
                            <p>Level: {{ $character->level }}</p>
                            <p>Class: {{ $character->class }}</p>

                            <button type="submit" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#editCharacterModal{{ $character->id }}">
                                Edit
                            </button>

                            <!-- Edit Character Modal -->
                            <div class="modal fade" id="editCharacterModal{{ $character->id }}" tabindex="-1" aria-labelledby="editCharacterModalLabel{{ $character->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editCharacterModalLabel{{ $character->id }}">Edit Character</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action="{{ route('characters.edit', $character->id) }}">
                                                @csrf
                                                @method('PUT')

                                                <div class="mb-3">
                                                    <label for="characterName{{ $character->id }}" class="form-label">Character Name:</label>
                                                    <input type="text" class="form-control" id="characterName{{ $character->id }}" name="name" 
                                                        value="{{ $character->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="characterLevel{{ $character->id }}" class="form-label">Level:</label>
                                                    <input type="number" class="form-control" id="characterLevel{{ $character->id }}" name="level" 
                                                        value="{{ $character->level }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="characterClass{{ $character->id }}" class="form-label">Class:</label>
                                                    <select class="form-select" id="characterClass{{ $character->id }}" name="class" required>
                                                        <option value="" disabled>Select Class</option>
                                                        @foreach ($classes as $class)
                                                            <option value="{{ $class }}" {{ $character->class == $class ? 'selected' : '' }}>
                                                                {{ $class }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-primary w-100">^</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Button -->
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

    <!-- Add Character Modal -->
        <div class="modal fade" id="addCharacterModal" tabindex="-1" aria-labelledby="addCharacterModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addCharacterModalLabel">Add Character</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('characters.create') }}">
                                @csrf

                                <div class="mb-3">
                                    <label for="characterName" class="form-label">Character Name:</label>
                                    <input type="text" class="form-control" id="characterName" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="characterLevel" class="form-label">Level:</label>
                                    <input type="number" class="form-control" id="characterLevel" name="level" required>
                                </div>
                                <div class="mb-3">
                                    <label for="characterClass" class="form-label">Class:</label>
                                    <select class="form-select" id="characterClass" name="class" required>
                                        <option value="" disabled selected>Select Class</option>
                                        @foreach ($classes as $class)
                                            <option value="{{ $class }}">{{ $class }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Add Character</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </div>
@endsection
