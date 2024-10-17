<?php

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ListingController;


//Common Resource Routes:
//index - Show all listings
// show - Show single listing
//create - Show form to create new listing
//store - Store new listing
//edit Show form to edit listing
//update - Update listing
// destroy - Delete listing



// All Listings
Route::get('/', [ListingController::class, 'index']);


// create a new Listing

Route::get('/listings/create', [ListingController::class, 'create'])->middleware('auth');

// Store new listing data

Route::post('/listings', [ListingController::class, 'store'])->middleware('auth');

// show edit form http://127.0.0.1:8000/listing/12/edit

Route::get('/listing/{listing}/edit', [ListingController::class, 'edit'])->middleware('auth')->name('listing.edit');

//edit submit to update

Route::put('/listing/{listing}', [ListingController::class, 'update'])->middleware('auth');


// delete listing
Route::delete('/listings/{listing}', [ListingController::class, 'destroy'])->middleware('auth');

//Manage listings
Route::get('/listings/manage', [ListingController::class, 'manage'])->middleware('auth');

// Single Listings
 
Route::get('/listings/{listing}', [ListingController::class, 'show']);


// show register/create form
Route::get('/register', [UserController::class, 'create'])->middleware('guest');

//create new user
Route::post('/users', [UserController::class,'store']);

// Only allow users with 'admin' role
Route::group(['middleware' => ['auth', 'role:admin']], function () {
    Route::get('/admin/dashboard', [UserController::class, 'adminDashboard'])->name('admin.dashboard');
});

// Only allow users with 'editor' role
Route::group(['middleware' => ['auth', 'role:editor']], function () {
    Route::get('/editor/dashboard', [UserController::class, 'editorDashboard'])->name('dashboard.editor');
});

// Only allow users with 'user' role
Route::get('/user/dashboard', [UserController::class, 'index'])
    ->middleware('role:user');


// Log user out
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');

// show login form
Route::get('/login', [UserController::class, 'login'])->name('login')->middleware('guest');

// log in user
Route::post('/users/authenticate', [UserController::class, 'authenticate']);


/*
Route::get('/hello', function () {
    return response('<h1> Hello World </h1>', 200)
    ->header('Content-Type', 'text/plain');
});

Route::get('/search', function (Request $request) {
    return $request->name . ' ' . $request->city;
});

Route::get('/posts/{id}', function ($id) {
    return response('Post'. $id );
});

*/

Auth::routes();


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
