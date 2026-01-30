<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Verificamos si la tabla no existe antes de crearla
        if (!Schema::hasTable('noticias')) {
            Schema::create('noticias', function (Blueprint $table) {
                $table->id();

                // Campos principales
                $table->string('titulo');
                $table->text('contenido')->nullable();
                $table->string('imagen')->nullable();        // Imagen destacada
                $table->string('archivo_pdf')->nullable();   // PDF si aplica
                
                // Campos para video
                $table->string('video_url')->nullable();     // URL del video (YouTube, Vimeo, etc.)
                $table->string('video_archivo')->nullable(); // Archivo de video subido
                $table->enum('tipo_video', ['url', 'archivo'])->nullable(); // Tipo de video
                
                // Tipo general de publicación
                $table->enum('tipo', ['noticia', 'flyer', 'video', 'cronica'])->default('noticia');
                $table->boolean('destacada')->default(false);
                $table->date('fecha_publicacion')->nullable();

                // Relaciones
                $table->foreignId('cronista_id')
                      ->nullable()
                      ->constrained('cronistas')
                      ->onDelete('set null');

                $table->foreignId('cronica_id')
                      ->nullable()
                      ->constrained('cronicas')
                      ->onDelete('set null');

                $table->timestamps();
            });
        } 
        else {
            // Si la tabla ya existe, aseguramos que tenga las columnas nuevas
            Schema::table('noticias', function (Blueprint $table) {
                if (!Schema::hasColumn('noticias', 'video_archivo')) {
                    $table->string('video_archivo')->nullable()->after('video_url');
                }
                if (!Schema::hasColumn('noticias', 'tipo_video')) {
                    $table->enum('tipo_video', ['url', 'archivo'])->nullable()->after('video_archivo');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('noticias');
    }
};