{{-- Grind Spot Items table --}}
<div id="grindSpotItemsTable" class="table-responsive" style="display: none;">
    <table class="table">
        <thead>
        
            <tr>
                <th colspan="3" class="text-center bg-body-tertiary">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addGrindSpotItemModal">
                        <i class="bi bi-plus-square-fill"></i> Add
                    </button>
                </th>
            </tr>
            <tr>
                <th>Grind Spot</th>
                <th>Item</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody id="GrinditemsTableBody">
            {{-- Data populate by javascript using AJAX --}}
        </tbody>
    </table>
</div>