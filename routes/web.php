<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
Route::post('/characters/create', [CharacterController::class, 'create'])->name('characters.create');
Route::put('/characters/edit/{id}', [CharacterController::class, 'edit'])->name('characters.edit');
Route::delete('/characters/{id}', [CharacterController::class, 'destroy'])->name('characters.destroy');

Route::get('/test-users', function () {
    try {
        // Attempt to fetch all users from the database
        $users = DB::table('users')->get();

        // Check if users were retrieved
        if ($users->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Connected to the database successfully!',
                'data' => $users,
            ]);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Connected to the database, but no users found.',
                'data' => [],
            ]);
        }
    } catch (\Exception $e) {
        // Return error message if database connection fails
        return response()->json([
            'success' => false,
            'error' => 'Could not connect to the database: ' . $e->getMessage(),
        ], 500);
    }
});

Route::get('/test-spots', function () {
    try {
        // Attempt to fetch all users from the database
        $spots = DB::table('grind_spots')->get();

        // Check if users were retrieved
        if ($spots->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Connected to the database successfully!',
                'data' => $spots,
            ]);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Connected to the database, but no users found.',
                'data' => [],
            ]);
        }
    } catch (\Exception $e) {
        // Return error message if database connection fails
        return response()->json([
            'success' => false,
            'error' => 'Could not connect to the database: ' . $e->getMessage(),
        ], 500);
    }
});
