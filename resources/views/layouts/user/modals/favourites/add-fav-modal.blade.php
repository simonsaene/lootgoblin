<div class="modal fade" id="addFavouriteModal" tabindex="-1" aria-labelledby="addFavouriteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFavouriteModalLabel">Add Favourite Grind Spot</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('favourite.add') }}">
                @csrf
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="character" class="form-label">Select Character</label>
                        <select name="character_id" id="character" class="form-select" required>
                            @foreach ($characters as $character)
                                <option value="{{ $character->id }}">{{ $character->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="grind_spot" class="form-label">Select Grind Spot</label>
                        <select name="grind_spot_id" id="grind_spot" class="form-select" required>
                            @foreach ($grindSpots as $grindSpot)
                                <option value="{{ $grindSpot->id }}">{{ $grindSpot->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Favourite</button>
                </div>
            </form>
        </div>
    </div>
</div>
