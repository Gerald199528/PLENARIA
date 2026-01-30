<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaParticipacion extends Model
{
    protected $table = 'categorias_participacion';
    
    protected $fillable = [
        'nombre',
        'descripcion',
    ];
}