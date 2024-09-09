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
        $labels = [];
        $first_timestamp = array_key_first($hr_raw);
        $hr_interval = (int) (count($hr_raw) / 50);
        $label_interval = (int) $hr_interval * 5;

        for ($i = 0; $i < count($hr_raw); $i++) {
            if ($i % $hr_interval == 0) {
                if (array_key_exists($first_timestamp + $i, $hr_raw)) {
                    $hr_optimized[] = $hr_raw[$first_timestamp + $i];
                    $labels[] = '';
                }
            }
            if ($i % $label_interval == 0) {
                if (array_key_exists($first_timestamp + $i, $hr_raw)) {
                    $labels[] = $first_timestamp + $i;
                }
            }
        }

        dd($labels, $hr_optimized);

        $workout->trackpoints_heart_rate = $hr_optimized;
        $workout->type_gpx = $pFFA->enumData('sport', $pFFA->data_mesgs['sport']['sport']);
        $workout->date_gpx = $pFFA->data_mesgs['activity']['timestamp'];

        $workout->save();

        return redirect('workouts');
    }
}
