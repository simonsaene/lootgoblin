<!-- Edit Grind Spot Modal-->
<div class="modal fade" id="editGrindSpotModal{{ $spot->id }}" tabindex="-1" aria-labelledby="editGrindSpotModalLabel{{ $spot->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editGrindSpotModalLabel{{ $spot->id }}">Edit Grind Spot</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('admin.grindspots.edit', $spot->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="grindSpotName{{ $spot->id }}" class="form-label">Grind Spot Name</label>
                        <input type="text" class="form-control" id="grindSpotName{{ $spot->id }}" name="name" value="{{ $spot->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="grindSpotLocation{{ $spot->id }}" class="form-label">Location</label>
                        <input type="text" class="form-control" id="grindSpotLocation{{ $spot->id }}" name="location" value="{{ $spot->location }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="grindSpotDescription{{ $spot->id }}" class="form-label">Description</label>
                        <textarea class="form-control" id="grindSpotDescription{{ $spot->id }}" name="description" required>{{ $spot->description }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="grindSpotLevel{{ $spot->id }}" class="form-label">Level</label>
                        <input type="number" class="form-control" id="grindSpotLevel{{ $spot->id }}" name="suggested_level" value="{{ $spot->suggested_level }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="grindSpotGearScore{{ $spot->id }}" class="form-label">Gear Score</label>
                        <input type="number" class="form-control" id="grindSpotGearScore{{ $spot->id }}" name="suggested_gearscore" value="{{ $spot->suggested_gearscore }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="grindSpotDifficulty{{ $spot->id }}" class="form-label">Difficulty(1-5)</label>
                        <input type="number" class="form-control" id="grindSpotDifficulty{{ $spot->id }}" name="difficulty" value="{{ $spot->difficulty }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="grindSpotMechanics{{ $spot->id }}" class="form-label">Mechanics</label>
                        <textarea class="form-control" id="grindSpotMechanics{{ $spot->id }}" name="mechanics" required>{{ $spot->mechanics }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>