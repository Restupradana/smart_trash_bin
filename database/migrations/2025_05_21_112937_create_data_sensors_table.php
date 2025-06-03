<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataSensorsTable extends Migration
{
    public function up()
    {
        Schema::create('data_sensors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sensor_id')->constrained('sensors')->onDelete('cascade');
            $table->float('nilai');
            $table->timestamp('waktu')->useCurrent();
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('data_sensors');
    }
}
