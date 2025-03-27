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
        'descripcion',
        'partido_relacionado_id',
        'es_ida',
        'estado'
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'es_ida' => 'boolean',
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

    public function acciones()
    {
        return $this->hasMany(AccionPartido::class);
    }

    // Nueva relación para partidos de ida/vuelta
    public function partidoRelacionado()
    {
        return $this->belongsTo(Partido::class, 'partido_relacionado_id');
    }

    public function partidosVuelta()
    {
        return $this->hasMany(Partido::class, 'partido_relacionado_id');
    }

    // Métodos de ayuda para determinar el tipo de partido
    public function esAmistoso()
    {
        return $this->tipo === 'amistoso';
    }

    public function esLiga()
    {
        return $this->tipo === 'grupo';
    }

    public function esEliminatoria()
    {
        return $this->tipo === 'eliminatoria';
    }

    public function esIda()
    {
        return $this->es_ida === true;
    }

    public function esVuelta()
    {
        return $this->es_ida === false;
    }

    // Método para obtener el resultado global (para partidos de ida y vuelta)
    public function resultadoGlobal()
    {
        if (!$this->esEliminatoria() || !$this->partido_relacionado_id) {
            return null;
        }

        $partidoRelacionado = $this->partidoRelacionado;
        if (!$partidoRelacionado) {
            return null;
        }

        $golesLocal = ($this->es_ida ? $this->goles_local : $partidoRelacionado->goles_local) + 
                      ($this->es_ida ? $partidoRelacionado->goles_visitante : $this->goles_visitante);
        
        $golesVisitante = ($this->es_ida ? $this->goles_visitante : $partidoRelacionado->goles_visitante) + 
                          ($this->es_ida ? $partidoRelacionado->goles_local : $this->goles_local);

        return [
            'local' => $golesLocal,
            'visitante' => $golesVisitante
        ];
    }

    // Método para determinar el ganador de la eliminatoria
    public function ganadorEliminatoria()
    {
        $resultado = $this->resultadoGlobal();
        if (!$resultado) {
            return null;
        }

        if ($resultado['local'] > $resultado['visitante']) {
            return $this->es_ida ? $this->equipoLocal : $this->equipoVisitante;
        } elseif ($resultado['local'] < $resultado['visitante']) {
            return $this->es_ida ? $this->equipoVisitante : $this->equipoLocal;
        }

        // En caso de empate, implementar reglas de desempate
        // Por ejemplo, goles de visitante valen doble
        return null;
    }
}

