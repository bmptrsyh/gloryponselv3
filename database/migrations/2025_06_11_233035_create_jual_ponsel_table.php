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
        Schema::create('jual_ponsel', function (Blueprint $table) {
            $table->id('id_jual_ponsel');
            $table->unsignedBigInteger('id_customer');
            $table->string('merk');
            $table->string('model');
            $table->string('warna');
            $table->integer('ram');
            $table->integer('storage');
            $table->string('processor');
            $table->text('kondisi')->nullable();
            $table->text('deskripsi')->nullable();
            $table->integer('harga');
            $table->string('gambar')->nullable();
            $table->enum('status', ['menunggu', 'di setujui', 'di tolak'])->default('menunggu');
            $table->foreign('id_customer')->references('id_customer')->on('customer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jual_ponsel');
    }
};
