<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\{AuthController, ProjectController, CategoryController, TaskController};

// Public Routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Protected Routes with Sanctum Middleware
Route::middleware(['auth:sanctum'])->group(function () {
    // Auth-related routes
    Route::get('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (\Illuminate\Http\Request $request) {
        return $request->user();
    });

    // Project routes
    Route::controller(ProjectController::class)->group(function () {
        Route::get('/projects', 'index');
        Route::get('/projects/{project}', 'show');
        Route::post('/projects', 'store');
        Route::put('/projects/{project}', 'update');
        Route::delete('/projects/{project}', 'destroy');
    });

    // Category routes
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/categories', 'index');
        Route::get('/categories/{category}', 'show');
        Route::post('/categories', 'store');
        Route::put('/categories/{category}', 'update');
        Route::delete('/categories/{category}', 'destroy');
    });

    // Task routes
    Route::controller(TaskController::class)->group(function () {
        // modified index routes to accept query parameters for searching
        Route::get('/tasks', 'index');
        Route::get('/tasks/{task}', 'show');
        Route::post('/tasks', 'store');
        Route::put('/tasks/{task}', 'update');
        Route::delete('/tasks/{task}', 'destroy');
    });
});
