<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function post(Request $request)
    {
        // Validate the request
        $request->validate([
            'grind_spot_id' => 'required|exists:grind_spots,id',
            'user_id' => 'required|exists:users,id',
            'comment' => 'required|string|max:1000',
        ]);

        Post::create([
            'grind_spot_id' => $request->grind_spot_id,
            'user_id' => $request->user_id,
            'poster_id' => auth()->id(),
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Your comment has been posted!');
    }
}
