<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cronica extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'contenido',
        'archivo_pdf',
        'cronista_id',
        'categoria_id',
        'fecha_publicacion'
    ];

    // Relación con cronista
    public function cronista()
    {
        return $this->belongsTo(Cronista::class, 'cronista_id');
    }

    // Relación con categoría
    public function categoria()
    {
        return $this->belongsTo(CategoriaCronica::class, 'categoria_id');
    }
}
