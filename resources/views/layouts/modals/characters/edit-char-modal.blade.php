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
                        <label for="characterName{{ $character->id }}" class="form-label">Character Name</label>
                        <input type="text" class="form-control" id="characterName{{ $character->id }}" name="name" 
                            value="{{ $character->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="characterLevel{{ $character->id }}" class="form-label">Level</label>
                        <input type="number" class="form-control" id="characterLevel{{ $character->id }}" name="level" 
                            value="{{ $character->level }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="characterClass{{ $character->id }}" class="form-label">Class</label>
                        <select class="form-select" id="characterClass{{ $character->id }}" name="class" required>
                            <option value="" disabled>Select Class</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class }}" {{ $character->class == $class ? 'selected' : '' }}>
                                    {{ $class }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>