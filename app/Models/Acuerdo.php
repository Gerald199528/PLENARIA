<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acuerdo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'ruta',
        'fecha_aprobacion',
        'fecha_importacion',
        'observacion',
        'categoria_instrumento_id', 
    ];

    protected $casts = [
        'fecha_aprobacion' => 'datetime',
        'fecha_importacion' => 'datetime',
    ];

    public function categoria()
    {
        return $this->belongsTo(CategoriaInstrumento::class, 'categoria_instrumento_id');
    }
}
