<?php

namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\GrindSpotItem;
use App\Models\GrindSpot;

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

        return view('layouts.adminhome');
    }

    // Items
    public function showItemsTable()
    {
        $items = Item::all();
        return response()->json($items);
    }
    //

    // Grind Spot Items
    public function showGrindSpotItemTable()
    {
        $grindItems = GrindSpotItem::all();
        return response()->json($grindItems);
    }
    //

    // Grind Spots
    public function showGrindSpotTable()
    {
        $grindSpot = GrindSpot::all();
        return response()->json($grindSpot);
    }
    //
}
