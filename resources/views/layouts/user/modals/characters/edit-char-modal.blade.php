{{-- Edit Charcter Modal --}}
<div class="modal fade" id="editCharacterModal{{ $character->id }}" tabindex="-1" aria-labelledby="editCharacterModalLabel{{ $character->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCharacterModalLabel{{ $character->id }}">Edit Character</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" action="{{ route('characters.edit', $character->id) }}">
                <div class="modal-body">
                    @csrf
                    @method('PUT')

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="characterName{{ $character->id }}" name="name" 
                            value="{{ $character->name }}" required>
                        <label for="characterName{{ $character->id }}" class="form-label">Character Name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="characterLevel{{ $character->id }}" name="level" 
                            value="{{ $character->level }}" required>
                            <label for="characterLevel{{ $character->id }}" class="form-label">Level</label>
                    </div>
                    <div class="mb-3">
                        <select class="form-select" id="characterClass{{ $character->id }}" name="class" required>
                            <option value="" disabled>Select Class</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class }}" {{ $character->class == $class ? 'selected' : '' }}>
                                    {{ $class }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>