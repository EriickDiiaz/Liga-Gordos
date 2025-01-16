<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Jugador extends Model
{
    public $table = "jugadores";
    use HasFactory;

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
}

