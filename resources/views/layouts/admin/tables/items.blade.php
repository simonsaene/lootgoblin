{{-- Items table --}}
<div id="itemsTable" class="table-responsive" style="display: none;">
    <table class="table">
        <thead>
            <tr>
                <th colspan="4" class="text-center">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addItemModal">
                        <i class="bi bi-plus-square-fill"></i> Add
                    </button>
                </th>
            </tr>
            <tr>
                <th>Item Name</th>
                <th>Description</th>
                <th>Market Value</th>
                <th>Vendor Value</th>
                <th>Edit/Delete</th>
            </tr>
        </thead>
        <tbody id="itemsTableBody">
            {{-- Data populate by javascript using AJAX --}}
        </tbody>
    </table>
</div>