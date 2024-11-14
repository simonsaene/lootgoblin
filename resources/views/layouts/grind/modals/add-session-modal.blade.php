{{-- Add Session Modal --}}
<div class="modal fade" id="add{{ $grindSpot->id }}Modal" tabindex="-1" aria-labelledby="add{{ $grindSpot->id }}ModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('grind.session.add') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="add{{ $grindSpot->id }}ModalLabel">Add Session for {{ $grindSpot->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="hidden" name="grind_spot_id" id="grind_spot_id" value="{{ $grindSpot->id }}">
                    </div>

                    {{-- Loop through grindSpotItems --}}
                    @foreach ($grindSpotItems as $item)
                        <div class="mb-3">
                            <label for="item_{{ $item->id }}" class="form-label">{{ $item->item->name }}</label>
                            <input type="number" name="item_quantities[{{ $item->id }}]" id="item_{{ $item->id }}" class="form-control" min="0" placeholder="Quantity">
                        </div>
                    @endforeach
                    <div class="mb-3">
                        <label for="hours" class="form-label">Hours</label>
                        <input type="number" name="hours" id="hours" class="form-control" step="0.01" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="loot_image" class="form-label">Loot Image</label>
                        <input type="file" name="loot_image" id="loot_image" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for="video_link" class="form-label">Video Link</label>
                        <input type="url" name="video_link" id="video_link" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" id="notes" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Session</button>
                </div>
            </form>
        </div>
    </div>
</div>