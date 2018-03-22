<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJurnalDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jurnal_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('jurnal_header_id')->nullable();
            $table->integer('coa_id')->nullable();
            $table->decimal('amount',12,2)->nullable();
            $table->string('dc')->nullable();
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
        Schema::dropIfExists('jurnal_detail');
    }
}
