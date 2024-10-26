<!-- Add Grind Spot Modal -->
<div class="modal" id="addGrindSpotModal" tabindex="-1" aria-labelledby="addGrindSpotModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGrindSpotModalLabel">Add Grind Spot</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>    
            <div class="modal-body">
                <form action="{{ route('admin.grind-spots.add') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="grindSpotName">Grind Spot Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="grindSpotLocation">Location</label>
                        <input class="form-control" name="location" required>
                    <div class="mb-3">
                        <label for="grindSpotDescription">Description</label>
                        <textarea class="form-control" name="description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="grindSpotLevel">Level</label>
                        <input type="number" class="form-control" name="suggested_level" required>
                    </div>
                    <div class="mb-3">
                        <label for="grindSpotGearScore">Gear Score</label>
                        <input type="number" class="form-control" name="suggested_gearscore" required>
                    </div>
                    <div class="mb-3">
                        <label for="grindSpotDifficulty">Difficulty(1-5)</label>
                        <input type="number" class="form-control" name="difficulty" required>
                    </div>
                    <div class="mb-3">
                        <label for="grindSpotMechanics">Mechanics</label>
                        <textarea class="form-control" name="mechanics" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Add Grind Spot</button>
                </form>
            </div>
        </div>
    </div>
</div>