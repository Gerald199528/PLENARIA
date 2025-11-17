<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comision_concejal', function (Blueprint $table) {
            $table->id();

            // Relación con miembros (nullable para historial)
            $table->foreignId('miembro_id')
                  ->nullable()
                  ->constrained('miembros')
                  ->onDelete('set null');

            // Relaciones con concejal y comisión (nullable para auditoría)
            $table->foreignId('concejal_id')
                  ->nullable()
                  ->constrained('concejal')
                  ->onDelete('set null');

            $table->foreignId('comision_id')
                  ->nullable()
                  ->constrained('comisions')
                  ->onDelete('set null');

            // Campos de auditoría para mantener info histórica
            $table->string('nombre_concejal')->nullable();
            $table->string('cedula_concejal')->nullable();
            $table->string('nombre_comision')->nullable();

            // Fechas de creación y actualización
            $table->timestamps();

            // Evitar duplicados por concejal y comisión en el historial reciente
            $table->unique(['concejal_id', 'comision_id', 'miembro_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comision_concejal');
    }
};
