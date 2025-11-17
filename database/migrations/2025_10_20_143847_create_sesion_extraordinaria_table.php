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
        Schema::create('sesion_extraordinaria', function (Blueprint $table) {
            $table->id(); // Crea una columna 'id' auto-incremental
            $table->string('nombre'); // Nombre de la ordinaria
            $table->string('ruta'); // Ruta del archivo   
            $table->date('fecha_sesion')->nullable(); // Agregar el campo fecha_sesion
            $table->timestamps(); // Crea las columnas 'created_at' y 'updated_at'
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesion_extraordinaria');
    }
};
