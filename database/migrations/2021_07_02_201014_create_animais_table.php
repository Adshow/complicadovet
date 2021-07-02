<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnimaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animais', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pessoa_id');
            $table->string('nome',32);
            $table->unsignedBigInteger('raca_id');
            $table->unsignedBigInteger('especie_id');
            $table->text('historico_clinico')->nullable();
            $table->string('nascimento')->nullable();

            $table->timestamps();

            $table->foreign('pessoa_id')->references('id')->on('pessoas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('animais');
    }
}
