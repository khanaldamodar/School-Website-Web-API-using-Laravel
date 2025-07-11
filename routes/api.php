<?php

use App\Http\Controllers\API\EventsController;
use App\Http\Controllers\API\GalleryController;
use App\Http\Controllers\API\NoticesController;
use App\Http\Controllers\API\SchoolInformationController;
use App\Http\Controllers\API\TeachersController;
use App\Http\Controllers\SubjectsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// ? Authentication Routes
Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'login']);
Route::post('/register', [App\Http\Controllers\API\AuthController::class, 'register']);
Route::middleware('auth:sanctum')->post('/logout', [App\Http\Controllers\API\AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/users', [App\Http\Controllers\API\AuthController::class, 'users']);

// Get the authenticated user
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




// ?API Routes for School Information
Route::get('/school-information', [SchoolInformationController::class, 'index']);
Route::get('/school-information/{id}', [SchoolInformationController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/school-information', [SchoolInformationController::class, 'store']);
    Route::put('/school-information/{id}', [SchoolInformationController::class, 'update']);
    Route::delete('/school-information/{id}', [SchoolInformationController::class, 'destroy']);
});


// ? API Routes for Teacher Management
Route::get('/teachers', [TeachersController::class, 'index']);
Route::get('/teachers/{id}', [TeachersController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/teachers', [TeachersController::class, 'store']);
    Route::put('/teachers/{id}', [TeachersController::class, 'update']);
    Route::delete('/teachers/{id}', [TeachersController::class, 'destroy']);
});


// ? API Routes for Subject Management
Route::get('/subjects', [SubjectsController::class, 'index']);
Route::get('/subjects/{id}', [SubjectsController::class, 'show']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/subjects', [SubjectsController::class, 'store']);
    Route::put('/subjects/{id}', [SubjectsController::class, 'update']);
    Route::delete('/subjects/{id}', [SubjectsController::class, 'destroy']);
});



// ? API Routes for Events Management
Route::get('/events', [EventsController::class, 'index']);
Route::get('/events/{id}', [EventsController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/events', [EventsController::class, 'store']);
    Route::put('/events/{id}', [EventsController::class, 'update']);
    Route::delete('/events/{id}', [EventsController::class, 'destroy']);
});

// ? API Routes for Gallery Management
Route::get('/gallery', [GalleryController::class, 'index']);
Route::get('/gallery/{id}', [GalleryController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/gallery', [GalleryController::class, 'store']);
    Route::put('/gallery/{id}', [GalleryController::class, 'update']);
    Route::delete('/gallery/{id}', [GalleryController::class, 'destroy']);
});


// ? API Routes for Notices Management
Route::get('/notices', [NoticesController::class, 'index']);
Route::get('/notices/{id}', [NoticesController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/notices', [NoticesController::class, 'store']);
    Route::put('/notices/{id}', [NoticesController::class, 'update']);
    Route::delete('/notices/{id}', [NoticesController::class, 'destroy']);
});






