{{-- Edit Grind Spot Modal --}}
<div class="modal fade" id="editGrindSpotModal{{ $spot->id }}" tabindex="-1" aria-labelledby="editGrindSpotModalLabel{{ $spot->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editGrindSpotModalLabel{{ $spot->id }}">Edit Grind Spot</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.grind-spots.edit', $spot->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="grindSpotName{{ $spot->id }}" name="name" value="{{ $spot->name }}" required>
                        <label for="grindSpotName{{ $spot->id }}" class="form-label">Grind Spot Name</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="grindSpotLocation{{ $spot->id }}" name="location" value="{{ $spot->location }}" required>
                        <label for="grindSpotLocation{{ $spot->id }}" class="form-label">Location</label>
                    </div>

                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="grindSpotDescription{{ $spot->id }}" name="description" required>{{ $spot->description }}</textarea>
                        <label for="grindSpotDescription{{ $spot->id }}" class="form-label">Description</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="grindSpotLevel{{ $spot->id }}" name="suggested_level" value="{{ $spot->suggested_level }}" required>
                        <label for="grindSpotLevel{{ $spot->id }}" class="form-label">Level</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="grindSpotGearScore{{ $spot->id }}" name="suggested_gearscore" value="{{ $spot->suggested_gearscore }}" required>
                        <label for="grindSpotGearScore{{ $spot->id }}" class="form-label">Gear Score</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="grindSpotDifficulty{{ $spot->id }}" name="difficulty" value="{{ $spot->difficulty }}" required>
                        <label for="grindSpotDifficulty{{ $spot->id }}" class="form-label">Difficulty(1-5)</label>
                    </div>

                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="grindSpotMechanics{{ $spot->id }}" name="mechanics" required>{{ $spot->mechanics }}</textarea>
                        <label for="grindSpotMechanics{{ $spot->id }}" class="form-label">Mechanics</label>
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