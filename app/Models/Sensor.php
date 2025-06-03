<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    protected $fillable = ['tipe', 'nama_sensor', 'tempat_sampah_id'];

    public function tempat_sampahs() {
        return $this->belongsTo(TempatSampah::class);
    }

    public function data_sensors() {
        return $this->hasMany(DataSensor::class);
    }
}
