@extends('layouts.app')

@section('content')
    <div class="container">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <!-- First row (mb-4 adds margin bottom) -->
        <div class="row mb-4">

            <!-- First card -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">{{ __('General') }}</div>
                    <div class="card-body">
                        {{ __('EEmpty') }}
                    </div>
                </div>
            </div>

            <!-- Second card -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">{{ __('Window') }}</div>
                    <div class="card-body">
                        {{ __('Empty') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Second row -->
        <div class="row">

            <!-- Third card -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">{{ __('Verification') }}</div>
                    <div class="card-body">
                        {{ __('Verification Options') }}
                </div>
                </div>
            </div>

            <!-- Fourth card -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">{{ __('Test') }}</div>
                    <div class="card-body">
                    
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container" style="margin: 20px;">
        <!-- Buttons Row -->
        <div class="row mb-4 justify-content-center">
            <div class="col-md-12 text-center">
                <h2 class="mb-3">Tables</h2>
                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-primary me-2" onclick="fetchData('itemsTable')">
                        Show Items
                    </button>
                    <button type="button" class="btn btn-primary me-2" onclick="fetchData('grindSpotItemsTable')">
                        Show Grind Spot Items
                    </button>
                    <button type="button" class="btn btn-primary" onclick="fetchData('grindSpotsTable')">
                        Show Grind Spots
                    </button>
                </div>
            </div>
        </div>
    
        <!-- Tables Section -->
        <div class="row">
            <div class="col-md-12">
                <div id="tablesContainer">
                    <div id="itemsTable" class="table-responsive" style="display: none;">
                        <table class="table">
                            <thead>
                                <!-- add/update/delete buttons -->
                                <tr>
                                    <th colspan="4" class="text-center">
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addItemModal">+</button>
                                    </th>
                                </tr>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Description</th>
                                    <th>Market Value</th>
                                    <th>Vendor Value</th>
                                </tr>
                            </thead>
                            <tbody id="itemsTableBody">
                                <!-- Items will be populated here -->
                            </tbody>
                        </table>
                    </div>
    
                    <div id="grindSpotItemsTable" class="table-responsive" style="display: none;">
                        <table class="table">
                            <thead>
                                <!-- add/update/delete buttons -->
                                <tr>
                                    <th colspan="2" class="text-center">
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addGrindSpotItemModal">+</button>
                                    </th>
                                </tr>
                                <tr>
                                    <th>Grind Spot</th>
                                    <th>Item</th>
                                </tr>
                            </thead>
                            <tbody id="GrinditemsTableBody">
                                <!-- Populate with grind spot items -->
                            </tbody>
                        </table>
                    </div>
    
                    <div id="grindSpotsTable" class="table-responsive" style="display: none;">
                        <table class="table">
                            <thead>
                                <!-- add/update/delete buttons -->
                                <tr>
                                    <th colspan="7" class="text-center">
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addGrindSpoModal">+</button>
                                    </th>
                                </tr>
                                <tr>
                                    <th>Grind Spot Name</th>
                                    <th>Location</th>
                                    <th>Description</th>
                                    <th>Level</th>
                                    <th>Gear Score</th>
                                    <th>Difficulty</th>
                                    <th>Mechanics</th>
                                </tr>
                            </thead>
                            <tbody id="GrindSpotsTableBody">
                                <!-- Populate with grind spots -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.modals.tables.add-item-modal')

        @foreach ($items as $item)
            @include('layouts.modals.tables.edit-item-modal', ['item' => $item])
        @endforeach

    </div>

    <script>
        function fetchData(tableId) {
            let route = '';
            let tbody;

            const tableIds = ['itemsTable', 'grindSpotItemsTable', 'grindSpotsTable'];
    
            tableIds.forEach(id => {
                document.getElementById(id).style.display = 'none';
            });

            switch (tableId)
            {
                case 'itemsTable':
                    route = '{{ route('admin.items') }}';
                    tbody = document.getElementById('itemsTableBody');
                    break;
                case 'grindSpotItemsTable':
                    route = '{{ route('admin.grinditems') }}';
                    tbody = document.getElementById('GrinditemsTableBody');
                    break;
                case 'grindSpotsTable':
                    route = '{{ route('admin.grindspots') }}';
                    tbody = document.getElementById('GrindSpotsTableBody');
                    break;
                default:
                    console.error('Invalid table ID');
                    return;
            }

            console.log('Fetching from route:', route);
            fetch(route)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    tbody.innerHTML = '';
                    
                    switch (tableId)
                    {
                        case 'itemsTable':
                            data.forEach(item => {
                                const row = `
                                    <tr>
                                        <td>${item.name}</td>
                                        <td>${item.description}</td>
                                        <td>${item.market_value}</td>
                                        <td>${item.vendor_value}</td>
                                        <td>                                   
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editItemModal${item.id}">^</button>
                                            <form method="POST" action="{{ route('admin.items.delete', '') }}/${item.id}" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">-</button>
                                            </form>
                                        </td>
                                    </tr>
                                `;
                                tbody.innerHTML += row;
                            });
                            break;

                        case 'grindSpotItemsTable':
                            data.forEach(grindItem => {
                                console.log(grindItem)
                                if (grindItem.grind_spot && grindItem.item) {
                                    const row = `
                                        <tr>
                                            <td>${grindItem.grind_spot.name}</td> 
                                            <td>${grindItem.item.name}</td>
                                            <td>                                          
                                                <button type="button" class="btn btn-primary" onclick="">^</button>
                                                <form method="POST" action="{{ route('admin.grinditems.delete', '') }}/${grindItem.id}" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">-</button>
                                                </form>
                                            </td>
                                        </tr>
                                    `;
                                    tbody.innerHTML += row;
                                } else {
                                    console.error('Invalid grind item:', grindItem);
                                }
                            });
                            break;

                        case 'grindSpotsTable':
                            data.forEach(spot => {
                                const row = `
                                    <tr>
                                        <td>${spot.name}</td>
                                        <td>${spot.location}</td>
                                        <td>${spot.description}</td>
                                        <td>${spot.suggested_level}</td>
                                        <td>${spot.suggested_gearscore}</td>
                                        <td>${spot.difficulty}</td>
                                        <td>${spot.mechanics}</td>
                                        <td>                                          
                                            <button type="button" class="btn btn-primary" onclick="">^</button>
                                            <button type="button" class="btn btn-danger" onclick="">-</button>
                                        </td>
                                    </tr>
                                `;
                                tbody.innerHTML += row;
                            });
                            break;
                    }
                    document.getElementById(tableId).style.display = 'block';
                })
                .catch(error => console.error('Error fetching items:', error));
        }
    </script>

@endsection