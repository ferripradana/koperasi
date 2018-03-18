<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnggotaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anggota', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama');
            $table->string('nia');
            $table->string('nik');
            $table->string('no_ktp');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('kecamatan');
            $table->string('kabupaten');
            $table->integer('unit_kerja');
            $table->integer('tahun_masuk');
            $table->integer('pengampu')->nullable();
            $table->string('nama_bank');
            $table->string('nomor_rekening');
            $table->string('foto');
            $table->string('phone');
            $table->integer('jabatan')->nullable();
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anggota');
    }
}
