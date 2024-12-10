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

            Route::prefix('/images')->group(function () {

                Route::prefix('/profile')->group(function () {
                    Route::put('/edit', [UserController::class, 'editProfileImage'])->name('user.edit.profile.image');
                    Route::delete('/delete', [UserController::class, 'deleteProfileImage'])->name('user.delete.profile.image');
                });

                Route::prefix('/gear')->group(function () {
                    Route::put('/edit', [UserController::class, 'editGearImage'])->name('user.edit.gear.image');
                    Route::delete('/delete', [UserController::class, 'deleteGearImage'])->name('user.delete.gear.image');
                });
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
                Route::delete('/delete/{id}', [UserController::class, 'deleteFavourite'])->name('favourite.delete');
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

            // Dashboard
            Route::get('/', [AdminController::class, 'index'])->name('admin.home');

            // Flag
            Route::post('/flag', [AdminController::class, 'flag'])->name('admin.flag');
            Route::put('/unflag/session', [AdminController::class, 'unflagSession'])->name('admin.unflag.session');
            Route::put('/unflag/post', [AdminController::class, 'unflagPost'])->name('admin.unflag.post');

            // Verify video/images
            Route::prefix('/verify')->group(function () {
                Route::prefix('/video')->group(function () {
                    Route::post('/', [AdminController::class, 'verifyVideo'])->name('admin.verify.video');
                    Route::delete('/delete/{id}', [AdminController::class, 'deleteVideo'])->name('admin.delete.video');
                });

                Route::prefix('/image')->group(function () {
                    Route::post('/', [AdminController::class, 'verifyImage'])->name('admin.verify.image');
                    Route::delete('/delete/{id}', [AdminController::class, 'deleteimage'])->name('admin.delete.image');
                });
            });

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

Route::prefix('exceptions')->group(function () {

    // Apply the slow response middleware to this group
    Route::middleware(['db_slow_response_test'])->group(function () {

        // 404 error
        Route::get('/not-found', function () {
            return response()->view('errors.404', [
                'message' => 'The page you are looking for could not be found.'
            ], 404);
        });

        // Simulate a slow server response (503 error)
        Route::get('/slow-response', function () {
            sleep(5); // Simulate a slow server response by delaying for 5 seconds
            return response()->view('errors.503', [
                'message' => 'The server is currently slow. Please try again later.'
            ], 503);
        });

        // Simulate a token mismatch (419 error)
        Route::get('/token-mismatch', function () {
            return response()->view('errors.token-mismatch', [
                'message' => 'Session expired. Please refresh the page and try again.'
            ], 419);
        });

        // Simulate a database error (500 error)
        Route::get('/database-error', function () {
            // Trigger a database query exception
            return response()->view('errors.database', [
                'message' => 'A database error occurred. Please try again later.'
            ], 500);
        });
    });

    // Simulate MethodNotAllowedHttpException (405 error)
    Route::match(['get'], '/method-not-allowed', function () {
        // This would simulate a route that accepts only POST requests
        return response()->view('errors.method-not-allowed', [
            'message' => 'The requested method is not allowed.'
        ], 405);
    });

    // Simulate AuthenticationException (401 error)
    Route::get('/authentication-error', function () {
        return response()->view('errors.401', [
            'message' => 'You are not authorized to access this resource.'
        ], 401);
    });

    // Simulate AccessDeniedHttpException (403 error)
    Route::get('/access-denied', function () {
        return response()->view('errors.403', [
            'message' => 'Access denied. You do not have permission to view this page.'
        ], 403);
    });

    // Simulate ValidationException (redirect back with errors)
    Route::post('/validation-error', function (\Illuminate\Http\Request $request) {
        $rules = [
            'name' => 'required|string',
            'email' => 'required|email',
        ];

        // Perform validation
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        return 'Validation Passed';
    });

    // Simulate Unhandled Exception (500 error)
    Route::get('/unhandled-error', function () {
        // This is an unhandled exception which would normally be caught by Laravel's exception handler
        return response()->view('errors.500', [
            'message' => 'An unexpected error occurred. Please try again later.'
        ], 500);
    });
});

