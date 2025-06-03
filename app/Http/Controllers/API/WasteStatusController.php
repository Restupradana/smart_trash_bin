<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WasteStatusController extends Controller
{
    public function getStatus()
    {
        // Contoh pengambilan data dari tabel sensor (ubah sesuai database kamu)
        $organik = DB::table('sensor')->where('jenis_sensor', 'organik')->orderByDesc('id_sensor')->first();
        $plastik = DB::table('sensor')->where('jenis_sensor', 'plastik')->orderByDesc('id_sensor')->first();
        $metal   = DB::table('sensor')->where('jenis_sensor', 'metal')->orderByDesc('id_sensor')->first();

        return response()->json([
            'organik' => [
                'capacity' => $organik->tingkat_pengisian ?? 0,
                'weight' => null, // karena organik tidak pakai load cell
                'time' => $organik->updated_at ?? null,
            ],
            'plastik' => [
                'capacity' => $plastik->tingkat_pengisian ?? 0,
                'weight' => $plastik->data_sensor ?? 0,
                'time' => $plastik->updated_at ?? null,
            ],
            'metal' => [
                'capacity' => $metal->tingkat_pengisian ?? 0,
                'weight' => $metal->data_sensor ?? 0,
                'time' => $metal->updated_at ?? null,
            ],
        ]);
    }
}
