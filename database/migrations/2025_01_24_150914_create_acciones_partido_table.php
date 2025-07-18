<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccionesPartidoTable extends Migration
{
    public function up()
    {
        Schema::create('acciones_partido', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partido_id')->constrained()->onDelete('cascade');
            $table->foreignId('jugador_id')->constrained('jugadores')->onDelete('cascade');
            $table->enum('tipo_accion', ['gol', 'tarjeta_amarilla', 'tarjeta_roja', 'porteria_imbatida']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('acciones_partido');
    }
}