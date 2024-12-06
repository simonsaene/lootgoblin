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
                        <img src="{{ asset($session->loot_image) }}" alt="Loot Image" style="max-width: 150px;">
                    @else
                        N/A
                    @endif
                    <br>
                    <strong>Video:</strong> {{ $session->video_link ?? 'N/A' }}
                    <br>
                    <strong>Notes:</strong> {{ $session->notes ?? 'N/A' }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
