<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaCronica extends Model
{
    use HasFactory;

    protected $table = 'categoria_cronicas';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    // Relación con cronistas
    public function cronistas()
    {
        return $this->hasMany(Cronista::class, 'categoria_id');
    }

    // Relación con crónicas
    public function cronicas()
    {
        return $this->hasMany(Cronica::class, 'categoria_id');
        
    }


    
}
