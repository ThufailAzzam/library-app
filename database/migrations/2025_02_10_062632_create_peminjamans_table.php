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
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bukus_id')->constrained('bukus', 'id')->onDelete('cascade');
            $table->string('nama_peminjam');
            $table->dateTime('borrow_date');
            $table->dateTime('due_date');
            $table->dateTime('return_date')->nullable();
            $table->string('status')->default('active');
            
            $table->decimal('fine', 8, 2)->default(0); // Fine amount
            $table->string('condition')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
