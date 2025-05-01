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
        Schema::create('ulasan', function (Blueprint $table) {
            $table->id('id_ulasan');
            $table->unsignedBigInteger('id_beli_ponsel');
            $table->unsignedBigInteger('id_ponsel');
            $table->text('ulasan');
            $table->integer('rating');
            $table->foreign('id_beli_ponsel')->references('id_beli_ponsel')->on('beli_ponsel');
            $table->foreign('id_ponsel')->references('id_ponsel')->on('ponsel');
            $table->dateTime('tanggal_ulasan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ulasan');
    }
};
