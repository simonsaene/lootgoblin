<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\GrindSpot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    public function index()
    {
        $grindSpots = GrindSpot::all();
        $users = collect();

        return view('layouts.user.search.search', compact(
            'grindSpots',
            'users'
        ));
    }
    public function search(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'family_name' => 'required|string|max:255', 
            ]);

            $grindSpots = GrindSpot::with(['grindSpotItems.item' => function ($query) {
                $query->where('is_trash', true);
            }])->get();

            $searchFamilyName = $validatedData['family_name'];

            $users = User::where('family_name', 'like', '%' . $searchFamilyName . '%')->get();

            return view('layouts.user.search.search', compact(
                'users', 
                'searchFamilyName', 
                'grindSpots'));

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'There was an error processing your search. Please try again.');
        }
    }

}
