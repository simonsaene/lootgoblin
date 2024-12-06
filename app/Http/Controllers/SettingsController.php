<?php

namespace App\Http\Controllers;
use App\Models\GrindSpot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    public function settings()
    {
        $grindSpots = GrindSpot::with(['grindSpotItems.item' => function ($query) {
            $query->where('is_trash', true);
        }])->get();

        return view('layouts.user.settings', compact(
            'grindSpots'
        ));
    }
    //
}
