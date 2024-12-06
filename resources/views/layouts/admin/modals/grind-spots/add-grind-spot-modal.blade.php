{{-- Add Grind Spot Modal --}}
<div class="modal" id="addGrindSpotModal" tabindex="-1" aria-labelledby="addGrindSpotModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGrindSpotModalLabel">Add Grind Spot</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('admin.grind-spots.add') }}" method="POST">
                @csrf 
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="name" required>
                        <label for="grindSpotName">Grind Spot Name</label>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input class="form-control" name="location" required>
                        <label for="grindSpotLocation">Location</label>
                    </div>

                    <div class="form-floating mb-3">
                        <textarea class="form-control" name="description" required></textarea>
                        <label for="grindSpotDescription">Description</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" name="suggested_level" required>
                        <label for="grindSpotLevel">Level</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" name="suggested_gearscore" required>
                        <label for="grindSpotGearScore">Gear Score</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" name="difficulty" required>
                        <label for="grindSpotDifficulty">Difficulty(1-5)</label>
                    </div>

                    <div class="form-floating mb-3">
                        <textarea class="form-control" name="mechanics" required></textarea>
                        <label for="grindSpotMechanics">Mechanics</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-outline-primary">Finish</button>
                </div>
            </form>
        </div>
    </div>
</div>