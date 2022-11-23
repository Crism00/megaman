<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mapas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nombre');
            $table->unsignedBigInteger('tipo');
            $table->unsignedBigInteger('juego');
            $table->unsignedBigInteger('jefe');

            $table->foreign('tipo')->references('id')->on('tipos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('juego')->references('id')->on('juegos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('jefe')->references('id')->on('jefes')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mapas');
    }
};
