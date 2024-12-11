<table class="table">
    <thead>
        <tr>
            
            @if(auth()->user() || auth()->user()->is_admin)
                <th class="text-center">Flag</th>
            @endif
            <th>Date</th>
            <th class="text-end">Hours</th>
            <th class="text-end">Silver</th>

            {{-- Display loot item columns for the selected spot --}}
            @foreach ($lootData[$spot->id]['lootImages'] as $image)
                <th class="text-end">
                    <img src="{{ asset('storage/' . $image) }}" alt="Loot Image" style="max-width: 150px;">
                </th>
            @endforeach
                    
            <th class="text-center">More</th>

            @if(auth()->user()->is_admin)
            <th class="text-center">Delete</th>
        @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($grindSessionsPaginated[$spot->id] as $session)
            @php
                $session_id = $session->id;
                $user_id = $session->user_id;

                $totalSilver = 0;
                foreach ($session->grindSessionItems as $item) {
                    $marketValue = $item->grindSpotItem->item->market_value;
                    $vendorValue = $item->grindSpotItem->item->vendor_value;
                    $quantity = $item->quantity;

                    if ($marketValue === 0) {
                        $valuePerItem = $vendorValue;
                    } else {
                        $valuePerItem = $marketValue;
                    }

                    $totalValue = $valuePerItem * $quantity;
                    $totalSilver += $totalValue;
                }
            @endphp

            @if ($silverFilter === null || $totalSilver >= $silverFilter)
                <tr>
                    @include('layouts.grind.spot.show-session-flags', ['session_id' => $session_id])

                    <td>{{ $session->created_at->format('Y-m-d') }}</td>
                    <td class="text-end">{{ $session->hours }}</td>
                    <td class="text-end">{{ number_format($totalSilver) }}</td>
                    
                    {{-- Display loot item quantities for each session --}}
                    @foreach ($lootData[$spot->id]['lootItems'] as $lootItem)
                        @php
                            $quantity = 0;
                            foreach ($session->grindSessionItems as $item) {
                                if ($item->grindSpotItem->item->name === $lootItem) {
                                    $quantity = $item->quantity;
                                    break;
                                }
                            }
                        @endphp
                        <td class="text-end">{{ number_format($quantity) }}</td> 
                    @endforeach

                    @include('layouts.grind.modals.more-modal')

                    <td class="text-center">
                        <button class="btn" data-bs-toggle="modal" data-bs-target="#moreDetailsModal{{ $session->id }}">
                            <i class="bi bi-list"></i>
                        </button>
                    </td>

                    @if(auth()->user()->is_admin)
                        <td>
                            <form method="POST" action="{{ route('grind.session.delete', $session->id) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    @endif
                </tr>
            @endif
        @endforeach
    </tbody>
</table>