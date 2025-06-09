<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Jugador extends Model
{
    use HasFactory;
    public $table = 'jugadores';
    
    protected $fillable = ['nombre', 'cedula', 'fecha_nacimiento', 'dorsal', 'tipo', 'foto', 'equipo_id'];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    public function getRouteKeyName()
    {
        return 'id';
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    public function getEdadAttribute()
    {
        return $this->fecha_nacimiento 
            ? Carbon::parse($this->fecha_nacimiento)->age 
            : null;
    }

    public function getEquipoNombreAttribute()
    {
        return $this->equipo ? $this->equipo->nombre : 'Sin equipo';
    }

    // Nueva relación para torneos
    public function torneos()
    {
        return $this->belongsToMany(Torneo::class, 'jugadores_torneo')
                    ->withPivot('goles', 'tarjetas_amarillas', 'tarjetas_rojas', 'porterias_imbatidas', 'activo')
                    ->withTimestamps();
    }
    
    // Método para verificar si el jugador está inscrito en un torneo específico
    public function estaInscritoEnTorneo($torneoId)
    {
        return $this->torneos()->where('torneo_id', $torneoId)->exists();
    }
}

