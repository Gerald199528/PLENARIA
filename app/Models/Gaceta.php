<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gaceta extends Model
{
    protected $fillable = [
        'nombre',
        'ruta',
        'categoria',  
        'observacion',  
        'fecha_importacion',
        'fecha_aprobacion',
    ];

    protected $casts = [
        'fecha_importacion' => 'datetime',
        'fecha_aprobacion'  => 'datetime',
    ];

    /**
     * Obtener solo el nombre del documento desde la ruta.
     */
    public function getDocumentoAttribute(): string
    {
        return basename($this->ruta);
    }



}
