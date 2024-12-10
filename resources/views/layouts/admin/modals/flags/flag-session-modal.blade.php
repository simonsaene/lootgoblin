<div class="modal fade" id="flagSessionModal{{ $session_id }}" tabindex="-1" aria-labelledby="flagSessionModalLabel{{ $session_id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="flagSessionModalLabel{{ $session_id }}">Flag Session: {{ $session_id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.flag', $session_id) }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="session_id" id="session_id{{ $session_id }}" value="{{ $session_id }}">
                    <input type="hidden" name="user_id" id="user_id{{ $user_id }}" value="{{ $user_id }}">
                    <input type="hidden" name="status_type" value="flagged session">

                    <div class=" form-floating mb-3">
                        <textarea class="form-control" name="status_start_reason" id="status_start_reason" rows="3" required></textarea>
                        <label for="status_start_reason" class="form-label">Flag Reason</label>
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