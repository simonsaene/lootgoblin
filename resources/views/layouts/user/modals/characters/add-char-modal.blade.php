{{-- Add Charcter Modal --}}
<div class="modal fade" id="addCharacterModal" tabindex="-1" aria-labelledby="addCharacterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCharacterModalLabel">Add Character</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" action="{{ route('characters.create') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="characterName" name="name" required>
                        <label for="characterName" class="form-label">Character Name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="characterLevel" name="level" required>
                        <label for="characterLevel" class="form-label">Level</label>
                    </div>
                    <div class="mb-3">
                        <label for="profile_image">Upload Image (optional)</label>
                        <input type="file" class="form-control" name="profile_image" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <select class="form-select" id="characterClass" name="class" required>
                            <option value="" disabled selected>Select Class</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class }}">{{ $class }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondry" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-outline-primary">Finish</button>
                </div>
            </form>
        </div>
    </div>
</div>