<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorController;

Route::post('/simpan-sensor', [SensorController::class, 'store']);
