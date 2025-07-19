<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataSensor extends Model
{
    protected $table = 'data_sensors';
    protected $fillable = ['sensor_id', 'nilai', 'waktu'];

    public function sensors() {
        return $this->belongsTo(Sensor::class);
    }

    
}
