<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();

            // User penerima notifikasi awal (misal: guru)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Relasi ke tempat sampah
            $table->foreignId('tempat_sampah_id')->constrained('tempat_sampah')->onDelete('cascade');

            // Lokasi tempat sampah (redundan tapi cepat diakses)
            $table->string('lokasi');

            // Tambahan logika koordinasi notifikasi
            $table->foreignId('pengirim_id')->nullable()->constrained('users')->nullOnDelete(); // Guru yang memberi perintah
            $table->foreignId('penerima_id')->nullable()->constrained('users')->nullOnDelete(); // Petugas yang ditugaskan

            // Pesan dari guru ke petugas
            $table->text('pesan')->nullable();

            // Status konfirmasi petugas
            $table->boolean('dikonfirmasi')->default(false);

            // Bukti penjemputan (foto, dsb)
            $table->string('bukti_foto')->nullable();

            // Petugas yang mengonfirmasi penjemputan
            $table->foreignId('petugas_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};
