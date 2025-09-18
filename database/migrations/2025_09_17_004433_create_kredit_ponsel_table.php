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
        Schema::create('kredit_ponsel', function (Blueprint $table) {
            $table->id('id_kredit_ponsel');
            $table->unsignedBigInteger('id_customer');
            $table->unsignedBigInteger('id_ponsel');
            $table->string('nama_lengkap');
            $table->string('NIK');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('jenis_kelamin');
            $table->string('status_pernikahan');
            $table->string('no_telepon');
            $table->string('email');
            $table->text('alamat_ktp');
            $table->text('alamat_domisili');
            $table->string('pekerjaan');
            $table->string('nama_perusahaan');
            $table->integer('lama_bekerja');
            $table->integer('penghasilan_per_bulan');
            $table->integer('tenor');
            $table->integer('jumlah_DP');
            $table->integer('penghasilan_lainnya')->nullable();
            $table->text('alamat_perusahaan');
            $table->integer('jumlah_pinjaman');
            $table->string('gambar_ktp');
            $table->string('gambar_selfie');
            $table->integer('angsuran_per_bulan');
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
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
        Schema::dropIfExists('kredit_ponsel');
    }
};
