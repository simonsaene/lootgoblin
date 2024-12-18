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
                const baseUrl = "{{ asset('storage') }}/";
                switch (tableId)
                {
                    case 'itemsTable':
                        data.forEach(item => {
                            const row = `
                                <tr>
                                    <td>
                                        <img src="${baseUrl}${item.image}" alt="loot image" /> ${item.name}
                                    </td>
                                    <td>${item.description}</td>
                                    <td class="text-end">${item.market_value}</td>
                                    <td class="text-end">${item.vendor_value}</td>
                                    <td class="text-center">
                                        <span>${item.is_trash === 1 ? '<i class="bi bi-check2"></i>' : ''}</span>
                                    </td>
                                    <td class="text-center">                                   
                                        <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#editItemModal${item.id}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form method="POST" action="{{ route('admin.items.delete', '') }}/${item.id}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn"><i class="bi bi-trash"></i></button>
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
                                                <button type="submit" class="btn"><i class="bi bi-trash"></i></button>
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
                                    <td class="text-end">${spot.suggested_level}</td>
                                    <td class="text-end">${spot.suggested_gearscore}</td>
                                    <td class="text-end">${spot.difficulty}</td>
                                    <td>${spot.mechanics}</td>
                                    <td>                                          
                                        <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#editGrindSpotModal${spot.id}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form method="POST" action="{{ route('admin.grind-spots.delete', '') }}/${spot.id}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn">
                                                <i class="bi bi-trash"></i>
                                            </button>
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