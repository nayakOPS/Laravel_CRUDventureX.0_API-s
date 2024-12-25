<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\{AuthController, ProjectController, CategoryController, TaskController};

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/password/email', [AuthController::class, 'sendPasswordResetLink']);

// Protected Routes with Sanctum Middleware
Route::middleware(['auth:sanctum'])->group(function () {
    // Auth-related routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (\Illuminate\Http\Request $request) {
        return $request->user();
    });

    // Project routes
    // Route::controller(ProjectController::class)->group(function () {
    //     Route::get('/projects', 'index');
    //     Route::get('/projects/{project}', 'show');
    //     Route::post('/projects', 'store');
    //     Route::put('/projects/{project}', 'update');
    //     Route::delete('/projects/{project}', 'destroy');
    // });
    Route::apiResource('projects', ProjectController::class);

    // Category routes
    Route::apiResource('categories', CategoryController::class);


    Route::apiResource('tasks', TaskController::class);
});
