<?php

namespace App\Services;

use App\Models\Workout;
use adriangibbons\phpFITFileAnalysis;

class FITProcessor
{
    public static function process($id)
    {
        $workout = Workout::where('id', $id)->first();
        $filePath = storage_path('app/' . $workout->attachment);
        $pFFA = new phpFITFileAnalysis($filePath, ['units' => 'metric']);

       // dd($pFFA->data_mesgs);

        $hr_raw = $pFFA->data_mesgs['record']['heart_rate'];
        $hr_optimized = [];

        for ($i=array_key_first($hr_raw); $i <  array_key_last($hr_raw); $i += 50) { 
            if(array_key_exists($i, $hr_raw)) {
                $hr_optimized[] = $hr_raw[$i];
            }
        }

        $workout->trackpoints_heart_rate = $hr_optimized;
        $workout->name = '';// $reader->values()['gpx']['trk']['name'];
        $workout->type_gpx = ''; // $reader->values()['gpx']['trk']['type'];
        $workout->date_gpx = '';// $reader->values()['gpx']['metadata']['time'];

        $workout->save();

        return redirect('workouts');
    }
}
