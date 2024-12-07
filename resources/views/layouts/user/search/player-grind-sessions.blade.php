@extends('layouts.app')

@section('content')
    <div class="container" id="featured-3">
            {{-- Display Session Status or Error Messages --}}
            @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @if(empty($spotsWithSessions))
            <h1 class="border-bottom">{{ $user->name }} has no grind sessions</h1>
        @else
            <h1 class="border-bottom">{{ $user->name }}'s Grind Sessions</h1>
            <ul class="nav nav-tabs mt-5" id="grindSpotTabs" role="tablist">
                @foreach ($spotsWithSessions as $spot)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link @if($loop->first) active @endif" id="spot-{{ $spot->id }}-tab" data-bs-toggle="tab" href="#spot-{{ $spot->id }}" role="tab" aria-controls="spot-{{ $spot->id }}" aria-selected="true">
                            {{ $spot->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="tab-content" id="grindSpotTabsContent">
            @foreach ($grindSpots as $spot)
                {{-- Check if the grind spot stats exist for the current spot --}}
                @if(isset($grindSpotStats[$spot->id]))
                    <div class="tab-pane fade @if($loop->first) show active @endif" id="spot-{{ $spot->id }}" role="tabpanel" aria-labelledby="spot-{{ $spot->id }}-tab">
                        <div class="row mb-4">
                            <div class="row g-4 py-5 row-cols-1 row-cols-lg-3 justify-content-center">
                                <div class="feature col">
                                    <div class="feature-icon d-inline-flex fs-2 mb-3">
                                        <i class="bi bi-alarm"></i>
                                    </div>
                                    <h3 class="fs-2 text-body-emphasis">Total Hours</h3>
                                    <h5 class="card-title">{{ number_format($grindSpotStats[$spot->id]['totalHours'], 2) }}</h5>
                                </div>
                            
                                <div class="feature col">
                                    <div class="feature-icon d-inline-flex fs-2 mb-3">
                                        <i class="bi bi-currency-exchange"></i>
                                    </div>
                                    <h3 class="fs-2 text-body-emphasis">Total Silver</h3>
                                    <h5 class="card-title">{{ number_format($grindSpotStats[$spot->id]['totalSilver']) }}</h5>
                                </div>
                            
                                <div class="feature col">
                                    <div class="feature-icon d-inline-flex fs-2 mb-3">
                                        <i class="bi bi-hourglass-split"></i>
                                    </div>
                                    <h3 class="fs-2 text-body-emphasis">Silver Per Hour</h3>
                                    <h5 class="card-title">{{ number_format($grindSpotStats[$spot->id]['totalSilverPerHour']) }}</h5>
                                </div>
                            </div>
                        </div>
        
                        <div class="row py-5 bg-body-tertiary">
                            <th>@include('layouts.likes.like-grind')</th>
                            <table class="table">
                                <thead>
                                    <tr>
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($grindSessionsPaginated[$spot->id] as $session)
                                        <tr>
                                            <td>{{ $session->created_at->format('Y-m-d') }}</td>
                                            <td class="text-end">{{ $session->hours }}</td>
                                            <td class="text-end">
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

                                            <script>
                                                var lootData = @json($lootData);
                                                console.log(lootData);
                                            </script>

                                            @include('layouts.grind.modals.more-modal')

                                            <td class="text-center">
                                                <button class="btn" data-bs-toggle="modal" data-bs-target="#moreDetailsModal{{ $session->id }}">
                                                    <i class="bi bi-list"></i>
                                                </button>
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
        
                        <div class="comments-section mt-4">
                            <h5>Comments</h5>
                            @if (isset($comments[$spot->id]) && $comments[$spot->id]->isNotEmpty())
                                @foreach ($comments[$spot->id] as $comment)
                                    <div class="card mb-2">
                                        <div class="card-body">
                                            <p>{{ $comment->comment }}</p>
                                            <small class="text-muted">Posted by {{ $comment->poster->family_name }} on {{ $comment->created_at->format('Y-m-d H:i') }}</small>
                                        
                                            @include('layouts.likes.like-posts')
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p>No comments yet for this spot. Be the first to post a comment!</p>
                            @endif
        
                            @auth
                                <form action="{{ route('comments.post', $spot->id) }}" method="POST" class="mt-4">
                                    @csrf
                                    <input type="hidden" name="grind_spot_id" value="{{ $spot->id }}">
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
        
                                    <div class="form-group">
                                        <textarea name="comment" class="form-control" rows="3" placeholder="Add your comment..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-outline-primary mt-2">Post Comment</button>
                                </form>
                            @else
                                <p class="mt-3">You must be logged in to post a comment.</p>
                            @endauth
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
@endsection
