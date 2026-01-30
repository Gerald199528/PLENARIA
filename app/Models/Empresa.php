<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Empresa extends Model
{
    use HasFactory;

    protected $table = 'empresa';

    protected $fillable = [
        'name',
        'razon_social',
        'rif',
        'direccion_fiscal',
        'latitud',
        'longitud',
        'oficina_principal',
        'horario_atencion',
        'telefono_principal',
        'telefono_secundario',
        'email_principal',
        'email_secundario',
        'domain',
        'actividad',
        'description',       
        'organigrama_ruta',
        'mision',
        'vision',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'latitud' => 'decimal:7',
        'longitud' => 'decimal:7',
    ];

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)
            ->timezone('America/Caracas')
            ->format('d/m/Y H:i:s');
    }

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at
            ->timezone('America/Caracas')
            ->translatedFormat('F Y'); // Ejemplo: "Octubre 2025"
    }
}