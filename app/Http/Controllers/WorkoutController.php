<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Workout;
use Illuminate\Http\Request;
use App\Services\GPXProcessor;
use App\Services\TCXProcessor;
use App\Services\FITProcessor;
use Illuminate\Support\Facades\Auth;

class WorkoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Workouts/Index', [
            'workouts' => Workout::where('user_id', Auth::id())->get()
        ]);
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
            // 'name' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file'],
        ]);
    
        $attributes['name'] = $request->file('file')->getClientOriginalName();
        $attributes['user_id'] = Auth::id();
        $attributes['attachment'] = $attributes['file']->store('workouts');
        $attributes['mimetype'] = $attributes['file']->getMimeType();
        $attributes['file_extension'] = $request->file('file')->getClientOriginalExtension();

        $newWorkout = Workout::create($attributes);
        $workoutId = $newWorkout->id;

        if ($attributes['file_extension'] == 'fit') {
            return FITProcessor::process($workoutId);
        } else if ($attributes['file_extension'] == 'gpx') {
            return GPXProcessor::process($workoutId);
        } else if ($attributes['file_extension'] == 'tcx') {
            return TCXProcessor::process($workoutId);
        } else {
            return redirect('workouts');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $workout = Workout::where('id', $id)->first();

        return Inertia::render('Workouts/Show', [
            'workout' => $workout
        ]);
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
