@extends('layouts.app')

@section('content')
    <div class="container">
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

        {{-- Page Header --}}
        <h1 class="border-bottom">{{ $grindSpot->name }}</h1>

        <div class="row py-5 row-cols-1 row-cols-lg-3">
            <div class=" col d-flex flex-column">
                <div class="d-inline-flex fs-2 mb-3">
                    <i class="bi bi-alarm"></i>
                </div>
                <h3 class="fs-2 text-body-emphasis">Total Hours</h3>
                <h5 class="card-title">{{ number_format($totalHours, 2) }}</h5>
            </div>
        
            <div class=" col d-flex flex-column">
                <div class="d-inline-flex fs-2 mb-3">
                    <i class="bi bi-currency-exchange"></i>
                </div>
                <h3 class="fs-2 text-body-emphasis">Total Silver</h3>
                <h5 class="card-title">{{ number_format($totalSilver) }}</h5>
            </div>
        
            <div class=" col d-flex flex-column">
                <div class="d-inline-flex fs-2 mb-3">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <h3 class="fs-2 text-body-emphasis">Silver Per Hour</h3>
                <h5 class="card-title">{{ number_format($totalSilverPerHour) }}</h5>
            </div>
        </div>

        @include('layouts.grind.modals.add-session-modal')
        <div class="d-flex justify-content-center mb-4">
            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#add{{ $grindSpot->id }}Modal">
                <i class="bi bi-plus-square-fill"></i> Session
            </button>
        </div>
        @include('layouts.grind.modals.filter-session-modal')
        {{-- Display Grind Sessions --}}
        <div class="row py-5 bg-body-tertiary justify-content-center">

            <h3 class="text-center"><i class="bi bi-hand-thumbs-up"></i> {{ $totalLikes }}</h3>

            @if($grindSessions->isEmpty())
                <div class="col-12">
                    <p>No grind sessions found for this location.</p>
                </div>
            @else
                @include('layouts.grind.spot.display-tables')
            @endif
        </div>


        <div class="comments-section mt-4">
            <h5>Comments</h5>
        
            @if($comments->isNotEmpty())
                @foreach ($comments as $comment)
                    @php
                        $post_id = $comment->id;
                        $user_id = $comment->poster_id;
                    @endphp
                    <div class="card mb-2">
                        <div class="card-body">
                            <p>{{ $comment->comment }}</p>
                            <small class="text-muted">Posted by {{ $comment->poster->family_name }} on {{ $comment->created_at->format('Y-m-d H:i') }}</small>

                            <div class="d-flex justify-content-start mt-2">
                                <div>
                                    @include('layouts.likes.like-posts')
                                </div>
                                <div class="ml-2">
                                    @include('layouts.grind.spot.show-post-flags')
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p>No comments yet.</p>
            @endif
        </div>
    </div>
@endsection
