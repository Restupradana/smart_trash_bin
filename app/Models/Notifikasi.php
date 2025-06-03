<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'lokasi', 'dikonfirmasi', 'bukti_foto', 'petugas_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function petugas() {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
