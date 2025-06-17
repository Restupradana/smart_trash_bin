<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengirim_id',
        'tempat_sampah_id',
        'sensor_id',
        'nilai_kapasitas',
        'nilai_berat',
        'lokasi',
        'pesan',
        'petugas_id',
        'status',
        'dikonfirmasi_pada',
        'bukti_foto',
    ];

    /**
     * Pengguna yang mengirim notifikasi (guru/user).
     */
    public function pengirim()
    {
        return $this->belongsTo(User::class, 'pengirim_id');
    }

    /**
     * Tempat sampah terkait notifikasi.
     */
    public function tempatSampah()
    {
        return $this->belongsTo(TempatSampah::class);
    }

    /**
     * Sensor sumber data notifikasi.
     */
    public function sensor()
    {
        return $this->belongsTo(Sensor::class);
    }

    /**
     * Petugas yang menangani atau mengonfirmasi notifikasi.
     */
    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function latestSensorValue()
    {
        return $this->sensor->data_sensors()->latest()->first()?->nilai ?? '-';
    }
}
