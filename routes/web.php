<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Filament\Pages\EventReportPage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
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

// Route::group(
//     ["middleware" => ["guest"]],
//     function () {
//         Route::get("login", [AuthenticatedSessionController::class, "create"])->name('login');
//         Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
//     }
// );



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/welcome', [SiteController::class, 'index'])->name('welcome');
Route::get("login_unimas", [AuthenticatedSessionController::class, "create_unimas"])->name('login_unimas');
Route::post('login_unimas', [AuthenticatedSessionController::class, 'store_unimas'])->name('login_unimas.store');
Route::get('/home', [EventController::class, 'home'])->name('home');
Route::get('/search', [EventController::class, 'search'])->name('search');
Route::get('/bookmarked-event', [SiteController::class, 'bookmarkedEvent'])->name('bookmarked-event');
Route::get('/category/{category:category_name}', [EventController::class, 'byCategory'])->name('by-category');
Route::get('/{event:id}', [EventController::class, 'show'])->name('view');

Route::post('/join-event', [EventController::class, 'joinEvent'])->name('joinEvent');
Route::post('/unjoin-event', [EventController::class, 'unjoinEvent'])->name('unjoinEvent');
Route::get('/generate-qr/{eventId}', [EventController::class, 'generateQRCode'])->name('generate-qr');


Route::get('/event_feedback/{event_id}/{user_id}', [EventController::class, 'getEventFeedback']);
Route::get('/event_feedback_2/{event_id}/{user_id}', [EventController::class, 'getEventFeedback2']);
Route::post('/event_feedback', [EventController::class, 'feedbackStore'])->name('feedback.save');
Route::post('/event_feedback_2', [EventController::class, 'feedback2Store'])->name('feedback2.save');

Route::get('/admin/events/{event}/report', EventReportPage::class)
    ->name('events.report');  
    
Route::get('/events/{event}/report/pdf', function (Event $event) {
    $pdf = Pdf::loadView('pdf.event-report', compact('event'));

    return $pdf->download("report-{$event->title}.pdf");
})->name('events.report.pdf');

Route::get('auth/redirect', [AuthenticatedSessionController::class, 'redirect'])->name('auth.redirect');
Route::get('/', [AuthenticatedSessionController::class, 'handleRootCallback'])
    ->name('auth.callback');

// Route::get("login", [AuthenticatedSessionController::class, "create"])->name('login');
// Route::post('login', [AuthenticatedSessionController::class, 'store'])->
