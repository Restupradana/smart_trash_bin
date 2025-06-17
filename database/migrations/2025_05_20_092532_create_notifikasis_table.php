<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pengirim_id')->constrained('users')->onDelete('cascade'); // guru/user
            $table->foreignId('tempat_sampah_id')->constrained('tempat_sampah')->onDelete('cascade');
            $table->foreignId('sensor_id')->constrained('sensors')->onDelete('cascade'); // sensor sumber nilai

            // Redundant data (boleh jika ingin cepat akses di dashboard)
            $table->float('nilai_kapasitas')->nullable(); // nilai saat dikirim
            $table->float('nilai_berat')->nullable();     // nilai saat dikirim
            $table->string('lokasi'); // diambil dari tempat_sampah->lokasi

            $table->text('pesan')->nullable();

            $table->foreignId('petugas_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['pending', 'dikonfirmasi', 'ditolak'])->default('pending');
            $table->timestamp('dikonfirmasi_pada')->nullable();
            $table->string('bukti_foto')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};
