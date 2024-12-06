<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function post(Request $request)
    {
        try
        {
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
        } catch (\Illuminate\Validation\ValidationException $e) {

            Log::error('Validation error while posting comment:', $e->errors());
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Illuminate\Database\QueryException $e) {

            Log::error('Database error while posting comment:', ['error' => $e->getMessage()]);
            return back()->with('error', 'There was a problem with the database. Please try again later.');
    
        } catch (\Exception $e) {

            Log::error('Unexpected error while posting comment:', ['error' => $e->getMessage()]);
            return back()->with('error', 'An unexpected error occurred. Please try again later.');
        }
    }
}
