<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAnimaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('animais', function (Blueprint $table) {
            $table->foreign('raca_id')->references('id')->on('raca')->onDelete('cascade');
            $table->foreign('especie_id')->references('id')->on('especie')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('animais', function (Blueprint $table) {
            //
        });
    }
}
