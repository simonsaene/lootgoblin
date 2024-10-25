 <!-- Add Item Modal -->
 <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addItemModalLabel">Add Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.items.add') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="itemName">Item Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="itemDescription">Description</label>
                        <textarea class="form-control" name="description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="marketValue">Market Value</label>
                        <input type="number" class="form-control" name="market_value" required>
                    </div>
                    <div class="mb-3">
                        <label for="vendorValue">Vendor Value</label>
                        <input type="number" class="form-control" name="vendor_value" required>
                    </div>
                    <div class="mb-3">
                        <label for="itemImage">Upload Image (optional)</label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-success">Add Item</button>
                </form>
            </div>
        </div>
    </div>
</div>