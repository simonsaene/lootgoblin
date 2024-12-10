<?php

namespace App\Http\Controllers;

use App\Models\GrindSpot;
use App\Models\GrindSpotItem;
use App\Models\GrindSession;
use App\Models\GrindSessionItem;
use App\Models\Status;
use App\Models\User;
use App\Models\Post;
use App\Models\Like;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GrindSessionController extends Controller
{
    public function showLocation($id)
    {
        try
        {   $user = auth()->id();
            $grindSpots = GrindSpot::with(['grindSpotItems.item' => function ($query) {
                $query->where('is_trash', true);
            }])->get();

            $grindSpot = GrindSpot::findOrFail($id);

            $posts = Post::all();

            $grindSpotItems = GrindSpotItem::where('grind_spot_id', $grindSpot->id)
                ->with('item')
                ->get();

            $allGrindSessions = GrindSession::where('grind_spot_id', $grindSpot->id)
                ->where('user_id', auth()->id())
                ->with('grindSessionItems.grindSpotItem.item')
                ->get();
            
            $grindSessions = GrindSession::where('grind_spot_id', $grindSpot->id)
                ->where('user_id', auth()->id())
                ->with('grindSessionItems.grindSpotItem.item')
                ->paginate(20);

            $totalHours = $allGrindSessions->sum('hours');
            $totalSilver = $allGrindSessions->flatMap(function ($grindSession) {
                return $grindSession->grindSessionItems->map(function ($grindSessionItem) {
                    $marketValue = $grindSessionItem->grindSpotItem->item->market_value;
                    $vendorValue = $grindSessionItem->grindSpotItem->item->vendor_value;

                    if ($marketValue == 0) {
                        $valuePerItem = $vendorValue;
                    } else {
                        $valuePerItem = $marketValue;
                    }
                    return $grindSessionItem->quantity * $valuePerItem;
                });
            })->sum();

            if ($totalHours > 0) {
                $totalSilverPerHour = $totalSilver / $totalHours;
            } else {
                $totalSilverPerHour = 0;
            }

            $comments = Post::where('grind_spot_id', $grindSpot->id)
                ->where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->get();

            $like = Like::where('user_id', $user)->where('grind_spot_id', $id);
            $totalLikes = $like->count();

            $flaggedSessions = Status::where('status_type', 'flagged session')
                ->whereIn('session_id', $allGrindSessions->pluck('id'))
                ->pluck('status_start_reason', 'session_id')
                ->toArray();
            
            $flaggedPosts = Status::where('status_type', 'flagged post')
                ->whereIn('post_id', $posts->pluck('id'))
                ->pluck('status_start_reason', 'post_id')
                ->toArray();

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('show.summary')->with('error', 'Grind spot not found.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred. Please try again later.');
        }

        return view('layouts.grind.spot.display-spot', compact(
            'user',
            'grindSpots',
            'grindSpot',
            'grindSpotItems',
            'grindSessions',
            'totalHours',
            'totalSilver',
            'totalSilverPerHour',
            'comments',
            'totalLikes',
            'flaggedSessions',
            'flaggedPosts'
        ));
    }

    public function playerGrindSessions($id)
    {
        try {
            $user = User::findOrFail($id);

            $grindSpots = GrindSpot::with(['grindSpotItems.item'])->get(); // Ensure all items related to grind spots are fetched

            $allGrindSessions = GrindSession::where('user_id', $id)
                ->with('grindSpot', 'grindSessionItems.grindSpotItem.item')
                ->get();

            $posts = Post::all();

            $grindSessionsPaginated = [];
            $grindSpotStats = [];
            $comments = [];
            $spotsWithSessions = [];
            $lootData = [];

            foreach ($grindSpots as $spot) {
                $lootItems = [];
                $lootImages = [];

                // Gather all loot items for this grind spot
                foreach ($spot->grindSpotItems as $spotItem) {
                    $lootItems[] = $spotItem->item->name;
                    $lootImages[] = $spotItem->item->image;
                }

                $lootData[$spot->id] = [
                    'lootItems' => $lootItems,
                    'lootImages' => $lootImages,
                ];

                $spotGrindSessions = $allGrindSessions->filter(function ($session) use ($spot) {
                    return $session->grind_spot_id === $spot->id;
                });

                if ($spotGrindSessions->isNotEmpty()) {
                    $totalHours = $spotGrindSessions->sum('hours');
                    $totalSilver = $spotGrindSessions->flatMap(function ($session) {
                        return $session->grindSessionItems->map(function ($item) {
                            $marketValue = $item->grindSpotItem->item->market_value;
                            $vendorValue = $item->grindSpotItem->item->vendor_value;
                            $valuePerItem = 0;
                            if ($marketValue === 0) {
                                $valuePerItem = $vendorValue;
                            } else {
                                $valuePerItem = $marketValue;
                            }
                            return $item->quantity * $valuePerItem;
                        });
                    })->sum();

                    $totalSilverPerHour = ($totalHours > 0) ? $totalSilver / $totalHours : 0;

                    $grindSpotStats[$spot->id] = [
                        'totalHours' => $totalHours,
                        'totalSilver' => $totalSilver,
                        'totalSilverPerHour' => $totalSilverPerHour,
                    ];

                    $grindSessionsPaginated[$spot->id] = GrindSession::where('user_id', $id)
                        ->where('grind_spot_id', $spot->id)
                        ->with('grindSpot', 'grindSessionItems.grindSpotItem.item')
                        ->paginate(20);

                    $spotsWithSessions[] = $spot;
                }

                // Comments for the spot
                $comments[$spot->id] = Post::where('grind_spot_id', $spot->id)
                    ->where('user_id', $id)
                    ->orderBy('created_at', 'desc')
                    ->get();

                $flaggedSessions = Status::where('status_type', 'flagged session')
                    ->whereIn('session_id', $allGrindSessions->pluck('id'))
                    ->pluck('status_start_reason', 'session_id')
                    ->toArray();

                $flaggedPosts = Status::where('status_type', 'flagged post')
                    ->whereIn('post_id', $posts->pluck('id'))
                    ->pluck('status_start_reason', 'post_id')
                    ->toArray();
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('user.home')->with('error', 'User not found.');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'Database error occurred. Please try again later.');
        } catch (\Exception $e) {
            return back()->with('error', 'An unexpected error occurred. Please try again later.');
        }

        return view('layouts.user.search.player-grind-sessions', compact(
            'user',
            'grindSpots',
            'spotsWithSessions',
            'grindSessionsPaginated',
            'grindSpotStats',
            'allGrindSessions',
            'comments',
            'lootData',
            'flaggedSessions',
            'flaggedPosts'
        ));
    }

    public function validateData(Request $request)
    {
        return $request->validate([
            'grind_spot_id' => 'required|integer',
            'loot_image' => 'image|nullable',
            'video_link' => 'url|nullable',
            'notes' => 'string|nullable',
            'hours' => 'nullable|numeric|min:0',
            'is_video_verified' => 'boolean',
            'is_image_verified' => 'boolean',
            'item_quantities' => 'array',
            'item_quantities.*' => 'required|integer|min:0',
        ]);
    }

    public function addSession(Request $request)
    {
        try 
        {
            Log::debug('Request Data:', $request->all());

            $validatedData = $this->validateData($request);

            $validatedData['hours'] = floatval($validatedData['hours']);

            if ($request->hasFile('loot_image')) {
                $path = $request->file('loot_image')->store('loot_images', 'public');
                $validatedData['loot_image'] = $path;
            }

            $grindSession = GrindSession::create([
                'user_id' => auth()->id(),
                'grind_spot_id' => $validatedData['grind_spot_id'],
                'loot_image' => $validatedData['loot_image'] ?? null,
                'video_link' => $validatedData['video_link'] ?? null,
                'notes' => $validatedData['notes'] ?? null,
                'hours' => $validatedData['hours'] ?? null,
                'is_video_verified' => $validatedData['is_video_verified'] ?? false,
                'is_image_verified' => $validatedData['is_image_verified'] ?? false,
            ]);

            Log::debug('Image Upload Debug', [
                'has_file' => $request->hasFile('loot_image'),
                'file_exists' => $request->file('loot_image') ? true : false,
                'file_path' => $request->hasFile('loot_image') ? $request->file('loot_image')->getPathname() : 'No file',
            ]);
            Log::debug('Validated Data:', $validatedData);

            Log::debug('GrindSession Created:', $grindSession->toArray());
        
            foreach ($validatedData['item_quantities'] as $itemId => $quantity) {
                if ($quantity > 0) {
                    GrindSessionItem::create([
                        'grind_session_id' => $grindSession->id,
                        'grind_spot_item_id' => $itemId,
                        'quantity' => $quantity,
                    ]);
                    Log::debug('GrindSessionItem Created for Item ID ' . $itemId . ' with Quantity:', ['quantity' => $quantity]);
                }
            }
    
            $grindSpot = GrindSpot::find($validatedData['grind_spot_id']);

            if ($grindSpot) {
                return redirect()->route('grind.location', ['id' => $grindSpot->id])
                    ->with('status', 'Session added successfully!');
            }
            return redirect()->back()->with('error', 'Grind spot not found.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            
            Log::error('Validation error:', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();

        } catch (\Illuminate\Database\QueryException $e) {

            Log::error('Database query error:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Database error occurred. Please try again later.');

        } catch (\Exception $e) {

            Log::error('Unexpected error:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'An unexpected error occurred. Please try again later.');
        }
    }

    public function editSession(Request $request, $id)
    {
        try 
        {
            Log::debug('Request Data for Edit Session:', $request->all());

            $grindSession = GrindSession::findOrFail($id);

            $validatedData = $this->validateData($request);

            $validatedData['hours'] = floatval($validatedData['hours']);

            if ($request->hasFile('loot_image')) {

                if ($grindSession->loot_image) {
                    Log::debug("Deleting old loot image: " . $grindSession->loot_image);
                    Storage::disk('public')->delete($grindSession->loot_image);
                }

                $path = $request->file('loot_image')->store('loot_images', 'public');
                $validatedData['loot_image'] = $path;
            } else {

                $validatedData['loot_image'] = $grindSession->loot_image;
            }

            $grindSession->update([
                'grind_spot_id' => $validatedData['grind_spot_id'],
                'loot_image' => $validatedData['loot_image'] ?? $grindSession->loot_image,
                'video_link' => $validatedData['video_link'] ?? $grindSession->video_link,
                'notes' => $validatedData['notes'] ?? $grindSession->notes,
                'hours' => $validatedData['hours'] ?? $grindSession->hours,
                'is_video_verified' => $validatedData['is_video_verified'] ?? $grindSession->is_video_verified,
                'is_image_verified' => $validatedData['is_image_verified'] ?? $grindSession->is_image_verified,
            ]);

            $grindSession->grindSessionItems()->delete();

            foreach ($validatedData['item_quantities'] as $itemId => $quantity) {
                if ($quantity > 0) {
                    GrindSessionItem::create([
                        'grind_session_id' => $grindSession->id,
                        'grind_spot_item_id' => $itemId,
                        'quantity' => $quantity,
                    ]);
                }
            }

            $grindSpot = GrindSpot::find($validatedData['grind_spot_id']);

            if ($grindSpot) {
                return redirect()->route('grind.location', ['id' => $grindSpot->id])
                    ->with('status', 'Session updated successfully!');
            }

            return redirect()->back()->with('error', 'Grind spot not found.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error during edit session:', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database query error during edit session:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Database error occurred. Please try again later.');

        } catch (\Exception $e) {
            Log::error('Unexpected error during edit session:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'An unexpected error occurred. Please try again later.');
        }
    }

    public function deleteSession($id)
    {
        try {
            $session = GrindSession::findOrFail($id);
    
            if ($session->user_id != auth()->id()) {
                return redirect()->route('grind.location')->with('error', 'You are not authorized to delete this session.');
            }

            $grindSpotId = $session->grind_spot_id;

            $session->delete();
    
            // Redirect back to the grind spot after deleting the session
            return redirect()->route('grind.location', ['id' => $grindSpotId])
                ->with('status', 'Session deleted successfully!');

        } catch (\Exception $e) {
            // Handle any exceptions that occur during the deletion process
            return redirect()->back()->with('error', 'Error deleting session: ' . $e->getMessage());
        }
    }

}
