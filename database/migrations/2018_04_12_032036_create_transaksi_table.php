<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransaksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_transaksi');
            $table->integer('id_jenis_transaksi');
            $table->decimal('nominal', 12,2);
            $table->integer('type')->nullable();
            $table->date('tanggal');
            $table->integer('jurnal_id')->nullable();
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('transaksi');
    }
}
