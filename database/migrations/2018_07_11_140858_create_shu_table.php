<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shu', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_anggota');
            $table->integer('bulan');
            $table->integer('tahun');
            $table->decimal('tiga_puluh', 12,2);
            $table->decimal('tidak_diambil', 12,2);
            $table->decimal('diambil', 12,2);
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
        Schema::dropIfExists('shu');
    }
}
