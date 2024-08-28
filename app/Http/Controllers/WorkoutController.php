<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Workout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkoutController extends Controller
{
    public function processTCX($id)
    {
        $workout = Workout::where('id', $id)->first();

        $tcx = simplexml_load_file(storage_path('app/' . $workout->attachment));

        $trackpoints = [];

        $counter = 0;
        foreach($tcx->Activities->Activity->Lap as $lap) {
            foreach($lap->Track->Trackpoint as $trackpoint) {
                $counter++;
                if ($counter % 25 == 0) {
                    if ($trackpoint->HeartRateBpm) {
                        $trackpoints[] = (int) $trackpoint->HeartRateBpm->Value[0];
                    }
                }
            }
        }

        $workout->trackpoints_heart_rate = $trackpoints;

        $workout->save();

        return redirect('workouts');
    }

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
            'name' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file'],
        ]);
    
        $attributes['user_id'] = Auth::id();
        $attributes['attachment'] = $attributes['file']->store('workouts');
        $attributes['mimetype'] = $attributes['file']->getMimeType();

        $newWorkout = Workout::create($attributes);
        $workoutId = $newWorkout->id;

        $this->processTCX($workoutId);

        return redirect('workouts');
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
