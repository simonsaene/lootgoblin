{{-- Add Item Modal --}}
 <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addItemModalLabel">Add Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.items.add') }}" method="POST" enctype="multipart/form-data">   
                <div class="modal-body justify-content-center">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="name" required>
                        <label for="itemName">Item Name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea class="form-control" name="description" required></textarea>
                        <label for="itemDescription">Description</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" name="market_value" required>
                        <label for="marketValue">Market Value</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" name="vendor_value" required>
                        <label for="vendorValue">Vendor Value</label>
                    </div>
                    <div class="mb-3">
                        <label for="image">Upload Image (optional)</label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="is_trash" id="is_trash" value="true">
                        <label class="form-check-label" for="is_trash">Trash Loot?</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Finish</button>
                </div>
            </form>
        </div>
    </div>
</div>