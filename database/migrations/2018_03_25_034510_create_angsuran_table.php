<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAngsuranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('angsuran', function (Blueprint $table) {
            $table->increments('id');
            $table->string("no_transaksi");
            $table->date('tanggal_transaksi');
            $table->integer('id_pinjaman');
            $table->integer('id_anggota');
            $table->decimal('pokok',12,2);
            $table->decimal('bunga',12,2)->nullable();
            $table->decimal('simpanan_wajib', 12,2)->nullable();
            $table->decimal('denda', 12,2)->nullable();
            $table->integer('status')->nullable();
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('angsuran');
    }
}
