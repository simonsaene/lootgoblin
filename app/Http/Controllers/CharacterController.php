<?php

namespace App\Http\Controllers;
use App\Models\Character;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
class CharacterController extends Controller
{
    public function validateUpdate(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|integer',
            'profile_image' => 'image|nullable',
            'class' => 'required|string|max:255'
        ]);
    }
    public function addChar(Request $request)
    {
        try
        {   
            $validatedUpdate = $this->validateUpdate($request);

            if ($request->hasFile('profile_image')) {

                Log::debug("Uploading image...");

                $path = $request->file('profile_image')->store('profile_images', 'public');

                Log::debug("Image stored at: " . $path);

                $validatedUpdate['profile_image'] = $path;
            } else {
                Log::debug("No image uploaded.");
            }

            Character::create([
                    'name' => $validatedUpdate['name'],
                    'level' => $validatedUpdate['level'],
                    'profile_image' => $validatedUpdate['profile_image'] ?? null,
                    'class' => $validatedUpdate['class'],
                    'user_id' => auth()->id(),
                ]);

            return redirect()->route('user.home')->with('status', 'Character added successfully!');
        } catch (\Exception $e) {
            Log::error('Error adding character: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error adding character: ' . $e->getMessage());
        }
    }

    
    public function editChar($id, Request $request)
    {
        try {
            // Find character by ID
            $character = Character::findOrFail($id);
    
            // Validate the incoming update
            $validatedUpdate = $this->validateUpdate($request);
    
            // Prepare the update for updating
            $update = [
                'name' => $validatedUpdate['name'],
                'level' => $validatedUpdate['level'],
                'class' => $validatedUpdate['class'],
            ];
    
            // Handle profile image if a new one is uploaded
            if ($request->hasFile('profile_image')) {
                // Delete the old image if it exists
                if ($character->profile_image) {
                    Log::debug("Deleting old image: " . $character->profile_image);
                    Storage::disk('public')->delete($character->profile_image);
                }
    
                // Store the new image and add it to the update update
                $update['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
            }
    
            // Perform the batch update in one query
            $character->update($update);
    
            // Return success response
            return redirect()->route('user.home')->with('status', 'Character updated successfully!');
        } catch (\Exception $e) {
            // Handle errors and provide feedback
            return redirect()->back()->with('error', 'Error updating character: ' . $e->getMessage());
        }
    }
    

    public function deleteChar($id)
    {
        try {
            $character = Character::findOrFail($id);
    
            if ($character->user_id != auth()->id()) {
                return redirect()->route('user.home')->with('error', 'You are not authorized to delete this character.');
            }

            $character->delete();
    
            return redirect()->route('user.home')->with('status', 'Character deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting character: ' . $e->getMessage());
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

