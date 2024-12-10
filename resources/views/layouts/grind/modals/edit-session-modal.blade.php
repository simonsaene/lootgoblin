{{-- Edit Session Modal --}}
<div class="modal fade" id="editSessionModal{{ $session->id }}" tabindex="-1" aria-labelledby="editSessionModalLabel{{ $session->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('grind.session.edit', $session->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editSessionModalLabel{{ $session->id }}">Editing Session</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="hidden" name="grind_spot_id" id="grind_spot_id" value="{{ $session->grind_spot_id }}">
                    </div>

                    {{-- Loop through grindSpotItems and populate current values --}}
                    @foreach ($grindSpotItems as $item)
                        <div class="form-floating mb-3">
                            <input type="number" name="item_quantities[{{ $item->id }}]" id="item_{{ $item->id }}" class="form-control" min="0" placeholder="Quantity" value="{{ old('item_quantities.'.$item->id, $session->grindSessionItems->firstWhere('grind_spot_item_id', $item->id)->quantity ?? 0) }}" required>
                            <label for="item_{{ $item->id }}" class="form-label">{{ $item->item->name }}</label>
                        </div>
                    @endforeach

                    {{-- Hours field --}}
                    <div class="form-floating mb-3">
                        <input type="number" name="hours" id="hours" class="form-control" step="any" min="0" value="{{ old('hours', $session->hours) }}" required>
                        <label for="hours" class="form-label">Hours</label>
                    </div>

                    {{-- Loot Image --}}
                    <div class="mb-3">
                        <label for="loot_image" class="form-label">Loot Image (optional)</label>
                        <input type="file" name="loot_image" id="loot_image" class="form-control" accept="image/*">

                        
                        {{-- Display current loot image if it exists --}}
                        @if ($session->loot_image)
                            <div class="mt-2">
                                <div class="form-check mt-2">
                                    <input type="checkbox" name="delete_loot_image" id="delete_loot_image" class="form-check-input">
                                    <label for="delete_loot_image" class="form-check-label">Delete current loot image</label>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Video Link --}}
                    <div class="form-floating mb-3">
                        <input type="url" name="video_link" id="video_link" class="form-control" value="{{ old('video_link', $session->video_link) }}">
                        <label for="video_link" class="form-label">Video Link</label>
                    </div>

                    {{-- Notes --}}
                    <div class="form-floating mb-3">
                        <textarea name="notes" id="notes" class="form-control">{{ old('notes', $session->notes) }}</textarea>
                        <label for="notes" class="form-label">Notes</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-outline-primary">Save</button>
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
