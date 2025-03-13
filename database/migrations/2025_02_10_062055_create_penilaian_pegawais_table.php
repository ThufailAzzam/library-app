<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penilaian_pegawais', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pelapor')->nullable(); // Bisa null untuk laporan anonim
            $table->foreign('id_pelapor')->references('id')->on('users')->onDelete('set null');
            
            $table->unsignedBigInteger('id_pegawai');
            $table->foreign('id_pegawai')->references('id')->on('users')->onDelete('cascade');
            
            $table->enum('penilaian', ['Sangat Baik', 'Baik', 'Cukup', 'Buruk']);
            $table->integer('skor_penilaian')->nullable(); // Penilaian numerik opsional
            $table->string('jenis_insiden', 100); // Jenis insiden yang dilaporkan
            $table->text('deskripsi'); // Deskripsi insiden secara detail
            $table->string('lokasi', 100)->nullable(); // Lokasi di perpustakaan
            $table->boolean('anonim')->default(false); // Flag untuk laporan anonim
            $table->string('foto_kejadian')->nullable(); // Foto yang diupload sebagai bukti kejadian
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_pegawais');
    }
};