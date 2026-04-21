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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->unsignedBigInteger('shoe_id');
            $table->string('durasi')->nullable(); // Pastikan kolom durasi ada di sini
            $table->date('tgl_pinjam')->nullable(); // Kolom tgl_pinjam sudah ada di sini
            $table->enum('status', ['dipinjam', 'kembali'])->default('dipinjam');
            $table->timestamps();

            $table->foreign('shoe_id')->references('id')->on('shoes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
