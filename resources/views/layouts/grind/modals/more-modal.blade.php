<div class="modal fade" id="moreDetailsModal{{ $session->id }}" tabindex="-1" aria-labelledby="moreDetailsModalLabel{{ $session->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="moreDetailsModalLabel{{ $session->id }}">Grind Session Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <strong>Loot Image:</strong> 
                    @if($session->loot_image)
                        @if($session->is_image_verified === 0)
                            <i class="bi bi-slash-square" title="Image not verified"></i> Image Unverified
                        @elseif($session->is_image_verified === 1)
                            <img src="{{ asset('storage/' . $session->loot_image ) }}" alt="Loot Image" style="max-width: 150px;">
                        @else
                            N/A
                        @endif
                    @else
                        N/A
                    @endif
                    <br>

                    <strong>Video:</strong>
                    @if($session->video_link)
                        @if($session->is_video_verified === 0)
                            <i class="bi bi-slash-square" title="Video not verified"></i> Video Unverified.
                        @elseif($session->is_video_verified === 1)
                            {{ $session->video_link }}
                        @else
                            N/A
                        @endif
                    @else
                        N/A
                    @endif
                    <br>
                    <strong>Notes:</strong> {{ $session->notes ?? 'N/A' }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
