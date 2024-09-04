<?php

namespace App\Services;

use App\Models\Workout;
use Saloon\XmlWrangler\XmlReader;

class GPXProcessor
{
    public static function process($id)
    {
        $trackpoints_raw = [];
        $trackpoints_optimized = [];
        $average_heart_rate = 0;

        $workout = Workout::where('id', $id)->first();

        $filePath = storage_path('app/' . $workout->attachment);

        $reader = XmlReader::fromFile($filePath);

        $trackpoints_raw = $reader->values()['gpx']['trk']['trkseg']['trkpt'];

        dd($trackpoints_raw);

        for ($i = 0; $i < count($trackpoints_raw); $i += 25) {
            $trackpoint = $trackpoints_raw[$i];
            dd($trackpoint);
            $average_heart_rate += $trackpoint['extensions']['gpxtpx:TrackPointExtension']['gpxtpx:hr'];
            $trackpoints_optimized[] = $trackpoint['extensions']['gpxtpx:TrackPointExtension']['gpxtpx:hr'];
        }
        
        $workout->trackpoints_heart_rate = $trackpoints_optimized;
        $workout->name = $reader->values()['gpx']['trk']['name'];
        $workout->type_gpx = $reader->values()['gpx']['trk']['type'];
        $workout->date_gpx = $reader->values()['gpx']['metadata']['time'];

        $workout->save();

        return redirect('workouts');
    }
}
