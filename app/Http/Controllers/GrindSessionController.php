<?php

namespace App\Http\Controllers;

use App\Models\GrindSpot;
use App\Models\GrindSpotItem;
use App\Models\GrindSession;
use App\Models\GrindSessionItem;
use App\Models\User;
use App\Models\Post;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GrindSessionController extends Controller
{
    public function showLocation($id)
    {
        try
        {        
            $grindSpots = GrindSpot::all();

            $grindSpot = GrindSpot::findOrFail($id);

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
                ->paginate(5);

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
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('show.summary')->with('error', 'Grind spot not found.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred. Please try again later.');
        }

        return view('layouts.grind.spot.display-spot', [
            'grindSpots' => $grindSpots,
            'grindSpot' => $grindSpot,
            'grindSpotItems' => $grindSpotItems,
            'grindSessions' => $grindSessions,
            'totalHours' => $totalHours,
            'totalSilver' => $totalSilver,
            'totalSilverPerHour' => $totalSilverPerHour,
            'comments' => $comments,
        ]);
    }

    public function playerGrindSessions($id)
    {
        try
        {
            $user = User::findOrFail($id);

            $grindSpots = GrindSpot::all();

            $allGrindSessions = GrindSession::where('user_id', $id)
                ->with('grindSpot', 'grindSessionItems.grindSpotItem.item')
                ->get();

            $grindSessionsPaginated = [];
            $grindSpotStats = [];
            $comments = [];
            $spotsWithSessions = [];

            foreach ($grindSpots as $spot) {

                $spotGrindSessions = $allGrindSessions->filter(function ($session) use ($spot) {
                    return $session->grind_spot_id === $spot->id;
                });

                if ($spotGrindSessions->isNotEmpty()) {

                    $totalHours = $spotGrindSessions->sum('hours');
                    $totalSilver = $spotGrindSessions->flatMap(function ($session) {
                        return $session->grindSessionItems->map(function ($item) {
                            $marketValue = $item->grindSpotItem->item->market_value;
                            $vendorValue = $item->grindSpotItem->item->vendor_value;
                            if ($marketValue == 0) {
                                $valuePerItem = $vendorValue;
                            } else {
                                $valuePerItem = $marketValue;
                            }
                            return $item->quantity * $valuePerItem;
                        });
                    })->sum();

                    if ($totalHours > 0) {
                        $totalSilverPerHour = $totalSilver / $totalHours;
                    } else {
                        $totalSilverPerHour = 0;
                    }

                    $grindSpotStats[$spot->id] = [
                        'totalHours' => $totalHours,
                        'totalSilver' => $totalSilver,
                        'totalSilverPerHour' => $totalSilverPerHour,
                    ];

                    $grindSessionsPaginated[$spot->id] = GrindSession::where('user_id', $id)
                        ->where('grind_spot_id', $spot->id)
                        ->with('grindSpot', 'grindSessionItems.grindSpotItem.item')
                        ->paginate(5); 

                    $spotsWithSessions[] = $spot;
                }

                    $comments[$spot->id] = Post::where('grind_spot_id', $spot->id)
                        ->where('user_id', $id)
                        ->orderBy('created_at', 'desc')
                        ->get();
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('user.home')->with('error', 'User not found.');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'Database error occurred. Please try again later.');
        } catch (\Exception $e) {
            return back()->with('error', 'An unexpected error occurred. Please try again later.');
        }

        return view('layouts.user.player-grind-sessions', [
            'user' => $user,
            'grindSpots' => $grindSpots,
            'spotsWithSessions' => $spotsWithSessions,
            'grindSessionsPaginated' => $grindSessionsPaginated,
            'grindSpotStats' => $grindSpotStats,
            'comments' => $comments
        ]);
    }

    

    public function addSession(Request $request)
    {
        try 
        {
            Log::debug('Request Data:', $request->all());

            $validatedData = $request->validate([
                'grind_spot_id' => 'required|integer',
                'loot_image' => 'image|nullable',
                'video_link' => 'url|nullable',
                'notes' => 'string|nullable',
                'hours' => 'numeric|min:0',
                'is_video_verified' => 'boolean',
                'is_image_verified' => 'boolean',
                'item_quantities' => 'array',
                'item_quantities.*' => 'integer|min:0',
            ]);
        
            if ($request->hasFile('loot_image')) {
                $validatedData['loot_image'] = $request->file('loot_image')->store('loot_images');
            }
        
            $grindSession = GrindSession::create([
                'user_id' => auth()->id(),
                'grind_spot_id' => $validatedData['grind_spot_id'],
                'loot_image' => $validatedData['loot_image'] ?? null,
                'video_link' => $validatedData['video_link'] ?? null,
                'notes' => $validatedData['notes'] ?? null,
                'hours' => $validatedData['hours'],
                'is_video_verified' => $validatedData['is_video_verified'] ?? false,
                'is_image_verified' => $validatedData['is_image_verified'] ?? false,
            ]);
        
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
                    ->with('success', 'Session added successfully!');
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
    
}
