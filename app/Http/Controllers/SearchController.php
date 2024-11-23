<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\GrindSpot;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        try {

            $validatedData = $request->validate([
                'family_name' => 'required|string|max:255', 
            ]);

            $grindSpots = GrindSpot::all();

            $searchFamilyName = $validatedData['family_name'];
            
            if ($searchFamilyName) {
                $users = User::where('family_name', 'like', '%' . $searchFamilyName . '%')->get();
            } else {
                $users = collect();
            }

            return view('layouts.user.search', compact(
                'users', 
                'searchFamilyName',
                'grindSpots'
            ));
            
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'There was an error processing your search. Please try again.');
        }
    }
}
