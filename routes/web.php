<?php

use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\VerificationController;

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

Route::get('/test-email', function () {
    $data = [
        'verification_token' => 'fjdlskfjkl',
        'user' => 'simon'
        ];
    Mail::to('support@lootgoblin.lol')->send(new VerifyEmail( $data));
    echo "verification sent";
});


Route::get('/', function () {
    return view('welcome');
});

Route::get('/verify', [VerificationController::class, 'showVerificationNotice'])->name('verification.notice');
Route::post('/email/verify', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('/characters/create', [CharacterController::class, 'create'])->name('characters.create');
    Route::put('/characters/edit/{id}', [CharacterController::class, 'edit'])->name('characters.edit');
    Route::delete('/characters/{id}', [CharacterController::class, 'destroy'])->name('characters.destroy');
});