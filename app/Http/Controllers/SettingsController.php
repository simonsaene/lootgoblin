<?php

namespace App\Http\Controllers;
use App\Models\GrindSpot;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function settings()
    {
        $grindSpots = GrindSpot::all();

        return view('layouts.user.settings', compact(
            'grindSpots'
        ));
    }
    //
}
