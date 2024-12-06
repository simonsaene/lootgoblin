{{-- Display Grind Sessions --}}
<div class="row py-5 bg-body-tertiary">
    @if($grindSessions->isEmpty())
        <div class="col-12">
            <p>No grind sessions found for this location.</p>
        </div>
    @else

        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th class="text-end">Hours</th>
                    <th class="text-end">Silver</th>
                    @foreach ($grindSpotItems as $item)
                        <th class="text-end"><img src="{{ asset('storage/' . $item->item->image) }}" alt="Item Image" style="width: 30px; height: 30px;"></th>
                    @endforeach
                    <th>More</th>
                    <th>Edit/Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($grindSessions as $session)
                    @php
                        $totalSilver = 0;
                    @endphp
                    <tr>
                        <td>{{ $session->created_at->format('Y-m-d') }}</td>
                        <td class="text-end">{{ number_format($session->hours, 2) }}</td>
                        <td class="text-end">
                            @foreach ($session->grindSessionItems as $sessionItem)
                                @php
                                    $marketValue = $sessionItem->grindSpotItem->item->market_value;
                                    $vendorValue = $sessionItem->grindSpotItem->item->vendor_value;
                                    $quantity = $sessionItem->quantity;
        
                                    $valuePerItem = 0;
                                    if ($marketValue === 0) {
                                        $valuePerItem = $vendorValue;
                                    } else {
                                        $valuePerItem = $marketValue;
                                    }
                                    $totalValue = $valuePerItem * $quantity;
                                    $totalSilver += $totalValue;
                                @endphp
                            @endforeach
                            {{ number_format($totalSilver) }}
                        </td>
                        
                        @foreach ($grindSpotItems as $item)
                            @php
                                $sessionItem = $session->grindSessionItems->firstWhere('grind_spot_item_id', $item->id);
                                $quantity = 0;

                                if ($sessionItem) {
                                    $quantity = $sessionItem->quantity;
                                } else {
                                    $quantity = 0;
                                }
                            @endphp
                            <td class="text-end">{{ number_format($quantity) }}</td>
                        @endforeach
                        @include('layouts.grind.modals.more-modal')
                        <td>
                            <button class="btn" data-bs-toggle="modal" data-bs-target="#moreDetailsModal{{ $session->id }}">
                                <i class="bi bi-list"></i>
                            </button>
                        </td>

                        @include('layouts.grind.modals.edit-session-modal')

                        <td>
                            <button class="btn" data-bs-toggle="modal" data-bs-target="#editSessionModal{{ $session->id }}">
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <button class="btn" data-bs-toggle="modal" data-bs-target="#editSessionModal{{ $session->id }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>

                    <tr class="collapse" id="more-details-{{ $session->id }}">
                        <td colspan="{{ count($session->grindSessionItems) + 4 }}">
                            <div>
                                <strong>Video:</strong> {{ $session->video_link ?? 'N/A' }}<br>
                                <strong>Loot Image:</strong> 
                                @if($session->loot_image)
                                    <img src="{{ asset($session->loot_image) }}" alt="Loot Image" style="max-width: 150px;">
                                @else
                                    N/A
                                @endif
                                <br>
                                <strong>Notes:</strong> {{ $session->notes ?? 'N/A' }}
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="col-12">
            <div class="results-info">
                <p>Showing {{ $grindSessions->firstItem() }} to {{ $grindSessions->lastItem() }} of {{ $grindSessions->total() }} results</p>
            </div>
            <div class="pagination">
                {{ $grindSessions->links('pagination::bootstrap-4') }}
            </div>
        </div>
    @endif
</div>

