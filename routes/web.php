<?php

use App\Models\User;
use Inertia\Inertia;
use App\Models\Workout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\ProfileController;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/workouts/create', function () {
    return Inertia::render('Workouts/Create');
})->middleware(['auth', 'verified'])->name('workouts.create');

Route::post('/workouts/create', function () {
    $attributes = Request::validate([
        'name' => ['required', 'string', 'max:255'],
        'file' => ['required', 'file'],
    ]);

    $attributes['user_id'] = Auth::id();
    $attributes['attachment'] = $attributes['file']->store('workouts');
    $attributes['mimetype'] = $attributes['file']->getMimeType();
    $attributes['distance'] = 0;
    $attributes['duration'] = 0;
    $attributes['pace'] = 0;
    $attributes['heart_rate'] = 0;
    $attributes['elevation_gain'] = 0;
    $attributes['date'] = now();
    $attributes['trackpoints_heart_rate'] = [];

    Workout::create($attributes);

    return redirect('workouts/create'); // ->with('success', 'Workout created.');
})->middleware(['auth', 'verified'])->name('workouts.store');

require __DIR__.'/auth.php';
