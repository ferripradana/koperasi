<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProyeksiAngsuranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proyeksi_angsuran', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('peminjaman_id');
            $table->date('tanggal_proyeksi')->nullable();
            $table->decimal('cicilan',12,2)->nullable();
            $table->decimal('bunga_nominal',12,2)->nullable();
            $table->decimal('simpanan_wajib',12,2)->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('proyeksi_angsuran');
    }
}
