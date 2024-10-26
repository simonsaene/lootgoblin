{{-- AJAX for showing tables --}}
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
                route = '{{ route('admin.grind-items') }}';
                tbody = document.getElementById('GrinditemsTableBody');
                break;
            case 'grindSpotsTable':
                route = '{{ route('admin.grind-spots') }}';
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
                                            <form method="POST" action="{{ route('admin.grind-items.delete', '') }}/${grindItem.id}" style="display:inline;">
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
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editGrindSpotModal${spot.id}">^</button>
                                            <form method="POST" action="{{ route('admin.grind-spots.delete', '') }}/${spot.id}" style="display:inline;">
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
                }
                document.getElementById(tableId).style.display = 'block';
            })
            .catch(error => console.error('Error fetching items:', error));
    }
</script>