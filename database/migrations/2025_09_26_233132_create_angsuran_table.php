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
        Schema::create('angsuran', function (Blueprint $table) {
            $table->id('id_angsuran');
            $table->unsignedBigInteger('id_kredit_ponsel');
            $table->integer('bulan_ke');
            $table->integer('jumlah_cicilan');
            $table->date('jatuh_tempo');
            $table->date('tanggal_bayar')->nullable();
            $table->enum('status', ['belum', 'lunas'])->default('belum');
            $table->timestamps();
            $table->foreign('id_kredit_ponsel')->references('id_kredit_ponsel')->on('kredit_ponsel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('angsuran');
    }
};
