{{-- Grind Spots table --}}
<div id="grindSpotsTable" class="table-responsive" style="display: none;">
    <table class="table">
        <thead>
            
            <tr>
                <th colspan="7" class="text-center">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addGrindSpotModal">+</button>
                </th>
            </tr>
            <tr>
                <th>Grind Spot Name</th>
                <th>Location</th>
                <th>Description</th>
                <th>Level</th>
                <th>Gear Score</th>
                <th>Difficulty</th>
                <th>Mechanics</th>
            </tr>
        </thead>
        <tbody id="GrindSpotsTableBody">
            {{-- Data populate by javascript using AJAX --}}
        </tbody>
    </table>
</div>