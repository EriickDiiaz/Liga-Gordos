<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstadisticasJugadorTable extends Migration
{
    public function up()
    {
        Schema::create('estadisticas_jugador', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partido_id')->constrained()->onDelete('cascade');
            $table->foreignId('jugador_id')->constrained('jugadores')->onDelete('cascade');
            $table->integer('goles')->default(0);
            $table->integer('tarjetas_amarillas')->default(0);
            $table->boolean('tarjeta_roja')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('estadisticas_jugador');
    }
}

