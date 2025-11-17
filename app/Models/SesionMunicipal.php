<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesionMunicipal extends Model
{
    protected $table = 'sesions_municipal';

    protected $fillable = [
        'titulo',
        'descripcion',
        'categoria_participacion_id',
        'fecha_hora',
        'estado',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
    ];

    // Relación: Una sesión pertenece a una categoría
    public function categoria()
    {
        return $this->belongsTo(CategoriaParticipacion::class, 'categoria_participacion_id');
    }

    // Relación: Una sesión tiene muchos derechos de palabra
    public function derechosPalabra()
    {
        return $this->hasMany(DerechoPalabra::class, 'sesion_municipal_id');
    }
}