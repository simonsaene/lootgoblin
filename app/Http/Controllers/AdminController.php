<?php

namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\GrindSpotItem;
use App\Models\GrindSpot;
use Illuminate\Http\Request;

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
        $grindSpotItems = GrindSpotItem::all();
        return view('layouts.adminhome', compact('items', 'grindSpotItems'));
    }

    // Items
    public function showItemsTable()
    {
        $items = Item::all();
        return response()->json($items);
    }

    public function addItem(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'market_value' => 'required|integer',
            'vendor_value' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $item = new Item();
            $item->name = $validatedData['name'];
            $item->description = $validatedData['description'];
            $item->market_value = $validatedData['market_value'];
            $item->vendor_value = $validatedData['vendor_value'];
    
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('images', 'public');
                $item->image = $path;
            }
    
            $item->save();

            return redirect()->route('adminhome')->with('success', 'Item added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add item: ' . $e->getMessage());
        }
    }

    public function editItem($id, Request $request)
    {
        $item = Item::findOrFail($id);
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'market_value' => 'required|integer',
            'vendor_value' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max',
        ]);

        try {
            $item->name = $validatedData['name'];
            $item->description = $validatedData['description'];
            $item->market_value = $validatedData['market_value'];
            $item->vendor_value = $validatedData['vendor_value'];
    
            if ($request->hasFile('image')) {

                if ($item->image) {
                    Storage::disk('public')->delete($item->image);
                }

                $path = $request->file('image')->store('images', 'public');
                $item->image = $path;
            }
    
            $item->save();

            return redirect()->route('adminhome')->with('success', 'Item updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to updated item: ' . $e->getMessage());
        }
    }

    public function deleteItem($id)
    {
        $item = Item::findOrFail($id);

        $item->delete();

        return redirect()->route('adminhome')->with('success', 'Item deleted successfully!');
    }
    

    // Grind Spot Items
    public function showGrindSpotItemTable()
    {
        $grindItems = GrindSpotItem::with('item', 'grindSpot')->get();
        return response()->json($grindItems);
    }

    public function deleteGrindSpotItem($id)
    {
        $grindSpotItem = GrindSpotItem::findOrFail($id);

        $grindSpotItem->delete();

        return redirect()->route('adminhome')->with('success', 'Item removed from grind spot successfully!');
    }
    

    // Grind Spots
    public function showGrindSpotTable()
    {
        $grindSpot = GrindSpot::all();
        return response()->json($grindSpot);
    }
    
}
