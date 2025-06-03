<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempatSampah extends Model
{
    protected $table = 'tempat_sampah';
    protected $fillable = ['nama', 'jenis', 'lokasi'];

    public function sensors() {
        return $this->hasMany(Sensor::class);
    }
}
