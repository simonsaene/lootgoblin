<?php

namespace App\Http\Controllers;
use App\Models\Character;
use App\Models\User;
use App\Models\GrindSpot;
use App\Models\Favourite;
use Illuminate\Http\Request;


class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user_id = auth()->id();

        $family_name = User::where('id', $user_id)->value('family_name');
        $characters = Character::with(['favourites'])->where('user_id', $user_id)->get();
        $allFavourites = Favourite::with(['grindSpot'])->where('user_id', $user_id)->get()->unique('grind_spot_id');
        $grindSpots = GrindSpot::with(['grindSpotItems.item' => function ($query) {
            $query->where('is_trash', true);
        }])->get();

        $classes = $this->choose_class();
        $char = session('char');

        return view('layouts.user.home', compact(
            'characters', 
            'classes', 
            'family_name', 
            'char', 
            'allFavourites',
            'grindSpots'
        ));
    }

    public function playerProfile($id)
    {

        $user = User::findOrFail($id);
        
        $family_name = $user->family_name;
        
        $characters = Character::with('favourites')->where('user_id', $id)->get();

        $allFavourites = Favourite::with('grindSpot')->where('user_id', $id)->get()->unique('grind_spot_id');

        $grindSpots = GrindSpot::all();

        return view('layouts.user.player-profile', compact(
            'user', 
            'family_name', 
            'characters', 
            'allFavourites', 
            'grindSpots'));
    }

    public function addFavourite(Request $request)
    {
        try
        {
            $validatedData = $request->validate([
                'character_id' => 'required',
                'grind_spot_id' => 'required',
            ]);

                // Check if the Favourite already exists for the current user
            $existingFavourite = Favourite::where('user_id', auth()->id())
                ->where('character_id', $validatedData['character_id'])
                ->where('grind_spot_id', $validatedData['grind_spot_id'])
                ->first();

            // If it exists, don't add and return a message
            if ($existingFavourite) {
                return redirect()->route('user.home')->with('status', 'This grind spot is already in your favourites!');
            }

            Favourite::create([
                'character_id' => $validatedData['character_id'],
                'grind_spot_id' => $validatedData['grind_spot_id'],
                'user_id' => auth()->id(),
            ]);

            return redirect()->route('user.home')->with('status', 'Grind spot added to your favourites!');
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'There was an error adding the grind spot to your favourites. Please try again.');
        }
    }
    
    
    public function choose_class()
    {
        $classes = [
            'Warrior',
            'Ranger',
            'Sorceress',
            'Berserker',
            'Tamer',
            'Musa',
            'Maehwa',
            'Valkyrie',
            'Kunoichi',
            'Ninja',
            'Witch',
            'Wizard',
            'Dark Knight',
            'Striker',
            'Mystic',
            'Lahn',
            'Archer',
            'Shai',
            'Guardian',
            'Hashashin',
            'Nova',
            'Sage',
            'Corsair',
            'Drakania',
            'Woosa',
            'Maegu'
        ];

        return $classes;
    }
}
