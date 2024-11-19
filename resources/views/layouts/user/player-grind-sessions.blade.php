@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>{{ $user->name }}'s Grind Sessions</h2>

        <ul class="nav nav-tabs" id="grindSpotTabs" role="tablist">
            @foreach ($grindSpots as $spot)
                <li class="nav-item" role="presentation">
                    <a class="nav-link @if($loop->first) active @endif" id="spot-{{ $spot->id }}-tab" data-bs-toggle="tab" href="#spot-{{ $spot->id }}" role="tab" aria-controls="spot-{{ $spot->id }}" aria-selected="true">
                        {{ $spot->name }}
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="tab-content" id="grindSpotTabsContent">
            @foreach ($grindSpots as $spot)
                <div class="tab-pane fade @if($loop->first) show active @endif" id="spot-{{ $spot->id }}" role="tabpanel" aria-labelledby="spot-{{ $spot->id }}-tab">
                    <div class="row mb-4 mt-3">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    Total Hours
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ number_format($grindSpotStats[$spot->id]['totalHours'], 2) }}</h5>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    Total Silver
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ number_format($grindSpotStats[$spot->id]['totalSilver']) }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    Average Silver/hour
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ number_format($grindSpotStats[$spot->id]['totalSilverPerHour']) }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Hours</th>
                                    <th>Silver Earned</th>
                                    <th>Loot</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($grindSessionsPaginated[$spot->id] as $session)
                                    <tr>
                                        <td>{{ $session->created_at->format('Y-m-d H:i') }}</td>
                                        <td>{{ $session->hours }}</td>
                                        <td>
                                            @php
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

                                            {{ number_format($totalSilver) }}
                                        </td>
                                        <td>
                                            <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#loot-{{ $session->id }}" aria-expanded="false" aria-controls="loot-{{ $session->id }}">
                                                Show Loot
                                            </button>
                                            <div class="collapse" id="loot-{{ $session->id }}">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Items</th>
                                                            <th class="text-end">Qty</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($session->grindSessionItems as $item)
                                                            <tr>
                                                                <td>{{ $item->grindSpotItem->item->name }}</td>
                                                                <td class="text-end">{{ $item->quantity }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="col-12">
                        <div class="results-info mb-3">
                            <p>Showing {{ $grindSessionsPaginated[$spot->id]->firstItem() }} to {{ $grindSessionsPaginated[$spot->id]->lastItem() }} of {{ $grindSessionsPaginated[$spot->id]->total() }} results</p>
                        </div>
                        <div class="pagination">
                            {{ $grindSessionsPaginated[$spot->id]->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection