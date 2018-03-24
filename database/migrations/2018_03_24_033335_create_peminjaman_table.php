<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeminjamanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_transaksi');
            $table->integer('id_anggota');
            $table->integer('id_keterangan_pinjaman');
            $table->decimal('nominal', 12,2);
            $table->date('tanggal_pengajuan')->nullable();
            $table->date('tanggal_disetujui')->nullable();
            $table->integer('tenor')->nullable();
            $table->decimal('bunga_persen')->nullable();
            $table->decimal('cicilan')->nullable();
            $table->decimal('bunga_nominal')->nullable();
            $table->integer('status')->nullable(); //0=>menunggu persetujuan , 1 => disetujui , 2 => lunas
            $table->integer('approve_by')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('edited_by')->nullable();
            $table->timestamps();
            //deskripsi , jurnal_id
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('peminjaman');
    }
}
