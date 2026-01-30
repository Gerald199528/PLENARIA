<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Cronista extends Model
{
    use HasFactory;

    protected $table = 'cronistas';

    protected $fillable = [
        'cedula',
        'nombre_completo',
        'apellido_completo',
        'email',
        'telefono',
        'imagen_url',
        'categoria_id',
        'estado_id',
        'municipio_id',
        'parroquia_id',
        'cargo',
        'perfil',
        'fecha_ingreso',
    ];

    // Convertir automáticamente fecha_ingreso a instancia de Carbon
    protected $casts = [
        'fecha_ingreso' => 'date',
    ];

    // Accesor para formatear fecha_ingreso de forma segura
    public function getFechaIngresoFormattedAttribute()
    {
        try {
            return $this->fecha_ingreso ? Carbon::parse($this->fecha_ingreso)->format('d/m/Y') : '';
        } catch (\Exception $e) {
            return '';
        }
    }

    // Relación con estado, municipio y parroquia
    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }

    public function parroquia()
    {
        return $this->belongsTo(Parroquia::class, 'parroquia_id');
    }

    // Relación con crónicas
    public function cronicas()
    {
        return $this->hasMany(Cronica::class, 'cronista_id');
    }
}
