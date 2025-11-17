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
        Schema::create('cronicas', function (Blueprint $table) {
            $table->id();

            $table->string('titulo');
            $table->text('contenido')->nullable();
            $table->string('archivo_pdf')->nullable();

            // Relación con cronista
            $table->foreignId('cronista_id')
                  ->constrained('cronistas')
                  ->onDelete('cascade');

            // Relación con categoría (opcional)
            $table->foreignId('categoria_id')
                  ->nullable()
                  ->constrained('categoria_cronicas')
                  ->onDelete('set null');

            $table->date('fecha_publicacion')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cronicas');
    }
};
