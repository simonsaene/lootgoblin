<?php

namespace App\Http\Controllers;
use App\Models\Character;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class CharacterController extends Controller
{
    public function validateData(Request $request)
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
            $validatedData = $this->validateData($request);


            if ($request->hasFile('profile_image')) {

                Log::debug("Uploading image...");

                $path = $request->file('profile_image')->store('profile_images', 'public');

                Log::debug("Image stored at: " . $path);

                $validatedData['profile_image'] = $path;
            } else {
                Log::debug("No image uploaded.");
            }

            Character::create([
                    'name' => $validatedData['name'],
                    'level' => $validatedData['level'],
                    'profile_image' => $validatedData['profile_image'] ?? null,
                    'class' => $validatedData['class'],
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
            $character = Character::findOrFail($id);

            $validatedData = $this->validateData($request);

            $character->name = $validatedData['name'];
            $character->level = $validatedData['level'];
            $character->class = $validatedData['class'];
        
            $character->save();
        
            return redirect()->route('user.home')->with('status', 'Character updated successfully!');
        } catch (\Exception $e) {
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

