<?php

namespace App\Http\Controllers;
use App\Models\GrindSpot;
use App\Models\GrindSpotItem;
use App\Models\GrindSession;
use App\Models\GrindSessionItem;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class SummaryController extends Controller
{
    public function showSummary(Request $request)
    {
        $user = auth()->user();
        $grindSessions = GrindSession::where('user_id', $user->id)
            ->with(['grindSessionItems.grindSpotItem.item'])
            ->get();

        // Calculate total hours and silver
        $totalHours = $this->calculateTotalHours($grindSessions);
        $totalSilver = $this->calculateTotalSilver($grindSessions);
        
        // Calculate grind spot hours and silver
        $grindSpotHours = $this->calculateGrindSpotHours($grindSessions);
        $grindSpotSilver = $this->calculateGrindSpotSilver($grindSessions);
        $grindSpotSilverPerHour = $this->calculateGrindSpotSilverPerHour($grindSpotSilver, $grindSpotHours);

        // Get additional data
        $grindSpots = $this->getGrindSpots($grindSessions);
        $grindSpotCount = $this->getGrindSpotCount($grindSessions);
        $spotCount = $grindSpotCount->values();
        $hoursPerSpot = $grindSpotHours->values();
        $silverPerSpot = $grindSpotSilver->values();
        $avgSilverPerSpot = $grindSpotSilverPerHour->values();

        if ($totalHours == 0) {
            $totalSilverPerHour = 0;
        } else {
            $totalSilverPerHour = $totalSilver / $totalHours;
        }

        return view('layouts.grind.summary', compact(
            'grindSpots', 
            'hoursPerSpot', 
            'spotCount', 
            'totalHours',
            'totalSilver',
            'totalSilverPerHour',
            'silverPerSpot',
            'avgSilverPerSpot'
        ));
    }

    // Calculate total hours for all grind sessions
    private function calculateTotalHours($grindSessions)
    {
        return $grindSessions->sum('hours');
    }

    // Calculate total silver for all grind sessions
    private function calculateTotalSilver($grindSessions)
    {
        return $grindSessions->flatMap(function ($grindSession) {
            return $grindSession->grindSessionItems->map(function ($grindSessionItem) {
                $marketValue = $grindSessionItem->grindSpotItem->item->market_value;
                $vendorValue = $grindSessionItem->grindSpotItem->item->vendor_value;
                $valuePerItem = ($marketValue == 0) ? $vendorValue : $marketValue;
                return $grindSessionItem->quantity * $valuePerItem;
            });
        })->sum();
    }

    // Calculate the total hours spent on each grind spot
    private function calculateGrindSpotHours($grindSessions)
    {
        return $grindSessions->groupBy('grind_spot_id')->map(function ($sessions) {
            return $sessions->sum('hours');
        });
    }

    // Calculate the total silver earned at each grind spot
    private function calculateGrindSpotSilver($grindSessions)
    {
        return $grindSessions->groupBy('grind_spot_id')->map(function ($sessions) {
            return $sessions->flatMap(function ($grindSession) {
                return $grindSession->grindSessionItems->map(function ($grindSessionItem) {
                    $marketValue = $grindSessionItem->grindSpotItem->item->market_value;
                    $vendorValue = $grindSessionItem->grindSpotItem->item->vendor_value;
                    $valuePerItem = ($marketValue == 0) ? $vendorValue : $marketValue;
                    return $grindSessionItem->quantity * $valuePerItem;
                });
            })->sum();
        });
    }

    // Calculate the silver per hour for each grind spot
    private function calculateGrindSpotSilverPerHour($grindSpotSilver, $grindSpotHours)
    {
        return $grindSpotSilver->map(function ($totalSilver, $grindSpotId) use ($grindSpotHours) {
            $totalHours = $grindSpotHours->get($grindSpotId, 0);
            return ($totalHours > 0) ? $totalSilver / $totalHours : 0;
        });
    }

    // Get the list of grind spots (unique names)
    private function getGrindSpots($grindSessions)
    {
        return $grindSessions->pluck('grindSpot.name', 'grind_spot_id')->unique()->values();
    }

    // Get the count of sessions for each grind spot
    private function getGrindSpotCount($grindSessions)
    {
        return $grindSessions->groupBy('grind_spot_id')->map(function ($sessions) {
            return $sessions->count();
        });
    }
}
