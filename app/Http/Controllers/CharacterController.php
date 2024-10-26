<?php

namespace App\Http\Controllers;
use App\Models\Character;
use Illuminate\Http\Request;

class CharacterController extends Controller
{

    public function validateData(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|integer',
            'class' => 'required|string|max:255',
        ]);
    }
    public function addChar(Request $request)
    {
        try
        {   
            $validatedData = $this->validateData($request);

            Character::create([
                    'name' => $validatedData['name'],
                    'level' => $validatedData['level'],
                    'class' => $validatedData['class'],
                    'user_id' => auth()->id(),
                ]);

            return redirect()->route('home')->with('status', 'Character added successfully!');
        } catch (\Exception $e) {
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
        
            return redirect()->route('home')->with('status', 'Character updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating character: ' . $e->getMessage());
        }
    }

    public function deleteChar($id)
    {
        try {
            $character = Character::findOrFail($id);
    
            if ($character->user_id != auth()->id()) {
                return redirect()->route('home')->with('error', 'You are not authorized to delete this character.');
            }

            $character->delete();
    
            return redirect()->route('home')->with('status', 'Character deleted successfully!');
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

