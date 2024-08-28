<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Workout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Workouts/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $attributes = $request->validate([
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
    
        return redirect('workouts/create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Workout $workout)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Workout $workout)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Workout $workout)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Workout $workout)
    {
        //
    }
}
