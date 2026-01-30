<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('concejal', function (Blueprint $table) {
            $table->id();
            $table->string('cedula')->unique();
            $table->string('nombre');   
            $table->string('apellido');          
            $table->string('fecha_nacimiento')->nullable();
            $table->string('telefono')->nullable();    
            $table->string('cargo')->nullable();
            $table->text('perfil')->nullable();
            $table->string('imagen_url')->nullable(); // imagen de perfil
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('concejal');
    }
};
