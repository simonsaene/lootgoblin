<?php

use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes([
    'verify' => true
]);

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('verified');
        
    // Admin routes
    Route::middleware(['admin'])->group(function () {
        Route::get('/adminhome', [AdminController::class, 'index'])->name('adminhome');

        // Tables
        Route::prefix('/tables')->group(function () {
        
            // Items
            Route::prefix('/items')->group(function ()
            {
                Route::get('/', [AdminController::class, 'showItemsTable'])->name('admin.items');
                Route::post('/add', [AdminController::class, 'addItem'])->name('admin.items.add');
                Route::delete('/delete/{id}', [AdminController::class, 'deleteItem'])->name('admin.items.delete');
            });

            // Grind Spot Items
            Route::prefix('/grind-spot-items')->group(function ()
            {
                Route::get('/', [AdminController::class, 'showGrindSpotItemTable'])->name('admin.grinditems');
                Route::delete('/delete/{id}', [AdminController::class, 'deleteGrindSpotItem'])->name('admin.grinditems.delete');
            });

            // Grind Spots
            Route::prefix('/grind-spots')->group(function ()
            {
                Route::get('/', [AdminController::class, 'showGrindSpotTable'])->name('admin.grindspots');
            });

        });
    });

    // Settings routes
    Route::prefix('/settings')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('settings');
    });

    // Grind routes
    Route::prefix('/grind')->group(function () {
        Route::get('/summary', [SettingsController::class, 'index'])->name('summary');
        Route::get('/gyfin-up', [SettingsController::class, 'index'])->name('gyfin.up');
        Route::get('/jade-forest', [SettingsController::class, 'index'])->name('jade.forest');
        Route::get('/d-cres-shrine', [SettingsController::class, 'index'])->name('d.cres');
    });
    
    // Character routes
    Route::prefix('/characters')->group(function () {
        Route::post('/create', [CharacterController::class, 'create'])->name('characters.create');
        Route::put('/edit/{id}', [CharacterController::class, 'edit'])->name('characters.edit');
        Route::delete('/delete/{id}', [CharacterController::class, 'delete'])->name('characters.delete');
    });
});

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
