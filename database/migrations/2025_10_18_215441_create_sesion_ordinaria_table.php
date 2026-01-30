<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::create('sesion_ordinaria', function (Blueprint $table) {
        $table->id(); // Crea una columna 'id' auto-incremental
        $table->string('nombre'); // Nombre de la sesión ordinaria
         $table->string('ruta');   // Ruta del documento
        $table->date('fecha_sesion')->nullable(); // Fecha de la sesión
        $table->timestamps(); // created_at y updated_at
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesion_ordinaria');
    }
};
