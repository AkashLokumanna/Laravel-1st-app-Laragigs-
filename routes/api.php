<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;


// Public route for user registration
Route::post('/register', [UserController::class, 'register']);

// Route group with Sanctum middleware to protect routes
Route::middleware('auth:sanctum')->group(function () {
    // Get current user details
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Example of a protected route for different roles
    Route::get('/admin/dashboard', [UserController::class, 'adminDashboard'])->middleware('role:admin');
    Route::get('/editor/dashboard', [UserController::class, 'editorDashboard'])->middleware('role:editor');
});
