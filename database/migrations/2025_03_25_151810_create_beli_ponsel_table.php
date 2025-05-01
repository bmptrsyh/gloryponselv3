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
        Schema::create('beli_ponsel', function (Blueprint $table) {
            $table->id('id_beli_ponsel');
            $table->unsignedBigInteger('id_customer');
            $table->unsignedBigInteger('id_ponsel');
            $table->string('metode_pembayaran');
            $table->enum('status', ['selesai', 'tertunda'])->default('tertunda');
            $table->dateTime('tanggal_transaksi');
            $table->foreign('id_customer')->references('id_customer')->on('customer');
            $table->foreign('id_ponsel')->references('id_ponsel')->on('ponsel');
            $table->timestamps();

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beli_ponsel');
    }
};
