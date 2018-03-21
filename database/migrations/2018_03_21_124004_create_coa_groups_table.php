<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoaGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coa_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->String('code');
            $table->string('description');
            $table->integer('balance_sheet_group');
            $table->string('balance_sheet_side'); //C or D
            $table->integer('pls_group');
            $table->string("pls_side"); // I or E
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
        Schema::dropIfExists('coa_groups');
    }
}
