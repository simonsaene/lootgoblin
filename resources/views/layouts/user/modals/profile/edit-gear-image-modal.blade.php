<div class="modal fade text-left" id="editGearImageModal" tabindex="-1" aria-labelledby="editGearImageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editGearImageModalLabel">Edit Gear Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('user.edit.gear.image') }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="modal-body">
                    <div>
                        @if ($gear_image)
                            <img src="{{ asset('storage/' . $gear_image) }}" alt="Gear Image" class="img-fluid" />
                        @else
                            <p>No image on file</p>
                        @endif
                    </div>
                    <div class="mb-3">
                        <input type="file" class="form-control" id="gear_image" name="gear_image" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-outline-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>