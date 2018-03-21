<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coa', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('nama');
            $table->decimal('op_balance', 12,2);
            $table->string('op_balance_dc');
            $table->integer('balance_sheet_group');
            $table->string('balance_sheet_side'); //C or D
            $table->integer('pls_group');
            $table->string("pls_side"); // I
            $table->integer("group_id"); //
            $table->integer("parent_id");
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
        Schema::dropIfExists('coa');
    }
}
