<div class="modal fade" id="flagPostModal{{ $post_id }}" tabindex="-1" aria-labelledby="flagPostModalLabel{{ $post_id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="flagPostModalLabel{{ $post_id }}">Flag Post: {{ $post_id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.flag', $post_id) }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="post_id" id="post_id{{ $post_id }}" value="{{ $post_id }}">
                    <input type="hidden" name="user_id" id="user_id{{ $user_id }}" value="{{ $user_id }}">
                    <input type="hidden" name="status_type" value="flagged post">

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