<div class="modal fade" id="flagSessionReasonModal{{ $session_id }}" tabindex="-1" aria-labelledby="flagSessionReasonModalLabel{{ $session_id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="flagSessionReasonModalLabel{{ $session_id }}">Flag Reason for Session:</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ $flaggedSessions[$session_id] }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>