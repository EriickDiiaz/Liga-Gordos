<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGruposTable extends Migration
{
    public function up()
    {
        Schema::create('grupos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('torneo_id')->constrained()->onDelete('cascade');
            $table->string('nombre');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('grupos');
    }
}