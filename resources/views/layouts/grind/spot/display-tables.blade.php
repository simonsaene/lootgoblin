
{{-- Display Grind Sessions --}}
<div class="row">
    @if($grindSessions->isEmpty())
        <div class="col-12">
            <p>No grind sessions found for this location.</p>
        </div>
    @else
        @foreach ($grindSessions as $session)
            <div class="col-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <p class="card-text">Notes: {{ $session->notes ?? 'N/A' }}</p>
                        <p class="card-text">Video: {{ $session->video_link ?? 'N/A' }}</p>
                        <p class="card-text">Loot Image: 
                            @if($session->loot_image)
                                <img src="{{ asset($session->loot_image) }}" alt="Loot" style="max-width: 100px;">
                            @else
                                N/A
                            @endif
                        </p>
                        <table class="table">
                            <thead>
                                <tr>
                                    @foreach ($session->grindSessionItems as $item)
                                        <th>{{ $item->grindSpotItem->item->name }}</th>
                                    @endforeach
                                    <th>Total Silver</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @php
                                        $totalSilver = 0;
                                    @endphp

                                    @foreach ($session->grindSessionItems as $item)
                                        @php

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
                                        @endphp
                                        <td>{{ number_format($quantity) }}</td>
                                    @endforeach
                                    <td>{{ number_format($totalSilver) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>


