<div class="comments-section mt-4">
    <h5>Comments</h5>

    @foreach ($comments as $comment)
        <div class="card mb-2">
            <div class="card-body">
                <p>{{ $comment->comment }}</p>
                <small class="text-muted">Posted by {{ $comment->poster->family_name }} on {{ $comment->created_at->format('Y-m-d H:i') }}</small>
            </div>
        </div>
    @endforeach
</div>