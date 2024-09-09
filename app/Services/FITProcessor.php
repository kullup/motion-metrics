<?php

namespace App\Services;

use DateTime;
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

        $label_interval = 20;



        $timestamps_raw = $pFFA->data_mesgs['record']['timestamp'];
        $timestamps_optimized = [];
        for ($j = 0; $j < count($timestamps_raw); $j += 50) {
            if (array_key_exists($j, $timestamps_raw)) {
                $timestamps_optimized[] = $timestamps_raw[$j];
            }
        }

        $last_known_hr = 0;
        foreach ($timestamps_optimized as $timestamp) {
            if (array_key_exists($timestamp, $hr_raw)) {
                $last_known_hr = $hr_raw[$timestamp];
                $hr_optimized[] = $last_known_hr;
            } else {
                $hr_optimized[] = $last_known_hr;
            }
        }

        for ($i = 1; $i < count($timestamps_optimized); $i++) {
            $timestamp = $timestamps_optimized[$i];
            $date = new DateTime();
            $date->setTimestamp($timestamp);

            $labels[] = $date->format('Y-m-d\TH:i:s.v\Z');
        }

        $workout->trackpoints_heart_rate = $hr_optimized;
        $workout->labels = $labels;
        $workout->type_gpx = $pFFA->enumData('sport', $pFFA->data_mesgs['sport']['sport']);
        $workout->date_gpx = $pFFA->data_mesgs['activity']['timestamp'];

        $workout->save();

        return redirect('workouts');
    }
}
