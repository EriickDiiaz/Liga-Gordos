<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partido extends Model
{
    use HasFactory;

    protected $fillable = [
        'torneo_id', 
        'grupo_id', 
        'equipo_local_id', 
        'equipo_visitante_id', 
        'goles_local', 
        'goles_visitante', 
        'fecha', 
        'tipo', 
        'fase', 
        'estado'
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function torneo()
    {
        return $this->belongsTo(Torneo::class);
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function equipoLocal()
    {
        return $this->belongsTo(Equipo::class, 'equipo_local_id');
    }

    public function equipoVisitante()
    {
        return $this->belongsTo(Equipo::class, 'equipo_visitante_id');
    }

    public function estadisticasJugadores()
    {
        return $this->hasMany(EstadisticaJugador::class);
    }
}