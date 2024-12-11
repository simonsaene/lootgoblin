@if(auth()->user()->is_admin)
    @if(in_array($post_id, array_keys($flaggedPosts)))
        @include('layouts.admin.modals.flags.unflag-post-modal')
        <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#unflagPostModal{{ $post_id}}" data-session-id="{{ $post_id  }}">
            <i class="bi bi-flag"></i>
        </button>
    @else
        @include('layouts.admin.modals.flags.flag-post-modal')
        <button class="btn" data-bs-toggle="modal" data-bs-target="#flagPostModal{{ $post_id  }}" data-session-id="{{ $post_id  }}">
            <i class="bi bi-flag"></i>
        </button>
    @endif
@else
    @if(in_array($post_id, array_keys($flaggedPosts)))
        @include('layouts.grind.modals.flagged-post-reason-modal', ['post_id' => $post_id])
        <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#flagReasonModal{{ $post_id  }}" data-session-id="{{ $post_id  }}">
            <i class="bi bi-flag"></i>
        </button>
    @else
        <i class="bi bi-flag"></i>
    @endif
@endif