<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ciudadano extends Model
{
    protected $fillable = [
        'cedula',
        'nombre',
        'apellido',
        'email',
        'telefono_movil',
        'whatsapp'
    ];

    // Un ciudadano tiene muchas solicitudes
    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class);
    }

    // Un ciudadano tiene muchos derechos de palabra
    public function derechosPalabra()
    {
        return $this->hasMany(DerechoPalabra::class);
    }
}
