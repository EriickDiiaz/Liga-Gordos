<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccionPartido extends Model
{
    use HasFactory;

    public $table = 'acciones_partido';

    protected $fillable = ['partido_id', 'jugador_id', 'tipo_accion'];

    public function partido()
    {
        return $this->belongsTo(Partido::class);
    }

    public function jugador()
    {
        return $this->belongsTo(Jugador::class);
    }
}