<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SesionOrdinaria extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla en la base de datos
     */
    protected $table = 'sesion_ordinaria';

    /**
     * Campos que pueden asignarse de forma masiva
     */
    protected $fillable = [
        'nombre',
        'ruta',      
        'fecha_sesion',
    ];

    /**
     * Formateo automático de fechas
     */
    protected $casts = [      
        'fecha_sesion' => 'date',
    ];
}
