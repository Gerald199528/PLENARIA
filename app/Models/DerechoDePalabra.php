<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DerechoDePalabra extends Model
{
    use HasFactory;

    protected $table = 'derecho_palabra';

    protected $fillable = [
        'ciudadano_id',
        'sesion_municipal_id',
        'comision_id',
        'motivo_solicitud',
        'estado',
        'observaciones',
        'fecha_respuesta',
        'acepta_terminos',
    ];

    protected $casts = [
        'acepta_terminos' => 'boolean',
        'fecha_respuesta' => 'datetime',
    ];

    /**
     * Relación con Ciudadano
     */
    public function ciudadano()
    {
        return $this->belongsTo(Ciudadano::class);
    }

    /**
     * Relación con SesionMunicipal
     */
    public function sesion()
    {
        return $this->belongsTo(SesionMunicipal::class, 'sesion_municipal_id');
    }

    /**
     * Relación con Comisión
     */
    public function comision()
    {
        return $this->belongsTo(Comision::class);
    }

    public function scopeAprobadas($query)
    {
        return $query->where('estado', 'aprobada');
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeRechazadas($query)
    {
        return $query->where('estado', 'rechazada');
    }
}
