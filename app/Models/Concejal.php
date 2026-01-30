<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Concejal extends Model
{
    use HasFactory;

    protected $table = 'concejal';

    protected $fillable = [
        'cedula',
        'nombre',     
        'apellido', 
        'fecha_nacimiento',
        'telefono',      
        'cargo',
        'perfil',
        'imagen_url',
    ];
        public function comisions()
    {
        return $this->belongsToMany(Comision::class, 'comision_concejal');
    }

    // Relación con la tabla Miembros para acceder a campos extras directamente
    public function miembros()
    {
        return $this->hasMany(Miembro::class);
    }
}
