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
        Schema::create('jual_pondel', function (Blueprint $table) {
            $table->id('id_jual_pondel');
            $table->unsignedBigInteger('id_customer');
            $table->unsignedBigInteger('id_ponsel');
            $table->integer('harga');
            $table->enum('status', ['menunggu', 'di setujui', 'di tolak'])->default('menunggu');
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
        Schema::dropIfExists('jual_pondel');
    }
};
