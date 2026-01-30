<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SesionEspecial extends Model
{
    use HasFactory;

    protected $table = 'sesion_especial';

    protected $fillable = [
        'nombre',
        'ruta',
        'fecha_sesion',
        'orador_de_orden',
    ];

    protected $casts = [
        'fecha_sesion' => 'datetime', // Convierte automáticamente a Carbon
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
