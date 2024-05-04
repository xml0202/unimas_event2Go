<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/', [EventController::class, 'home'])->name('home');
Route::get('/search', [EventController::class, 'search'])->name('search');
Route::get('/bookmarked-event', [SiteController::class, 'bookmarkedEvent'])->name('bookmarked-event');
Route::get('/category/{category:category_name}', [EventController::class, 'byCategory'])->name('by-category');
Route::get('/{event:id}', [EventController::class, 'show'])->name('view');

Route::post('/join-event', [EventController::class, 'joinEvent'])->name('joinEvent');
Route::post('/unjoin-event', [EventController::class, 'unjoinEvent'])->name('unjoinEvent');
