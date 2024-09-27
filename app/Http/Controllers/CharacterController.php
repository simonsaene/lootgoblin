<?php

namespace App\Http\Controllers;
use App\Models\Character;
use Illuminate\Http\Request;

class CharacterController extends Controller
{
    public function create(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|integer',
            'class' => 'required|string|max:255',
        ]);

        // Create a new character instance and fill it with the validated data
        $character = new Character();
        $character->name = $validatedData['name'];
        $character->level = $validatedData['level'];
        $character->class = $validatedData['class'];
        $character->user_id = auth()->id(); // Assign the authenticated user ID

        // Save the character to the database
        $character->save();

        // Redirect back or to a specific page with a success message
        return redirect()->route('home')->with('status', 'Character added successfully!');
    }

    public function choose_class()
    {
        $classes = ['Warrior', 
                    'Ninja', 
                    'Kunoichi', 
                    'Valkyrie',
                ];

        return view('characters.choose_class', compact('classes'));
    }
}

