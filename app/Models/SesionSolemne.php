<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SesionSolemne extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla asociada al modelo.
     */
    protected $table = 'sesion_solemne';

    /**
     * Campos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'nombre',
        'ruta',
        'fecha_sesion',
    ];
}
