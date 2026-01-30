<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ordenanza extends Model
{
    protected $fillable = [
        'nombre',
        'ruta',
        'observacion',
        'categoria_instrumento_id',
        'fecha_importacion',
        'fecha_aprobacion'
    ];

    protected $casts = [
        'fecha_importacion' => 'datetime',
        'fecha_aprobacion'  => 'datetime',
    ];

    // Relación con CategoriaInstrumento
    public function categoria()
    {
        return $this->belongsTo(CategoriaInstrumento::class, 'categoria_instrumento_id');
    }

    // Nombre del documento
    public function getDocumentoAttribute(): string
    {
        return basename($this->ruta);
    }


}
