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
        Schema::create('tukar_tambah', function (Blueprint $table) {
            $table->id('id_tukar_tambah');
            $table->unsignedBigInteger('id_customer');
            $table->unsignedBigInteger('id_ponsel_lama');
            $table->unsignedBigInteger('id_ponsel_baru');
            $table->enum('status', ['menunggu', 'di setujui', 'di tolak'])->default('menunggu');
            $table->integer('harga_tambahan')->nullable();
            $table->foreign('id_ponsel_lama')->references('id_ponsel')->on('ponsel');
            $table->foreign('id_ponsel_baru')->references('id_ponsel')->on('ponsel');
            $table->foreign('id_customer')->references('id_customer')->on('customer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tukar_tambah');
    }
};
