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

        <h1 class="border-bottom">Admin</h1>
        <div class="row mt-5">
            <div class="col-md-6 col-12">
                <h3 class="pb-2 border-bottom"><i class="bi bi-patch-check"></i> Session Data Verification</h3>
                <div class="pb-2">
                    <p><i class="bi bi-camera-video"></i> Videos</p>
                        @foreach($unverifiedSessions as $session)
                            @if($session->video_link && $session->is_video_verified === 0)
                                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#verifyVideoModal{{ $session->id }}">
                                    Session: {{ $session->id }}
                                </button>
                                @include('layouts.admin.modals.verify.verify-video')
                            @endif
                        @endforeach
                </div>
                <div>
                    <p class="mt-3"><i class="bi bi-image"></i> Images</p>
                    @foreach($unverifiedSessions as $session)
                        @if($session->loot_image && $session->is_image_verified === 0)
                            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#verifyImageModal{{ $session->id }}">
                                Session: {{ $session->id }}
                            </button>
                            @include('layouts.admin.modals.verify.verify-image')
                        @endif
                    @endforeach
                </div>
            </div>
        
            <div class="col-md-6 col-12">
                <h3 class="pb-2 border-bottom"><i class="bi bi-flag"></i> Flags</h3>
                <p><i class="bi bi-list-columns"></i> Sessions</p>
                @if(!$allFlaggedSessions->isEmpty())
                        @foreach($allFlaggedSessions as $session)
                            @php
                                $session_id = $session->session_id;
                                $user_id = $session->user_id;
                            @endphp
                            @include('layouts.admin.modals.flags.unflag-session-modal')
                            <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#unflagSessionModal{{ $session_id }}">
                                Session: {{ $session_id }}
                            </button>
                        @endforeach
                @else
                    <p>No flagged sessions.</p>
                @endif

                <p class="mt-3"><i class="bi bi-chat-left-dots"></i> Posts</p>
                @if(!$allFlaggedPosts->isEmpty())
                    @foreach($allFlaggedPosts as $post)
                        @php
                            $post_id = $post->post_id;
                            $user_id = $post->user_id;
                        @endphp
                        @include('layouts.admin.modals.flags.unflag-post-modal')
                        <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#unflagPostModal{{ $post_id }}">
                            Post: {{ $post_id }}
                        </button>
                    @endforeach
                @else
                    <p>No flagged Posts.</p>
                @endif
            </div>
        </div>


        <div class="row mt-5">
            <h3 class="mb-3  border-bottom">
                <i class="bi bi-database-fill-gear"></i> Tables 
            </h3>
            <div class="d-flex justify-content-center">
                <button type="button" class="btn me-2 btn-outline-warning" onclick="fetchData('itemsTable')">
                    <i class="bi bi-chevron-expand"></i> Items
                </button>
                <button type="button" class="btn me-2 btn-outline-warning" onclick="fetchData('grindSpotItemsTable')">
                    <i class="bi bi-chevron-expand"></i> Grind Spot Items
                </button>
                <button type="button" class="btn me-2 btn-outline-warning" onclick="fetchData('grindSpotsTable')">
                    <i class="bi bi-chevron-expand"></i> Grind Spots
                </button>
            </div>
        </div>

        <div class="row mt-2">
            <div class="d-flex justify-content-center">
                <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#helpModal">
                    <i class="bi bi-info-circle"></i> Help
                </button>
            </div>
        </div>

        {{-- Tables --}}
        <div class="row mt-3 bg-body-tertiary">
            {{-- Displays tables --}}
            @include('layouts.admin.tables.items')
            @include('layouts.admin.tables.grind-spot-items')
            @include('layouts.admin.tables.grind-spots')
        </div>
    </div>
    @include('layouts.admin.modals.help-modal')
    {{-- Modals for adding items, items to grind spots and grind spots --}}
    @include('layouts.admin.modals.items.add-item-modal')
    @include('layouts.admin.modals.grind-spot-items.add-grind-spot-item-modal', ['items' => $items, 'grindSpots' => $grindSpots])
    @include('layouts.admin.modals.grind-spots.add-grind-spot-modal')


    {{-- AJAX for showing tables --}}
    @include('layouts.admin.display-tables')

    {{-- Creates modals for all items, needs to be done after javascript has filled the tables with the items --}}
    @foreach ($items as $item)
        @include('layouts.admin.modals.items.edit-item-modal')
    @endforeach

    {{-- Creates modals for all grind spots, needs to be done after javascript has filled the tables with the grind spots --}}
    @foreach ($grindSpots as $spot)
        @include('layouts.admin.modals.grind-spots.edit-grind-spot-modal')
    @endforeach
@endsection