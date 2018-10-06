<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Id,Kode Supllier, Nama ,Alamat ,Nama Bank, Nama pemilik Rekening,No Rekening,No Telepon, status (PKP / NON PKP), Status beli (Konsinyasi / Tempo/ Tunai)

        Schema::create('supplier', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode_supplier');
            $table->string('nama');
            $table->text('alamat');
            $table->integer('id_bank');
            $table->string('nama_rekening');
            $table->string('no_rekening');
            $table->string('phone');
            $table->integer('status'); //1:PKP, 2:Non PKP
            $table->integer('status_beli'); //1:konsinyasi, 2:tempo, 3.tunai
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
        Schema::dropIfExists('supplier');
    }
}
