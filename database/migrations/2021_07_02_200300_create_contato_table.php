<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContatoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contato', function (Blueprint $table) {
            $table->id();
            $table->string('telefone_1', 32)->nullable();
            $table->string('tipo_telefone_1', 32)->nullable();
            $table->string('telefone_2', 32)->nullable();
            $table->string('tipo_telefone_2', 32)->nullable();
            $table->string('email', 64)->nullable();
            $table->unsignedBigInteger('pessoa_id');

            $table->foreign('pessoa_id')->references('id')->on('pessoas')->onDelete('cascade');

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
        Schema::dropIfExists('contato');
    }
}
