<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jugadores_torneo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jugador_id')->constrained('jugadores')->onDelete('cascade');
            $table->foreignId('torneo_id')->constrained('torneos')->onDelete('cascade');
            $table->foreignId('equipo_id')->constrained('equipos')->onDelete('cascade');
            $table->integer('goles')->default(0);
            $table->integer('tarjetas_amarillas')->default(0);
            $table->integer('tarjetas_rojas')->default(0);
            $table->integer('porterias_imbatidas')->default(0)->comment('Solo para porteros');
            $table->boolean('activo')->default(true)->comment('Permite desactivar un jugador sin eliminarlo');
            $table->timestamps();
            
            // Índices para mejorar el rendimiento
            $table->index(['torneo_id', 'equipo_id']);
            $table->unique(['jugador_id', 'torneo_id']); // Un jugador solo puede estar una vez en un torneo
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jugadores_torneo');
    }
};
