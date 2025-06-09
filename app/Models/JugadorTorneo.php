<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JugadorTorneo extends Model
{
    use HasFactory;
    
    protected $table = 'jugadores_torneo';
    
    protected $fillable = [
        'jugador_id',
        'torneo_id',
        'equipo_id',
        'goles',
        'tarjetas_amarillas',
        'tarjetas_rojas',
        'porterias_imbatidas',
        'activo'
    ];
    
    public function jugador()
    {
        return $this->belongsTo(Jugador::class);
    }
    
    public function torneo()
    {
        return $this->belongsTo(Torneo::class);
    }
    
    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }
}
