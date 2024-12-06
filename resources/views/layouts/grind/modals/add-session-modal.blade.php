{{-- Add Session Modal --}}
<div class="modal fade" id="add{{ $grindSpot->id }}Modal" tabindex="-1" aria-labelledby="add{{ $grindSpot->id }}ModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add{{ $grindSpot->id }}ModalLabel">Add Session for {{ $grindSpot->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" action="{{ route('grind.session.add') }}" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <div class="mb-3">
                        <input type="hidden" name="grind_spot_id" id="grind_spot_id" value="{{ $grindSpot->id }}">
                    </div>

                    {{-- Loop through grindSpotItems --}}
                    @foreach ($grindSpotItems as $item)
                        <div class="form-floating mb-3">
                            <input type="number" name="item_quantities[{{ $item->id }}]" id="item_{{ $item->id }}" class="form-control" min="0" placeholder="Quantity" required>
                            <label for="item_{{ $item->id }}" class="form-label">{{ $item->item->name }}</label>
                        </div>
                    @endforeach

                    <div class="form-floating mb-3">
                        <input type="number" name="hours" id="hours" class="form-control" step="any" min="0" required>
                        <label for="hours" class="form-label">Hours</label>
                    </div>

                    <div class="mb-3">
                        <label for="loot_image" class="form-label">Loot Image</label>
                        <input type="file" name="loot_image" id="loot_image" class="form-control" accept="image/*">
                    </div>

                    <div class="form-floating mb-3">
                        <input type="url" name="video_link" id="video_link" class="form-control">
                        <label for="video_link" class="form-label">Video Link</label>
                    </div>

                    <div class="form-floating mb-3">
                        <textarea name="notes" id="notes" class="form-control"></textarea>
                        <label for="notes" class="form-label">Notes</label>
                    </div>
                </div>

                <div class="form-floating modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-outline-primary">Finish</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.querySelector('form').addEventListener('submit', function(e) {
        const hoursInput = document.getElementById('hours');
        if (hoursInput.value.trim() === '') {
            alert('Please provide a value for hours.');
            e.preventDefault();
        }
    });
</script>