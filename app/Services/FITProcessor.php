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
        $speed_raw = $pFFA->data_mesgs['record']['speed'];
        $timestamps_raw = $pFFA->data_mesgs['record']['timestamp'];

        $timestamps_optimized = [];
        $speed_optimized = [];
        $hr_optimized = [];

        $labels = [];

        for ($j = 0; $j < count($timestamps_raw); $j += 50) {
            if (array_key_exists($j, $timestamps_raw)) {
                $timestamps_optimized[] = $timestamps_raw[$j];
            }
        }

        $last_known_hr = 100;
        for ($k = 1; $k < count($timestamps_optimized); $k++) {
            if (array_key_exists($timestamps_optimized[$k], $hr_raw)) {
                $last_known_hr = $hr_raw[$timestamps_optimized[$k]];
                $hr_optimized[] = $last_known_hr;
            } else {
                $hr_optimized[] = $last_known_hr;
            }
        }

        for ($k = 1; $k < count($timestamps_optimized); $k++) {
            if (array_key_exists($timestamps_optimized[$k], $speed_raw)) {
                $speed_optimized[] = $speed_raw[$timestamps_optimized[$k]];
            } else {
                $speed_optimized[] = 0;
            }
        }

        for ($i = 1; $i < count($timestamps_optimized); $i++) {
            $timestamp = $timestamps_optimized[$i];
            $date = new DateTime();
            $date->setTimestamp($timestamp);

            $labels[] = $date->format('Y-m-d\TH:i:s.v\Z');
        }

        $workout->trackpoints_heart_rate = $hr_optimized;
        $workout->trackpoints_speed = $speed_optimized;
        $workout->avg_speed = $pFFA->data_mesgs['session']['avg_speed'];
        $workout->max_speed = $pFFA->data_mesgs['session']['max_speed'];
        $workout->avg_hr = $pFFA->data_mesgs['session']['avg_heart_rate'];
        $workout->max_hr = $pFFA->data_mesgs['session']['max_heart_rate'];
        $workout->labels = $labels;
        $workout->total_ascent = $pFFA->data_mesgs['session']['total_ascent'];
        $workout->total_descent = $pFFA->data_mesgs['session']['total_descent'];
        $workout->distance = $pFFA->data_mesgs['session']['total_distance'];
        $workout->total_time = $pFFA->data_mesgs['session']['total_elapsed_time'];
        $workout->ride_time = $pFFA->data_mesgs['session']['total_timer_time'];
        $workout->type_gpx = $pFFA->enumData('sport', $pFFA->data_mesgs['sport']['sport']);
        $workout->date_gpx = $timestamps_optimized[0];

        $workout->save();

        return redirect('workouts');
    }
}
