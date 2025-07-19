<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataSensor;
use Illuminate\Support\Carbon;

class SensorController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'sensor_id' => 'required|integer|exists:sensors,id',
            'nilai' => 'required|numeric',
        ]);

        DataSensor::create([
            'sensor_id' => $request->sensor_id,
            'nilai' => $request->nilai,
            'waktu' => Carbon::now(),
        ]);

        return response()->json(['message' => 'Data berhasil disimpan'], 201);
    }
}
