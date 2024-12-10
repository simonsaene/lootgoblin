
<div class="modal fade" id="unflagPostModal{{ $post_id }}" tabindex="-1" aria-labelledby="unflagPostModalLabel{{ $post_id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="unflagPostModalLabel{{ $post_id }}">Unflag Post: {{ $post_id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.unflag.post', $post_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="post_id" value="{{ $post_id }}">
                    <input type="hidden" name="user_id" value="{{ $user_id }}">
                    <input type="hidden" name="status_type" value="unflagged post">

                    <div class="form-floating mb-3">
                        <textarea class="form-control" name="status_end_reason" id="status_end_reason{{ $post_id }}" rows="3"></textarea>
                        <label for="status_end_reason{{ $post_id }}" class="form-label">Unflag Reason</label>
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