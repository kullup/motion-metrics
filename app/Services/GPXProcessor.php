<?php

namespace App\Services;

use App\Models\Workout;
use Saloon\XmlWrangler\XmlReader;

class GPXProcessor
{
    public static function process($id)
    {
        $trackpoints = [];

        $workout = Workout::where('id', $id)->first();

        $filePath = storage_path('app/' . $workout->attachment);

        $reader = XmlReader::fromFile($filePath);

        $counter = 0;
        foreach($reader->values()['gpx']['trk']['trkseg']['trkpt'] as $trackpoint) {
            $counter++;
            if ($counter % 25 == 0) {
                $trackpoints[] = $trackpoint['extensions']['gpxtpx:TrackPointExtension']['gpxtpx:hr'];
            }
        }
        
        $workout->trackpoints_heart_rate = $trackpoints;
        $workout->name = $reader->values()['gpx']['trk']['name'];
        $workout->type_gpx = $reader->values()['gpx']['trk']['type'];
        $workout->date_gpx = $reader->values()['gpx']['metadata']['time'];

        $workout->save();

        return redirect('workouts');
    }
}
