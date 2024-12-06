<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use App\Models\GrindSpot;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    // Store the like
    public function likePost(Request $request, Post $post)
    {
        Log::debug('likePost request received', $request->all());
        
        // Validate the incoming request data
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id',
        ]);

        Log::debug('Validated data:', $validated);

        if (!$post) {
            Log::error('Post not found', ['post_id' => $request->post_id]);
            return back()->with('error', 'Post not found.');
        }

        Log::debug('Post found:', ['post' => $post]);

        if ($post->likes()->where('liker_id', auth()->id())->exists()) {
            Log::info('User already liked this post', ['liker_id' => auth()->id(), 'post_id' => $post->id]);
            return back()->with('error', 'You already liked this post.');
        } else {
            Like::create([
                'liker_id' => auth()->id(),
                'user_id' => $validated['user_id'],
                'post_id' => $validated['post_id'],
                'grind_spot_id' => null,
            ]);

            Log::debug('Creating like record', ['liker_id' => auth()->id(), 'user_id' => $validated['user_id'], 'post_id' => $validated['post_id']]);
        }
    
        Log::debug('Like record created successfully', ['liker_id' => auth()->id(), 'user_id' => $validated['user_id'], 'post_id' => $validated['post_id']]);

        return back()->with('status', 'You liked the post!');
    }

    public function unlikePost(Request $request, $id)
    {
        Log::debug('unlikePost request received', $request->all());
    
        $post = Post::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id',
        ]);
    
        Log::debug('Validated data:', $validated);
    
        $like = $post->likes()->where('liker_id', auth()->id())->first();
    
        if ($like) {
            Log::debug('Like found, proceeding to delete', ['like_id' => $like->id, 'liker_id' => auth()->id()]);
    
            $like->delete();
            Log::info('Like deleted successfully', ['like_id' => $like->id, 'liker_id' => auth()->id()]);
    
            return back()->with('status', 'You unliked the post!');
        }
    
        Log::warning('Like not found', ['liker_id' => auth()->id(), 'post_id' => $validated['post_id']]);
        return back()->with('error', 'You have not liked this post.');
    }

    public function likeGrind(Request $request, $id)
    {
        Log::debug('likeGrind request received', $request->all());
        
        // Validate the incoming request data
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'grind_spot_id' => 'required|exists:grind_spots,id',
        ]);

        Log::debug('Validated data:', $validated);

        $grindSpot = GrindSpot::findOrFail($id);

        Log::debug('GrindSpot found:', ['grindSpot' => $grindSpot]);

        if ($grindSpot->likes()->where('liker_id', auth()->id())->exists()) {

            Log::info('User already liked this grind spot', ['liker_id' => auth()->id(), 'grind_spot_id' => $grindSpot->id]);
            return back()->with('error', 'You already liked this grind spot.');
        } else {

            Like::create([
                'liker_id' => auth()->id(),
                'user_id' => $validated['user_id'],
                'grind_spot_id' => $grindSpot->id,
                'post_id' => null,
            ]);

            Log::debug('Creating like record', ['liker_id' => auth()->id(), 'user_id' => $validated['user_id'], 'grind_spot_id' => $grindSpot->id]);
        }

        Log::debug('Like record created successfully', ['liker_id' => auth()->id(), 'user_id' => $validated['user_id'], 'grind_spot_id' => $grindSpot->id]);

        return back()->with('status', 'You liked the grind!');
    }

    public function unlikeGrind(Request $request, $id)
    {
        Log::debug('unlikePost request received', $request->all());
    
        $grindSpot = GrindSpot::findOrFail($id);

        Log::debug('GrindSpot found:', ['grindSpot' => $grindSpot]);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'grind_spot_id' => 'required|exists:grind_spots,id',
        ]);
    
        Log::debug('Validated data:', $validated);
    
        $grind = $grindSpot->likes()->where('liker_id', auth()->id())->first();
    
        if ($grind) {
            Log::debug('Like found, proceeding to delete', ['like_id' => $grind->id, 'liker_id' => auth()->id()]);
    
            $grind->delete();
            Log::info('Like deleted successfully', ['like_id' => $grind->id, 'liker_id' => auth()->id()]);
    
            return back()->with('status', 'You unliked the grind!');
        }
    
        Log::warning('Like not found', ['liker_id' => auth()->id(), 'grind_spot_id' => $validated['grind_spot_id']]);
        return back()->with('error', 'You have not liked this grind.');
    }

}


