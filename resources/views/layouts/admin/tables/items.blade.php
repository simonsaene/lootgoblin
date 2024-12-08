{{-- Items table --}}
<div id="itemsTable" class="table-responsive col-md-12 col-12 mt-5 mb-5" style="display: none; padding: 0; margin: 0; width: 100%;">
    <table class="table">
        <thead>
            <tr>
                <th colspan="5" class="text-center bg-body-tertiary">
                    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#addItemModal">
                        <i class="bi bi-plus-square-fill"></i> Add
                    </button>
                </th>
            </tr>
            <tr>
                <th>Item Name</th>
                <th>Description</th>
                <th class="text-end">Market Value</th>
                <th class="text-end">Vendor Value</th>
                <th class="text-center">Edit/Delete</th>
            </tr>
        </thead>
        <tbody id="itemsTableBody">
            {{-- Data populate by javascript using AJAX --}}
        </tbody>
    </table>
</div>