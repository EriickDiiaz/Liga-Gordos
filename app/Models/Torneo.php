<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Torneo extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'tipo', 'fecha_inicio', 'fecha_fin', 'estado'];

    protected $casts = [
        'fecha_inicio' => 'date',
    ];

    public function grupos()
    {
        return $this->hasMany(Grupo::class);
    }

    public function equipos()
    {
        return $this->belongsToMany(Equipo::class, 'equipos_torneo')
                    ->withPivot('grupo_id', 'puntos', 'partidos_jugados', 'victorias', 'empates', 'derrotas', 'goles_favor', 'goles_contra');
    }

    public function partidos()
    {
        return $this->hasMany(Partido::class);
    }
}

