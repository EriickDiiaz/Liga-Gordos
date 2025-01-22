<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTorneosTable extends Migration
{
    public function up()
    {
        Schema::create('torneos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->enum('tipo', ['eliminatoria', 'liga', 'mixto']);
            $table->date('fecha_inicio');
            $table->enum('estado', ['planificado', 'en_curso', 'finalizado']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('torneos');
    }
}