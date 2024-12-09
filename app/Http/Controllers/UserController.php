<?php

namespace App\Http\Controllers;
use App\Models\Character;
use App\Models\User;
use App\Models\GrindSpot;
use App\Models\Favourite;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        $profile_image = User::where('id', $user_id)->value('profile_image');
        $gear_image = User::where('id', $user_id)->value('gear_image');
        $characters = Character::with(['favourites'])->where('user_id', $user_id)->get();
        $allFavourites = Favourite::with(['grindSpot'])->where('user_id', $user_id)->get()->unique('grind_spot_id');
        $grindSpots = GrindSpot::with(['grindSpotItems.item' => function ($query) {
            $query->where('is_trash', true);
        }])->get();

        $classes = $this->choose_class();
        $char = session('char');

        $likes = Like::where('user_id', $user_id)->get();
        $totalLikes = $likes->count(); 

        return view('layouts.user.home', compact(
            'characters', 
            'classes', 
            'family_name',
            'profile_image',
            'gear_image',
            'char', 
            'allFavourites',
            'grindSpots',
            'totalLikes'
        ));
    }

    public function playerProfile($id)
    {

        $user = User::findOrFail($id);
        
        $family_name = $user->family_name;
        
        $characters = Character::with('favourites')->where('user_id', $id)->get();

        $allFavourites = Favourite::with('grindSpot')->where('user_id', $id)->get()->unique('grind_spot_id');

        $grindSpots = GrindSpot::all();

        $likes = Like::where('user_id', $user)->get();
        $totalLikes = $likes->count(); 

        return view('layouts.user.search.player-profile', compact(
            'user', 
            'family_name', 
            'characters', 
            'allFavourites', 
            'grindSpots',
            'totalLikes'
        ));
    }

    public function addFavourite(Request $request)
    {
        try
        {
            $validatedUpdate = $request->validate([
                'character_id' => 'required',
                'grind_spot_id' => 'required',
            ]);

                // Check if the Favourite already exists for the current user
            $existingFavourite = Favourite::where('user_id', auth()->id())
                ->where('character_id', $validatedUpdate['character_id'])
                ->where('grind_spot_id', $validatedUpdate['grind_spot_id'])
                ->first();

            // If it exists, don't add and return a message
            if ($existingFavourite) {
                return redirect()->route('user.home')->with('status', 'This grind spot is already in your favourites!');
            }

            Favourite::create([
                'character_id' => $validatedUpdate['character_id'],
                'grind_spot_id' => $validatedUpdate['grind_spot_id'],
                'user_id' => auth()->id(),
            ]);

            return redirect()->route('user.home')->with('status', 'Grind spot added to your favourites!');
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'There was an error adding the grind spot to your favourites. Please try again.');
        }
    }

    public function deleteFavourite($id)
    {
        try {
            // Retrieve the favourite for the given character ID and the authenticated user
            $favourite = Favourite::where('id', $id)
                ->where('user_id', auth()->id()) // Make sure it's the current authenticated user
                ->first();
    
            // Check if the favourite exists
            if (!$favourite) {
                return redirect()->route('user.home')->with('error', 'Favourite not found.');
            }
    
            // Delete the favourite
            $favourite->delete();
    
            return redirect()->route('user.home')->with('status', 'Favourite removed successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'There was an error removing the favourite. Please try again.');
        }
    }

    public function validateUpdate(Request $request, $type)
    {
        switch ($type) {
            case 'profile':
                return $request->validate([
                    'profile_image' => 'image|nullable'
                ]);

            case 'gear':
                return $request->validate([
                    'gear_image' => 'image|nullable'
                ]);

            default:
                throw new \InvalidArgumentException('Invalid validation type.');
        }
    }
    
    public function editProfileImage(Request $request) {
        try
        {  
            $user = auth()->user();

            $validatedUpdate = $this->validateUpdate($request, 'profile');

            $update = [
                'profile_image' => $validatedUpdate['profile_image']
            ];

            // Handle profile image if a new one is uploaded
            if ($request->hasFile('profile_image')) {
                // Delete the old image if it exists
                if ($user->profile_image) {
                    Log::debug("Deleting old image: " . $user->profile_image);
                    Storage::disk('public')->delete($user->profile_image);
                }
    
                // Store the new image and add it to the update update
                $update['profile_image'] = $request->file('profile_image')->store('home_profile_images', 'public');
            }

            $user->update($update);

            return redirect()->route('user.home')->with('status', 'Profile image updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating profile image: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating profile image: ' . $e->getMessage());
        }
    }

    public function deleteProfileImage() {
        try {
            // Get the authenticated user
            $user = auth()->user();

            // Check if the user has a profile image
            if ($user->profile_image) {
                // Delete the image from storage
                Log::debug("Deleting profile image: " . $user->profile_image);
                Storage::disk('public')->delete($user->profile_image);

                // Set the profile image field to null in the database
                $user->profile_image = null;
                $user->save();
            } else {
                // If no profile image is found, handle this case
                return redirect()->route('user.home')->with('info', 'No profile image found to delete.');
            }

            return redirect()->route('user.home')->with('status', 'Profile image deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to delete profile image: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete profile image: ' . $e->getMessage());
        }
    }

    public function editGearImage(Request $request) {

        try
        {  
            $user = auth()->user();

            $validatedUpdate = $this->validateUpdate($request, 'gear');

            $update = [
                'gear_image' => $validatedUpdate['gear_image']
            ];

            // Handle profile image if a new one is uploaded
            if ($request->hasFile('gear_image')) {
                // Delete the old image if it exists
                if ($user->gear_image) {
                    Log::debug("Deleting old image: " . $user->gear_image);
                    Storage::disk('public')->delete($user->gear_image);
                }
    
                $newGearImage = $request->file('gear_image')->store('gear_images', 'public');
                $update['gear_image'] = $newGearImage;
            } elseif ($user->gear_image) {
                $update['gear_image'] = $user->gear_image;
            }

            $user->update($update);

            return redirect()->route('user.home')->with('status', 'Gear image updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating gear image: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating gear image: ' . $e->getMessage());
        }
    }

    public function deleteGearImage() {
        try {
            // Get the authenticated user
            $user = auth()->user();

            // Check if the user has a profile image
            if ($user->gear_image) {
                // Delete the image from storage
                Log::debug("Deleting profile image: " . $user->gear_image);
                Storage::disk('public')->delete($user->gear_image);

                // Set the profile image field to null in the database
                $user->gear_image = null;
                $user->save();
            } else {
                // If no profile image is found, handle this case
                return redirect()->route('user.home')->with('info', 'No gear image found to delete.');
            }

            return redirect()->route('user.home')->with('status', 'Gear image deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to delete gear image: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete gear image: ' . $e->getMessage());
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
