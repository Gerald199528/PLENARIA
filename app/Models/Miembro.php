<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Miembro extends Model
{
    use HasFactory;

    protected $table = 'miembros';

    protected $fillable = [
    
        'fecha_inicio',
        'fecha_fin',
        'estado',
    ];

    // Relación con Concejal
    public function concejal()
    {
        return $this->belongsTo(Concejal::class, 'concejal_id');
    }

    // Relación con Comision
    public function comision()
    {
        return $this->belongsTo(Comision::class, 'comision_id');
    }


        public function comisions()
    {
        return $this->belongsToMany(Comision::class, 'comision_concejal');
    }

        public function concejals()
    {
        return $this->belongsToMany(Concejal::class, 'comision_concejal');
    }


    /**
     * Obtener el cargo directamente del concejal
     */
    public function getCargoAttribute()
    {
        return $this->concejal->cargo ?? null;
    }
}
