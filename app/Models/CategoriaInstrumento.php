<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaInstrumento extends Model
{
    protected $table = 'categoria_instrumentos';

    protected $fillable = [
        'nombre',
        'tipo_categoria', 
        'observacion',
    ];
}
