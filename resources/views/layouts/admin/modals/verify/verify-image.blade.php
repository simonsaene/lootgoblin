<div class="modal fade" id="verifyImageModal{{ $session->id }}" tabindex="-1" aria-labelledby="verifyImageModalLabel{{ $session->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verifyImageModalLabel{{ $session->id }}">Loot Image for Session: {{ $session->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <!-- Display the loot image -->
                <div class="text-center">
                    <img src="{{ asset('storage/' . $session->loot_image) }}" alt="Loot Image" class="img-fluid" style="max-width: 100%; height: auto;">
                </div>
            </div>

            <div class="modal-footer">
                <form method="POST" action="{{ route('admin.delete.image', $session->id) }}" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </form>
                <form action="{{ route('admin.verify.image') }}" method="POST">
                    @csrf
                    <input type="hidden" name="session_id" value="{{ $session->id }}">
                    <button type="submit" class="btn btn-outline-success">
                        <i class="bi bi-check-circle"></i> Verify Image
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>