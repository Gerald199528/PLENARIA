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
        Schema::create('cronistas', function (Blueprint $table) {
            $table->id();

            $table->string('cedula')->unique();
            $table->string('nombre_completo');
            $table->string('apellido_completo');
            $table->string('email')->unique()->nullable();
            $table->string('telefono')->nullable();
           $table->string('imagen_url')->nullable(); 
            $table->string('cargo')->nullable();
            $table->string('perfil')->nullable();
             $table->date('fecha_ingreso')->nullable();
            // Relaciones con estado, municipio y parroquia
            $table->foreignId('estado_id')->nullable()->constrained('estados')->onDelete('set null');
            $table->foreignId('municipio_id')->nullable()->constrained('municipios')->onDelete('set null');
            $table->foreignId('parroquia_id')->constrained('parroquias')->onDelete('cascade');

      
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cronistas');
    }
};
