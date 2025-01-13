<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'logo', 'color_primario', 'color_secundario', 'estado'];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function jugadores()
    {
        return $this->hasMany(Jugador::class);
    }

    public function partidos()
    {
        return $this->belongsToMany(Partido::class, 'partido_equipo')->withPivot('goles');
    }
}