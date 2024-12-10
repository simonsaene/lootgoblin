
<div class="modal fade" id="unflagSessionModal{{ $session_id }}" tabindex="-1" aria-labelledby="unflagSessionModalLabel{{ $session_id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="unflagSessionModalLabel{{ $session_id }}">Unflag Session: {{ $session_id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.unflag.session', $session_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="session_id" value="{{ $session_id }}">
                    <input type="hidden" name="user_id" value="{{ $user_id }}">
                    <input type="hidden" name="status_type" value="unflagged session">

                    <div class="form-floating mb-3">
                        <textarea class="form-control" name="status_end_reason" id="status_end_reason{{ $session_id }}" rows="3"></textarea>
                        <label for="status_end_reason{{ $session_id }}" class="form-label">Unflag Reason</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-outline-primary">Finish</button>
                </div>
            </form>
        </div>
    </div>
</div>