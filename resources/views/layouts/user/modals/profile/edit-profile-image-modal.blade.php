<div class="modal fade text-left" id="editProfileImageModal" tabindex="-1" aria-labelledby="editProfileImageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileImageModalLabel">Edit Profile Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('user.edit.profile.image') }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="modal-body">
                    <div>
                        @if ($profile_image)
                            <img src="{{ asset('storage/' . $profile_image) }}" alt="Profile Image" class="img-fluid rounded-circle" />
                        @else
                            <p>No image on file</p>
                        @endif
                    </div>
                    <div class="mb-3">
                        <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
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