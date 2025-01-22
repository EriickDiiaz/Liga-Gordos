<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquiposTorneoTable extends Migration
{
    public function up()
    {
        Schema::create('equipos_torneo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('torneo_id')->constrained()->onDelete('cascade');
            $table->foreignId('equipo_id')->constrained()->onDelete('cascade');
            $table->foreignId('grupo_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('puntos')->default(0);
            $table->integer('partidos_jugados')->default(0);
            $table->integer('victorias')->default(0);
            $table->integer('empates')->default(0);
            $table->integer('derrotas')->default(0);
            $table->integer('goles_favor')->default(0);
            $table->integer('goles_contra')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('equipos_torneo');
    }
}

