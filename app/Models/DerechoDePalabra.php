<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DerechoDePalabra extends Model
{
    use HasFactory;

    protected $table = 'derecho_palabra';

    protected $fillable = [
        'cedula',
        'nombre',
        'apellido',
        'email',
        'telefono_movil',
        'whatsapp',
        'categoria_participacion_id',
        'sesion_municipal_id',
        'motivo_solicitud',
        'estado',
        'observaciones',
        'fecha_respuesta',
        'acepta_terminos',
    ];

    protected $casts = [
        'acepta_terminos' => 'boolean',
        'fecha_respuesta' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    /**
     * Relación con SesionMunicipal
     */
    public function sesion()
    {
        return $this->belongsTo(SesionMunicipal::class, 'sesion_municipal_id');
    }

    /**
     * Scope para obtener solicitudes aprobadas
     */
    public function scopeAprobadas($query)
    {
        return $query->where('estado', 'aprobada');
    }

    /**
     * Scope para obtener solicitudes pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope para obtener solicitudes rechazadas
     */
    public function scopeRechazadas($query)
    {
        return $query->where('estado', 'rechazada');
    }
}