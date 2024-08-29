<?php

namespace App\Services;

use App\Models\Workout;

class TCXProcessor
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function process($id)
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
}
