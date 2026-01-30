<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    use HasFactory;

    protected $table = 'noticias';

    protected $fillable = [
        'titulo',
        'contenido',
        'imagen',
        'archivo_pdf',
        'video_url',
        'video_archivo',
        'tipo_video',
        'tipo',
        'destacada',
        'fecha_publicacion',
        'cronista_id',
        'cronica_id',
    ];

    // Relación con cronista
    public function cronista()
    {
        return $this->belongsTo(Cronista::class);
    }

    // Relación con Crónica (si existe tabla cronicas separada)
    public function cronica()
    {
        return $this->belongsTo(Cronica::class);
    }

    // Método para obtener el video de la noticia actual
    public function getVideo()
    {
        // Primero verificar si tiene video en la misma noticia
        if ($this->video_url || $this->video_archivo) {
            return (object) [
                'video_url' => $this->video_url,
                'video_archivo' => $this->video_archivo,
                'tipo_video' => $this->tipo_video,
            ];
        }
        
        // Si es crónica y tiene cronica_id, obtener video de la tabla cronicas
        if ($this->tipo === 'cronica' && $this->cronica_id && $this->cronica) {
            return (object) [
                'video_url' => $this->cronica->video_url ?? null,
                'video_archivo' => $this->cronica->video_archivo ?? null,
                'tipo_video' => $this->cronica->tipo_video ?? null,
            ];
        }
        
        return null;
    }

    // Esto convierte automáticamente 'fecha_publicacion' en un objeto Carbon
    protected $casts = [
        'fecha_publicacion' => 'datetime',
        'destacada' => 'boolean',
    ];
}