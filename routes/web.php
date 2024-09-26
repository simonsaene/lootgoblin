<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-db', function () {
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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
