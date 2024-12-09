<div class="modal fade" id="verifyVideoModal{{ $session->id }}" tabindex="-1" aria-labelledby="verifyVideoModalLabel{{ $session->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verifyVideoModalLabel{{ $session->id }}">Loot Image for Session: {{ $session->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <!-- Display the loot image -->
                <div class="text-center">
                    <a href="{{ $session->video_link }}" target="_blank" class="btn btn-outline-warning btn-sm">
                        Watch Video (Session: {{ $session->id }})
                    </a>
                </div>
            </div>

            <div class="modal-footer">

                <form method="POST" action="{{ route('admin.delete.video', $session->id) }}" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </form>

                <form action="{{ route('admin.verify.video') }}" method="POST">
                    @csrf
                    <input type="hidden" name="session_id" value="{{ $session->id }}">
                    <button type="submit" class="btn btn-outline-success">
                        <i class="bi bi-check-circle"></i> Verify Video
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>