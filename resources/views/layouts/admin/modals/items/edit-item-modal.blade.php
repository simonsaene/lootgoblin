{{-- Edit Item Modal --}}
<div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1" aria-labelledby="editItemModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editItemModalLabel{{ $item->id }}">Edit Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" action="{{ route('admin.items.edit', $item->id) }}"  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="itemName{{ $item->id }}" name="name" value="{{ $item->name }}" required>
                        <label for="itemName{{ $item->id }}" class="form-label">Item Name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="itemDescription{{ $item->id }}" name="description" required>{{ $item->description }}</textarea>
                        <label for="itemDescription{{ $item->id }}" class="form-label">Description</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="itemMarketValue{{ $item->id }}" name="market_value" value="{{ $item->market_value }}" required>
                        <label for="itemMarketValue{{ $item->id }}" class="form-label">Market Value</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="itemVendorValue{{ $item->id }}" name="vendor_value" value="{{ $item->vendor_value }}" required>
                        <label for="itemVendorValue{{ $item->id }}" class="form-label">Vendor Value</label>
                    </div>
                    <div class="mb-3">
                        <label for="itemImage{{ $item->id }}">Upload Image (optional)</label>
                        <input type="file" id="itemImage{{ $item->id }}" class="form-control" name="image" accept="image/*">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="is_trash" id="is_trash">
                        <label class="form-check-label" for="is_trash">Trash Loot?</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>