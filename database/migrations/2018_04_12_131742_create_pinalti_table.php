<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePinaltiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pinalti', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_transaksi');
            $table->integer('id_peminjaman');
            $table->integer('id_anggota');
            $table->decimal('nominal', 12,2);
            $table->decimal('angsuran_nominal', 12,2);
            $table->integer('banyak_angsuran');
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->integer('status')->nullable();
            $table->integer('created_by')->nullable();
            $table->date('tanggal_validasi')->nullable();
            $table->integer('approve_by')->nullable();
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
        Schema::dropIfExists('pinalti');
    }
}
