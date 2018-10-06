<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransaksiUnitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_unit', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_transaksi');
            $table->integer('id_jenis_transaksi');
            $table->decimal('nominal', 12,2);
            $table->integer('type')->nullable();
            $table->date('tanggal');
            $table->integer('jurnal_id');
            $table->integer('id_unit');
            $table->integer('id_supplier')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('nama_bank')->nullable();
            $table->string('no_ref')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('edited_by')->nullable();
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
        Schema::dropIfExists('transaksi_unit');
    }
}
