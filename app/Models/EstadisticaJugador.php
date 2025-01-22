<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadisticaJugador extends Model
{
    use HasFactory;

    protected $table = 'estadisticas_jugador';

    protected $fillable = ['partido_id', 'jugador_id', 'goles', 'tarjetas_amarillas', 'tarjeta_roja'];

    public function partido()
    {
        return $this->belongsTo(Partido::class);
    }

    public function jugador()
    {
        return $this->belongsTo(Jugador::class);
    }
}

