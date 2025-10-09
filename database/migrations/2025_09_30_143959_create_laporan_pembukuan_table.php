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
        Schema::create('laporan_pembukuan', function (Blueprint $table) {
            $table->id('id_laporan');
            $table->nullableMorphs('transaksi');
            $table->date('tanggal');
            $table->string('deskripsi');
            $table->integer('debit')->nullable();
            $table->integer('kredit')->nullable();
            $table->integer('saldo')->nullable();
            $table->string('metode_pembayaran')->nullable();
            $table->timestamps();
            $table->unique(['transaksi_id', 'transaksi_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_pembukuan');
    }
};
