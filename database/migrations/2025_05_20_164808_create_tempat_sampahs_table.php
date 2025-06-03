<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempatSampahsTable extends Migration
{
    public function up()
    {
        Schema::create('tempat_sampah', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('jenis', ['organik', 'plastik', 'metal']);
            $table->string('lokasi')->nullable();
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('tempat_sampah');
    }
}
