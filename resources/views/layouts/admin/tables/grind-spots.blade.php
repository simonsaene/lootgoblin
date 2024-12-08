{{-- Grind Spots table --}}
<div id="grindSpotsTable" class="table-responsive mt-5 mb-5" style="display: none; padding: 0; margin: 0; width: 100%;">
    <table class="table">
        <thead>
            
            <tr>
                <th colspan="8" class="text-center bg-body-tertiary">
                    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#addGrindSpotModal">
                        <i class="bi bi-plus-square-fill"></i> Add
                    </button>
                </th>
            </tr>
            <tr>
                <th>Grind Spot Name</th>
                <th>Location</th>
                <th>Description</th>
                <th class="text-end">Level</th>
                <th class="text-end">Gear Score</th>
                <th class="text-end">Difficulty</th>
                <th>Mechanics</th>
                <th>Edit/Delete</th>
            </tr>
        </thead>
        <tbody id="GrindSpotsTableBody">
            {{-- Data populate by javascript using AJAX --}}
        </tbody>
    </table>
</div>