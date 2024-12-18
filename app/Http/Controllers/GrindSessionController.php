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

            // Capture filter values from the request
            $validated = request()->validate([
                'hours_filter' => 'nullable|integer|min:0', // Optional, must be a positive integer if provided
                'silver_filter' => 'nullable|numeric|min:0', // Optional, must be a non-negative number if provided
            ]);

            $hoursFilter = $validated['hours_filter'] ?? null;
            $silverFilter = $validated['silver_filter'] ?? null;
            $hasImage = request()->get('has_image');
            $hasVideo = request()->get('has_video');
            
            $grindSessions = GrindSession::where('grind_spot_id', $grindSpot->id)
                ->where('user_id', auth()->id())
                ->with('grindSessionItems.grindSpotItem.item')
                // filter hours
                ->when($hoursFilter, function ($query) use ($hoursFilter) {
                    return $query->where('hours', '>=', $hoursFilter);
                })
                // filter spots with images
                ->when($hasImage != '', function ($query) use ($hasImage) {
                    return $query->when($hasImage == '1', function ($query) {
                        return $query->whereNotNull('loot_image');
                    }, function ($query) {
                        return $query->whereNull('loot_image');
                    });
                })                   
                    // filter spots with video links
                ->when($hasVideo != '', function ($query) use ($hasVideo) {
                    return $query->when($hasVideo == '1', function ($query) {
                        return $query->whereNotNull('video_link');
                    }, function ($query) {
                        return $query->whereNull('video_link');
                    });
                })
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
            'flaggedPosts',
            'silverFilter'
        ));
    }

    public function playerGrindSessions($id)
    {
        try {
            if (!auth()->check()) {
                return redirect()->route('login')->with('error', 'You must be logged in to view grind sessions');
            }
            
            $user = User::findOrFail($id);

            $grindSpots = GrindSpot::with(['grindSpotItems.item'])->get(); // Ensure all items related to grind spots are fetched

            $allGrindSessions = GrindSession::where('user_id', $id)
                ->with('grindSpot', 'grindSessionItems.grindSpotItem.item')
                ->get();

            // Capture filter values from the request
            $validated = request()->validate([
                'hours_filter' => 'nullable|integer|min:0', // Optional, must be a positive integer if provided
                'silver_filter' => 'nullable|numeric|min:0', // Optional, must be a non-negative number if provided
            ]);
            $hoursFilter = $validated['hours_filter'] ?? null;
            $silverFilter = $validated['silver_filter'] ?? null;
            $hasImage = request()->get('has_image');
            $hasVideo = request()->get('has_video');

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

                // Paginate grind sessions for each spot
                $grindSessionsPaginated[$spot->id] = GrindSession::where('user_id', $id)
                    ->where('grind_spot_id', $spot->id)
                    ->with('grindSpot', 'grindSessionItems.grindSpotItem.item')
                    // filter hours
                    ->when($hoursFilter, function ($query) use ($hoursFilter) {
                        return $query->where('hours', '>=', $hoursFilter);
                    })
                    // filter spots with images
                    ->when($hasImage != '', function ($query) use ($hasImage) {
                        return $query->when($hasImage == '1', function ($query) {
                            return $query->whereNotNull('loot_image');
                        }, function ($query) {
                            return $query->whereNull('loot_image');
                        });
                    })                   
                     // filter spots with video links
                    ->when($hasVideo != '', function ($query) use ($hasVideo) {
                        return $query->when($hasVideo == '1', function ($query) {
                            return $query->whereNotNull('video_link');
                        }, function ($query) {
                            return $query->whereNull('video_link');
                        });
                    })
                    ->paginate(20);

                    $spotsWithSessions[] = $spot;
                }

                $like = Like::where('user_id', $user)->where('grind_spot_id', $id);
                $totalLikes = $like->count();

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
            Log::error('Database Query Exception:', [
                'message' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'trace' => $e->getTraceAsString(),
            ]);
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
            'totalLikes',
            'comments',
            'lootData',
            'flaggedSessions',
            'flaggedPosts',
            'silverFilter'
        ));
    }

    public function validateData(Request $request)
    {
        return $request->validate([
            'grind_spot_id' => 'required|integer',
            'loot_image' => 'image|nullable|dimensions:max_width=350,max_height=250',
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

            // Handle Loot Image Deletion
            if ($request->has('delete_loot_image') && $grindSession->loot_image) {
                Log::debug("Deleting old loot image: " . $grindSession->loot_image);
                Storage::disk('public')->delete($grindSession->loot_image);
                $validatedData['loot_image'] = null;
            }

            // Handle uploading a new loot image
            if ($request->hasFile('loot_image')) {
                $path = $request->file('loot_image')->store('loot_images', 'public');
                $validatedData['loot_image'] = $path;
            } else {
                // If no new image is uploaded, retain the old image (if any)
                $validatedData['loot_image'] = $grindSession->loot_image;
            }

            $grindSession->update([
                'grind_spot_id' => $validatedData['grind_spot_id'],
                'loot_image' => $validatedData['loot_image'],
                'video_link' => $validatedData['video_link'] ?? null,
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
    
            if (!auth()->user()->is_admin && $session->user_id != auth()->id()) {
                return redirect()->route('grind.location')->with('error', 'You are not authorized to delete this session.');
            }

            $grindSpotId = $session->grind_spot_id;
            $userId = $session->user_id;

            $session->delete();

            // Redirect to grind.player (if admin) or grind.location (if not admin)
            if (auth()->user()->is_admin) {
                // Admin should be redirected to grind.player with the user_id
                return redirect()->route('grind.player', ['id' => $userId])
                    ->with('status', 'Session deleted successfully!');
            }

            $session->delete();
    
            // Redirect back to the grind spot after deleting the session
            return redirect()->route('grind.location', ['id' => $grindSpotId])
                ->with('status', 'Session deleted successfully!');

        } catch (\Exception $e) {
            // Handle any exceptions that occur during the deletion process
            return redirect()->back()->with('error', 'Error deleting session: ' . $e->getMessage());
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database query error during deleting session:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Database error occurred. Please try again later.');
        }
    }

}
