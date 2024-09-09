<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'type_gpx',
        'date_gpx',
        'attachment',
        'mimetype',
        'file_extension',
        'distance',
        'duration',
        'pace',
        'heart_rate',
        'elevation_gain',
        'date',
        'trackpoints_heart_rate',
        'labels',
        'ride_time',
        'total_time',
        'avg_speed',
        'max_speed',
        'avg_hr',
        'max_hr',
    ];

    protected $casts = [
        'name' => 'string',
        'type_gpx' => 'string',
        'date_gpx' => 'date',
        'attachment' => 'string',
        'mimetype' => 'string',
        'file_extension' => 'string',
        'distance' => 'float',
        'duration' => 'integer',
        'pace' => 'float',
        'heart_rate' => 'integer',
        'elevation_gain' => 'float',
        'date' => 'date',
        'trackpoints_heart_rate' => 'array',
        'labels' => 'array',
        'ride_time' => 'integer',
        'total_time' => 'integer',
        'avg_speed' => 'integer',
        'max_speed' => 'integer',
        'avg_hr' => 'integer',
        'max_hr' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
