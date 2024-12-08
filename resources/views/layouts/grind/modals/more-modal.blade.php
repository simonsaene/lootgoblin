<div class="modal fade" id="moreDetailsModal{{ $session->id }}" tabindex="-1" aria-labelledby="moreDetailsModalLabel{{ $session->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="moreDetailsModalLabel{{ $session->id }}">Grind Session Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    @if($session->loot_image)
                        @if($session->is_image_verified === 0)
                            <i class="bi bi-slash-square" title="Image not verified"></i> Image Unverified
                        @elseif($session->is_image_verified === 1)
                            <img src="{{ asset('storage/' . $session->loot_image ) }}" alt="Loot Image" style="max-width: 150px;">
                        @else
                            No Loot Image
                        @endif
                    @else
                    No Loot Image
                    @endif
                    <br>

                    @if($session->video_link)
                        @if($session->is_video_verified === 0)
                            <i class="bi bi-slash-square" title="Video not verified"></i> Video Unverified.
                        @elseif($session->is_video_verified === 1)
                            <a href="{{ $session->video_link }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                Watch Video
                            </a>
                        @else
                            No Video
                        @endif
                    @else
                        No Video
                    @endif
                    <br>

                    @if($session->notes)
                        <strong>Notes:</strong> {{ $session->notes }}
                    @else
                        No Notes
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
