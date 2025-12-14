<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\WishController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\GiftController;
use App\Http\Controllers\RegisterEventController;
use App\Http\Controllers\UnregisterEventController;
use App\Http\Controllers\ShowMyAssignmentController;
use App\Http\Controllers\ShowMyGiftController;
use App\Http\Controllers\ShowGiftFeedbackController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::get('/dashboard', [WishController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/test', [WishController::class, 'create'])->middleware(['auth', 'verified'])->name('test');

// Public organization join route (može i neprijavljeni da vide)
Route::get('/organizations/{slug}/join', [OrganizationController::class, 'join'])->name('organizations.join');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Wish routes
    Route::post('/wishes', [WishController::class, 'store'])->name('wishes.store');
    Route::patch('/wish', [WishController::class, 'update'])->name('wish.update');
    Route::get('/wishes/history', [WishController::class, 'history'])->name('wishes.history');

    // Comment routes
    Route::post('/comment', [CommentController::class, 'store'])->name('comment.store');

    // Organization routes
    Route::resource('organizations', OrganizationController::class)->names('organizations');
    Route::post('/organizations/{organization}/leave', [OrganizationController::class, 'leave'])->name('organizations.leave');
    Route::post('/organizations/{organization}/members/{userId}/role', [OrganizationController::class, 'updateMemberRole'])->name('organizations.updateMemberRole');
    Route::delete('/organizations/{organization}/members/{userId}', [OrganizationController::class, 'removeMember'])->name('organizations.removeMember');

    // Event routes
    Route::resource('events', EventController::class)->names('events');
    Route::post('/events/{event}/register', RegisterEventController::class)->name('events.register');
    Route::delete('/events/{event}/unregister', UnregisterEventController::class)->name('events.unregister');

    // Assignment routes
    Route::post('/events/{event}/assignments', [AssignmentController::class, 'makeAssignments'])->name('assignments.make');
    Route::get('/events/{event}/my-assignment', ShowMyAssignmentController::class)->name('assignments.my');
    Route::get('/events/{event}/my-gift', ShowMyGiftController::class)->name('assignments.gift');
    Route::get('/events/{event}/gift-feedback', ShowGiftFeedbackController::class)->name('assignments.feedback');

    // Gift routes
    Route::post('/assignments/{assignment}/gift', [GiftController::class, 'store'])->name('gifts.store');
    Route::get('/gifts', [GiftController::class, 'index'])->name('gifts.index');
});

require __DIR__ . '/auth.php';
