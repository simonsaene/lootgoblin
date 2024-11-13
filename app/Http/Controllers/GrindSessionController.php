<?php

namespace App\Http\Controllers;

use App\Models\GrindSpot;
use App\Models\GrindSpotItem;
use App\Models\GrindSession;
use App\Models\GrindSessionItem;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GrindSessionController extends Controller
{
    public function setLocation($data)
    {
        switch ($data)
        {
            case 'name':
                return [
                    'jade-forest' => 'Jade Forest',
                    'gyfin-under' => 'Gyfin Rhasia Temple: Underground',
                    'd-cres-shrine' => 'Dekia: Crescent Shrine',
                ];
                break;
            
            case 'modals':
                return [
                    'jade-forest' => 'jadeForest',
                    'gyfin-under' => 'gyfinUnder',
                    'd-cres-shrine' => 'dekCresShrine',
                ];
                break;

            case 'views':
                return [
                    'gyfin-under' => 'layouts.grind.spot.gyfin-upper.gyfin-upper',
                    'jade-forest' => 'layouts.grind.spot.jade-forest.jade-forest',
                    'd-cres-shrine' => 'layouts.grind.spot.dekia-crescent.dekia-crescent',
                ];
                break;
        }
    }

    public function showLocation($location)
    {

        $names = $this->setLocation('name');

        $modals = $this->setLocation('modals');

        $views = $this->setLocation('views');

        if (array_key_exists($location, $views)) {
            $name = $names[$location];
            $modal = $modals[$location];

            $grindSpots = GrindSpot::all(); 

            $grindSpot = $grindSpots->firstWhere('name', $name);
    
            if ($grindSpot) {
                $grindSpotId = $grindSpot->id;
                $grindSpotItems = GrindSpotItem::where('grind_spot_id', $grindSpotId)
                    ->with('item')
                    ->get();
                $grindSessions = GrindSession::where('grind_spot_id', $grindSpotId)
                    ->with('grindSessionItems.grindSpotItem.item')
                    ->get();
                    
                return view($views[$location], [
                    'location' => $name, 
                    'modal' => $modal,
                    'grindSpotId' => $grindSpotId,
                    'grindSpotItems' => $grindSpotItems,
                    'grindSessions' => $grindSessions,
                ]);
            } 
            else 
            {
                abort(404, 'Grind spot not found.');
            }
        }

        abort(404);
    }

    public function addSession(Request $request)
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

        Log::debug('Validated Data:', $validatedData);
    
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
    
        $names = $this->setLocation('name');

        $grindSpot = GrindSpot::find($validatedData['grind_spot_id']);
        if ($grindSpot) {
            $locationName = array_search($grindSpot->name, $names);
            if ($locationName) {
                return $this->showLocation($locationName);
            }
        }
    }
    
}
