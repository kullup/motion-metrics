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
        'attachment',
        'mimetype',
        'file_extension',
        'distance',
        'duration',
        'pace',
        'heart_rate',
        'elevation_gain',
        'date',
        'trackpoints_heart_rate'
    ];

    protected $casts = [
        'name' => 'string',
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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
