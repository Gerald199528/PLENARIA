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
        Schema::create('sesion_especial', function (Blueprint $table) {
            $table->id(); // ID auto-incremental
            $table->string('nombre'); // Nombre de la sesión
            $table->string('ruta'); // Ruta del archivo PDF
            $table->date('fecha_sesion')->nullable(); // Fecha de la sesión
            $table->string('orador_de_orden')->nullable(); // Orador de orden
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesion_especial');
    }
};
