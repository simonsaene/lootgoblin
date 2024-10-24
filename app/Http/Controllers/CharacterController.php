<?php

namespace App\Http\Controllers;
use App\Models\Character;
use Illuminate\Http\Request;

class CharacterController extends Controller
{
    public function create(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|integer',
            'class' => 'required|string|max:255',
        ]);

        $character = new Character();
        $character->name = $validatedData['name'];
        $character->level = $validatedData['level'];
        $character->class = $validatedData['class'];
        $character->user_id = auth()->id(); 

        $character->save();

        return redirect()->route('home')->with('status', 'Character added successfully!');
    }

    
    public function edit($id, Request $request)
    {
        $character = Character::findOrFail($id);
    
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|integer',
            'class' => 'required|string|max:255',
        ]);

        $character->name = $validatedData['name'];
        $character->level = $validatedData['level'];
        $character->class = $validatedData['class'];
    
        $character->save();
    
        return redirect()->route('home')->with('status', 'Character updated successfully!');
    }

    public function delete($id)
    {

        $character = Character::findOrFail($id);

        if ($character->user_id != auth()->id()) {
            return redirect()->route('home')->with('error', 'You are not authorized to delete this character.');
        }

        $character->delete();

        return redirect()->route('home')->with('status', 'Character deleted successfully!');
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

