{{-- Add the Like button here --}}
<div class="d-flex justify-content-center mb-2">
    @php
        $likesCount = $spot->likes()->count() - 1;
    @endphp
    @if($spot->likes()->where('liker_id', auth()->id())->exists())
        <!-- Unlike Form -->
        <form action="{{ route('unlike.grind', $spot->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <input type="hidden" name="grind_spot_id" value="{{ $spot->id }}">
            <button type="submit" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-hand-thumbs-up"></i> {{ $likesCount }}
            </button>
        </form>
    @else
        <!-- Like Form -->
        <form action="{{ route('like.grind', $spot->id) }}" method="POST">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id  }}">
            <input type="hidden" name="grind_spot_id" value="{{ $spot->id }}">
            <button type="submit" class="btn btn-outline-success btn-sm">
                <i class="bi bi-hand-thumbs-up"></i> {{ $likesCount }}
            </button>
        </form>
    @endif
</div>