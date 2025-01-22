<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    protected $fillable = ['torneo_id', 'nombre'];

    public function torneo()
    {
        return $this->belongsTo(Torneo::class);
    }

    public function equipos()
    {
        return $this->belongsToMany(Equipo::class, 'equipos_torneo')
                    ->withPivot('puntos', 'partidos_jugados', 'victorias', 'empates', 'derrotas', 'goles_favor', 'goles_contra');
    }

    public function partidos()
    {
        return $this->hasMany(Partido::class);
    }
}