{{-- Add Grind Spot Item Modal --}}
<div class="modal fade" id="addGrindSpotItemModal" tabindex="-1" aria-labelledby="addGrindSpotItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGrindSpotItemModalLabel">Add Grind Spot Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.grind-items.add') }}">
                @csrf

                <div class="modal-body">
                    <div class="mb-3">
                        <select class="form-select" id="item_id" name="item_id" required>
                            <option value="" disabled selected>Select Item</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <select class="form-select" id="grind_spot_id" name="grind_spot_id" required>
                            <option value="" disabled selected>Select Grind Spot</option>
                            @foreach($grindSpots as $grindSpot)
                                <option value="{{ $grindSpot->id }}">{{ $grindSpot->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Finish</button>
                </div>
            </form>
        </div>
    </div>
</div>