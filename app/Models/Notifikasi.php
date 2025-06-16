<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tempat_sampah_id',
        'lokasi',
        'pengirim_id',
        'penerima_id',
        'pesan',
        'dikonfirmasi',
        'bukti_foto',
        'petugas_id',
    ];

    /**
     * User yang menerima notifikasi awal (biasanya guru).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Tempat sampah terkait notifikasi.
     */
    public function tempatSampah()
    {
        return $this->belongsTo(TempatSampah::class);
    }

    /**
     * Guru atau admin yang mengirim notifikasi ke petugas.
     */
    public function pengirim()
    {
        return $this->belongsTo(User::class, 'pengirim_id');
    }

    /**
     * Petugas yang ditugaskan menerima notifikasi.
     */
    public function penerima()
    {
        return $this->belongsTo(User::class, 'penerima_id');
    }

    /**
     * Petugas yang melakukan konfirmasi dan unggah bukti.
     */
    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
