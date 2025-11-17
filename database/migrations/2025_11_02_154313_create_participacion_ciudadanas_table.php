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
        Schema::create('categorias_participacion', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();      
            $table->text('descripcion')->nullable(); 
            $table->timestamps(); 
        });            
            
        Schema::create('sesions_municipal', function (Blueprint $table) {
            $table->id();
            $table->string('titulo'); 
            $table->text('descripcion');
            $table->foreignId('categoria_participacion_id')
                ->constrained('categorias_participacion')
                ->onDelete('cascade');
            $table->dateTime('fecha_hora'); 
            $table->enum('estado', ['abierta', 'proxima', 'cerrada', 'completada'])->default('proxima');         
            $table->timestamps();
        });
     
        Schema::create('derecho_palabra', function (Blueprint $table) {
            $table->id();
            $table->string('cedula')->unique();
            $table->string('nombre'); 
            $table->string('apellido');
            $table->string('email')->unique();
            $table->string('telefono_movil'); 
            $table->string('whatsapp');         
            $table->foreignId('sesion_municipal_id')
                ->nullable()
                ->constrained('sesions_municipal')
                ->onDelete('set null');
            $table->text('motivo_solicitud'); 
            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->timestamp('fecha_respuesta')->nullable();
            $table->boolean('acepta_terminos')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('derecho_palabra');
        Schema::dropIfExists('sesions_municipal');
        Schema::dropIfExists('categorias_participacion');
    }
};