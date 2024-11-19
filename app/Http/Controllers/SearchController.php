<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\GrindSpot;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $grindSpots = GrindSpot::all();
        $searchFamilyName = $request->input('family_name');
        
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
    }
}
