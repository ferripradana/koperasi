<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJurnalHeaderUnitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jurnal_header_unit', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('entry_type')->nullable();
            $table->string('jurnal_number')->nullable();
            $table->date('tanggal')->nullable();
            $table->decimal('debit_total',12,2)->nullable();
            $table->decimal('credit_total',12,2)->nullable();
            $table->integer('id_unit')->nullable();
            $table->text('narasi')->nullable();
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
        Schema::dropIfExists('jurnal_header_unit');
    }
}
