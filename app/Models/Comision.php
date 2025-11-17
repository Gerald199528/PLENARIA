<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comision extends Model
{
    use HasFactory;

    protected $table = 'comisions';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    // Relación muchos a muchos con Concejales usando 'miembros' en lugar de 'comision_concejal'
public function concejales()
{
    // Relación normal con Concejal usando la tabla pivote original
    return $this->belongsToMany(Concejal::class, 'comision_concejal');
}

    // Relación con la tabla Miembros para acceder a campos extras directamente
    public function miembros()
    {
        return $this->hasMany(Miembro::class);
    }
}
