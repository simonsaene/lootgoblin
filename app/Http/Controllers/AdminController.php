<?php

namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\GrindSpotItem;
use App\Models\GrindSpot;
use App\Models\GrindSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
        /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }
    public function index()
    {
        $user_id = auth()->id();

        $items = Item::all();
        $grindSpotItems = GrindSpotItem::with('item', 'grindSpot')->get();
        $grindSpots = GrindSpot::with(['grindSpotItems.item' => function ($query) {
            $query->where('is_trash', true);
        }])->get();

        $sessions = GrindSession::all();

        // Filter sessions that have unverified video/image
        $unverifiedSessions = $sessions->filter(function($session) {
            return (
                ($session->video_link && $session->is_video_verified === 0) || 
                ($session->loot_image && $session->is_image_verified === 0)
            );
        });

        return view('layouts.admin.admin-home', compact(
            'items', 
            'grindSpotItems' , 
            'grindSpots',
            'unverifiedSessions'
        ));
    }

    /**
     * Validates data, based on the type of data to return
     * 
     * @param \Illuminate\Http\Request $request
     * @param mixed $type
     * @throws \InvalidArgumentException
     * @return mixed
     */
    public function validateData(Request $request, $type)
    {
        switch ($type) {
            case 'grind_spot':
                return $request->validate([
                    'name' => 'required|string|max:255',
                    'location' => 'required|string|max:255',
                    'description' => 'required|string|max:255',
                    'suggested_level' => 'required|integer',
                    'suggested_gearscore' => 'required|integer',
                    'difficulty' => 'required|integer',
                    'mechanics' => 'required|string|max:255',
                ]);

            case 'grind_spot_item':
                return $request->validate([
                    'item_id' => 'required|exists:items,id',
                    'grind_spot_id' => 'required|exists:grind_spots,id',
                ]);

            case 'item':
                return $request->validate([
                    'name' => 'required|string|max:255',
                    'description' => 'required|string|max:255',
                    'market_value' => 'required|integer',
                    'vendor_value' => 'required|integer',
                    'image' => 'image|nullable'
                ]);

            default:
                throw new \InvalidArgumentException('Invalid validation type.');
        }
    }

    // Items
    public function showItemsTable()
    {
        $items = Item::all();
        return response()->json($items);
    }

    public function addItem(Request $request)
    {
        try {

            Log::debug('Incoming request data: ', $request->all());
            $validatedData = $this->validateData($request, 'item');
            Log::debug('Validated data: ', $validatedData);

            $data = [
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'market_value' => $validatedData['market_value'],
                'vendor_value' => $validatedData['vendor_value'],
                'image' => $validatedData['image'] ?? null,
                'is_trash' => $request->has('is_trash') ? 1 : 0
            ];
            

            if ($request->hasFile('image')) {

                Log::debug("Uploading image...");

                $path = $request->file('image')->store('images', 'public');

                Log::debug("Image stored at: " . $path);

                $data['image'] = $path;
            } else {
                Log::debug("No image uploaded.");
            }
    
            Item::create($data);
            Log::debug('Item created successfully.');

            return redirect()->route('admin.home')->with('status', 'Item added successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to add item: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add item: ' . $e->getMessage());
        }
    }

    public function editItem($id, Request $request)
    {
        try {
            Log::debug('Incoming request data: ', $request->all());
            $item = Item::findOrFail($id);

            // Validate the incoming data
            $validatedData = $this->validateData($request, 'item');
            Log::debug('Validated data: ', $validatedData);

            // Prepare the data to be updated
            $update = [
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'market_value' => $validatedData['market_value'],
                'vendor_value' => $validatedData['vendor_value'],
                'is_trash' => $request->has('is_trash') ? 1 : 0,
            ];

            // If there's a new image, handle file upload
            if ($request->hasFile('image')) {

                // Delete the old image if it exists
                if ($item->image) {
                    Storage::disk('public')->delete($item->image);
                }

                // Store the new image and add it to the update data
                $update['image'] = $request->file('image')->store('images', 'public');
            }

            // Perform the batch update
            $item->update($update);

            Log::debug('Item edited successfully.');

            return redirect()->route('admin.home')->with('status', 'Item updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update item: ' . $e->getMessage());
        }
    }

    public function deleteItem($id)
    {
        try {
            $item = Item::findOrFail($id);
            $item->delete();
    
            return redirect()->route('admin.home')->with('success', 'Item deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to Edit item: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete item: ' . $e->getMessage());
        }
    }
    

    // Grind Spot Items
    public function showGrindSpotItemTable()
    {
        $grindItems = GrindSpotItem::with('item', 'grindSpot')->get();
        return response()->json($grindItems);
    }

    public function addGrindSpotItem(Request $request)
    {
        try {
            $validatedData = $this->validateData($request, 'grind_spot_item');
    

            $existingItem = GrindSpotItem::where('item_id', $validatedData['item_id'])
            ->where('grind_spot_id', $validatedData['grind_spot_id'])
            ->first();

            if ($existingItem) {
                return redirect()->back()->with('error', 'Grind spot item already exists for this grind spot!');
            }

            GrindSpotItem::create([
                'item_id' => $validatedData['item_id'],
                'grind_spot_id' => $validatedData['grind_spot_id'],
            ]);
    
            return redirect()->back()->with('success', 'Grind spot item added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add grind spot item: ' . $e->getMessage());
        }
    }

    public function deleteGrindSpotItem($id)
    {
        try {
            $grindSpotItem = GrindSpotItem::findOrFail($id);
            $grindSpotItem->delete();
    
            return redirect()->route('admin.home')->with('success', 'Item removed from grind spot successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to remove item from grind spot: ' . $e->getMessage());
        }
    }
    

    // Grind Spots
    public function showGrindSpotTable()
    {
        $grindSpot = GrindSpot::all();
        return response()->json($grindSpot);
    }

    public function addGrindSpot(Request $request)
    {
        try {
            $validatedData = $this->validateData($request, 'grind_spot');

            GrindSpot::create([
                'name' => $validatedData['name'],
                'location' => $validatedData['location'],
                'description' => $validatedData['description'],
                'suggested_level' => $validatedData['suggested_level'],
                'suggested_gearscore' => $validatedData['suggested_gearscore'],
                'difficulty' => $validatedData['difficulty'],
                'mechanics' => $validatedData['mechanics'],
            ]);

            return redirect()->back()->with('success', 'Grind spot item added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add grind spot: ' . $e->getMessage());
        }
    }

    public function editGrindSpot($id, Request $request)
    {
        try{
            $grindSpot = GrindSpot::findOrFail($id);

            $validatedData = $this->validateData($request, 'grind_spot');

            $grindSpot->name = $validatedData['name'];
            $grindSpot->location=  $validatedData['location'];
            $grindSpot->description = $validatedData['description'];
            $grindSpot->suggested_level = $validatedData['suggested_level'];
            $grindSpot->suggested_gearscore = $validatedData['suggested_gearscore'];
            $grindSpot->difficulty = $validatedData['difficulty'];
            $grindSpot->mechanics = $validatedData['mechanics'];

            $grindSpot->save();

            return redirect()->back()->with('success', 'Grind spot item added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add grind spot: ' . $e->getMessage());
        }
    }

    public function deleteGrindSpot($id)
    {
        $grindSpot = GrindSpot::findOrFail($id);

        $grindSpot->delete();

        return redirect()->route('admin.home')->with('success', 'Item removed from grind spot successfully!');
    }

    public function verifyVideo(Request $request)
    {
        try {
            // Find the session by its ID
            $session = GrindSession::findOrFail($request->session_id);

            // Update the verification status of the video
            $session->is_video_verified = 1;
            $session->save();

            // Redirect back with a success message
            return redirect()->back()->with('status', 'Video successfully verified!');
        } catch (\Exception $e) {
            // Handle error and log it
            Log::error('Error verifying video: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to verify video.');
        }
    }

    public function deleteVideo($id) {
        try {
            // Find the session by its ID
            $session = GrindSession::findOrFail($id);
            
            // Set the video_link to null
            $session->video_link = null;
            
            // Save the session to persist the change
            $session->save();
    
            // Redirect back with a success message
            return redirect()->back()->with('status', 'Video link successfully deleted!');
        } catch (\Exception $e) {
            // Handle error and log it
            Log::error('Error deleting video link: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete video link.');
        }
    }

    public function verifyImage(Request $request)
    {
        try {
            // Find the session by its ID
            $session = GrindSession::findOrFail($request->session_id);

            // Update the verification status of the image
            $session->is_image_verified = 1;
            $session->save();

            // Redirect back with a success message
            return redirect()->back()->with('status', 'Image successfully verified!');
        } catch (\Exception $e) {
            // Handle error and log it
            Log::error('Error verifying image: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to verify image.');
        }
    }

    public function deleteImage($id) {
        try {
            // Find the session by its ID
            $session = GrindSession::findOrFail($id);
            
            // Set the video_link to null
            $session->loot_image = null;
            
            // Save the session to persist the change
            $session->save();
    
            // Redirect back with a success message
            return redirect()->back()->with('status', 'Loot image successfully deleted!');
        } catch (\Exception $e) {
            // Handle error and log it
            Log::error('Error deleting loot image: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete loot image.');
        }
    }
    
}
