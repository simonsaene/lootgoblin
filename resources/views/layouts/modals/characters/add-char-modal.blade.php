<!-- Add Character Modal -->
<div class="modal fade" id="addCharacterModal" tabindex="-1" aria-labelledby="addCharacterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCharacterModalLabel">Add Character</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('characters.create') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="characterName" class="form-label">Character Nam</label>
                        <input type="text" class="form-control" id="characterName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="characterLevel" class="form-label">Level</label>
                        <input type="number" class="form-control" id="characterLevel" name="level" required>
                    </div>
                    <div class="mb-3">
                        <label for="characterClass" class="form-label">Class</label>
                        <select class="form-select" id="characterClass" name="class" required>
                            <option value="" disabled selected>Select Class</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class }}">{{ $class }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Add Character</button>
                </form>
            </div>
        </div>
    </div>
</div>