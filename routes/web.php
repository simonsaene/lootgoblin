<?php

use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GrindSessionController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\LikeController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes([
    'verify' => true
]);

Route::middleware(['auth'])->group(function () {

    Route::middleware(['verified'])->group(function () {

        // User
        Route::prefix('/home')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('user.home');
            Route::get('/profile/{id}', [UserController::class, 'playerProfile'])->name('user.player.profile');
            Route::post('/post', [PostController::class, 'post'])->name('comments.post');
            Route::post('/like/{id}', [LikeController::class, 'likePost'])->name('like.post');
            Route::delete('/unlike/{id}', [LikeController::class, 'unlikePost'])->name('unlike.post');

            Route::prefix('/search')->group(function () {
                Route::get('/', [SearchController::class, 'index'])->name('user.search.page');
                Route::get('/player', [SearchController::class, 'search'])->name('user.search.player');
            });

            // Settings routes
            Route::prefix('/settings')->group(function () {
                Route::get('/', [SettingsController::class, 'settings'])->name('user.settings');
            });

            // Character routes
            Route::prefix('/characters')->group(function () {
                Route::post('/create', [CharacterController::class, 'addChar'])->name('characters.create');
                Route::put('/edit/{id}', [CharacterController::class, 'editChar'])->name('characters.edit');
                Route::delete('/delete/{id}', [CharacterController::class, 'deleteChar'])->name('characters.delete');
            });

            // Favourites routes
            Route::prefix('/favourite')->group(function () {
                Route::post('/add', [UserController::class, 'addFavourite'])->name('favourite.add');
            });

        });

        // Grind routes
        Route::prefix('/grind')->group(function () {
            Route::get('/summary', [SummaryController::class, 'showSummary'])->name('show.summary');
            Route::get('/{id}', [GrindSessionController::class, 'showLocation'])->name('grind.location');
            Route::get('/player-grind/{id}', [GrindSessionController::class, 'playerGrindSessions'])->name('grind.player');
            Route::post('/like/{id}', [LikeController::class, 'likeGrind'])->name('like.grind');
            Route::delete('/unlike/{id}', [LikeController::class, 'unlikeGrind'])->name('unlike.grind');

            Route::prefix('/session')->group(function () 
            {
                Route::post('/add', [GrindSessionController::class, 'addSession'])->name('grind.session.add');
                Route::put('/edit/{id}', [GrindSessionController::class, 'editSession'])->name('grind.session.edit');
                Route::delete('/delete/{id}', [GrindSessionController::class, 'deleteSession'])->name('grind.session.delete');
            });

        });

        // Admin routes
        Route::middleware(['admin'])->group(function () {
            Route::get('/', [AdminController::class, 'index'])->name('admin.home');

            // Tables
            Route::prefix('/tables')->group(function () {
            
                // Items
                Route::prefix('/items')->group(function ()
                {
                    Route::get('/', [AdminController::class, 'showItemsTable'])->name('admin.items');
                    Route::post('/add', [AdminController::class, 'addItem'])->name('admin.items.add');
                    Route::put('/edit/{id}', [AdminController::class, 'editItem'])->name('admin.items.edit');
                    Route::delete('/delete/{id}', [AdminController::class, 'deleteItem'])->name('admin.items.delete');
                });

                // Grind Spot Items
                Route::prefix('/grind-spot-items')->group(function ()
                {
                    Route::get('/', [AdminController::class, 'showGrindSpotItemTable'])->name('admin.grind-items');
                    Route::post('/add', [AdminController::class, 'addGrindSpotItem'])->name('admin.grind-items.add');
                    Route::delete('/delete/{id}', [AdminController::class, 'deleteGrindSpotItem'])->name('admin.grind-items.delete');
                });

                // Grind Spots
                Route::prefix('/grind-spots')->group(function ()
                {
                    Route::get('/', [AdminController::class, 'showGrindSpotTable'])->name('admin.grind-spots');
                    Route::post('/add', [AdminController::class, 'addGrindSpot'])->name('admin.grind-spots.add');
                    Route::put('/edit/{id}', [AdminController::class, 'editGrindSpot'])->name('admin.grind-spots.edit');
                    Route::delete('/delete/{id}', [AdminController::class, 'deleteGrindSpot'])->name('admin.grind-spots.delete');
                });
            });
        });
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
