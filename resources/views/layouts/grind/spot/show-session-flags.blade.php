@if(auth()->user()->is_admin)
<td>
    @if(in_array($session_id, array_keys($flaggedSessions)))
        @include('layouts.admin.modals.flags.unflag-session-modal')
        <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#unflagSessionModal{{ $session_id}}" data-session-id="{{ $session_id }}">
            <i class="bi bi-flag"></i>
        </button>
    @else
        @include('layouts.admin.modals.flags.flag-session-modal')
        <button class="btn" data-bs-toggle="modal" data-bs-target="#flagSessionModal{{ $session_id }}" data-session-id="{{ $session_id }}">
            <i class="bi bi-flag"></i>
        </button>
    @endif
</td>
@else
<td class="text-center">
    @if(in_array($session_id, array_keys($flaggedSessions)))
        @include('layouts.grind.modals.flagged-reason-modal', ['session_id' => $session_id])
        <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#flagSessionReasonModal{{ $session_id }}" data-session-id="{{ $session_id }}">
            <i class="bi bi-flag"></i>
        </button>
    @else
        <i class="bi bi-flag"></i>
    @endif
</td>
@endif