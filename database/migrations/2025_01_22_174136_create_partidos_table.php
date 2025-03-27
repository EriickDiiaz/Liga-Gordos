<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartidosTable extends Migration
{
    public function up()
    {
        Schema::create('partidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('torneo_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('grupo_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('equipo_local_id')->constrained('equipos')->onDelete('cascade');
            $table->foreignId('equipo_visitante_id')->constrained('equipos')->onDelete('cascade');
            $table->integer('goles_local')->nullable();
            $table->integer('goles_visitante')->nullable();
            $table->dateTime('fecha');
            $table->enum('tipo', ['grupo', 'eliminatoria', 'amistoso']);
            $table->string('fase')->nullable();
            $table->string('descripcion')->nullable();
            $table->foreignId('partido_relacionado_id')->nullable()->references('id')->on('partidos')->onDelete('set null');
            $table->boolean('es_ida')->nullable();
            $table->enum('estado', ['programado', 'en_curso', 'finalizado']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('partidos');
    }
}

