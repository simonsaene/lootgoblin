<!-- Edit Item Modal-->
<div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1" aria-labelledby="editItemModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editItemModalLabel{{ $item->id }}">Edit Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('admin.items.edit', $item->id) }}">
                    @csrf
                    @method('PUT')

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="itemName{{ $item->id }}" class="form-label">Item Name</label>
                        <input type="text" class="form-control" id="itemName{{ $item->id }}" name="name" value="{{ $item->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="itemDescription{{ $item->id }}" class="form-label">Description</label>
                        <textarea class="form-control" id="itemDescription{{ $item->id }}" name="description" required>{{ $item->description }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="itemMarketValue{{ $item->id }}" class="form-label">Market Value</label>
                        <input type="number" class="form-control" id="itemMarketValue{{ $item->id }}" name="market_value" value="{{ $item->market_value }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="itemVendorValue{{ $item->id }}" class="form-label">Vendor Value</label>
                        <input type="number" class="form-control" id="itemVendorValue{{ $item->id }}" name="vendor_value" value="{{ $item->vendor_value }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="itemImage{{ $item->id }}">Upload Image (optional)</label>
                        <input type="file" id="itemImage{{ $item->id }}" class="form-control" name="image" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>