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
        Schema::create('bukus', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('penulis');
            $table->string('kode_buku')->unique();
            $table->string('cover_buku')->nullable();
            $table->string('penerbit', 50);
            $table->integer('jumlah_pinjam')->default(0);
            $table->year('tahun_terbit');
            $table->integer('stok')->default(0);
            $table->decimal('harga_buku', 8, 2)->default(0);
            $table->unsignedBigInteger('request_id')->nullable();
            $table->foreign('request_id')->references('id')->on('perminatan_bukus')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
