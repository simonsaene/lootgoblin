{{-- Add the Like button here --}}
@if($comment->likes()->where('liker_id', auth()->id())->exists())
    <!-- Unlike Form -->
    <form action="{{ route('unlike.post', $comment->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <input type="hidden" name="user_id" value="{{ $comment->user_id }}">
        <input type="hidden" name="post_id" value="{{ $comment->id }}">
        <button type="submit" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-hand-thumbs-up"></i> {{ $comment->likes()->count() }}
        </button>
    </form>
@else
    <!-- Like Form -->
    <form action="{{ route('like.post', $comment->id) }}" method="POST">
        @csrf
        <input type="hidden" name="user_id" value="{{ $comment->user_id }}">
        <input type="hidden" name="post_id" value="{{ $comment->id }}">
        <button type="submit" class="btn btn-outline-success btn-sm">
            <i class="bi bi-hand-thumbs-up"></i> {{ $comment->likes()->count() }}
        </button>
    </form>
@endif
