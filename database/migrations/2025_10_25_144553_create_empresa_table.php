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
        Schema::create('empresa', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('razon_social')->nullable();
            $table->string('rif')->nullable();
            $table->string('direccion_fiscal')->nullable();
            $table->string('oficina_principal')->nullable();
            $table->string('horario_atencion')->nullable();
            $table->string('telefono_principal')->nullable();
            $table->string('telefono_secundario')->nullable();
            $table->string('email_principal')->nullable();
            $table->string('email_secundario')->nullable();
            $table->string('domain')->nullable();
            $table->string('actividad')->nullable();
            $table->text('description')->nullable();       
            $table->string('organigrama_ruta')->nullable(); 
            $table->text('mision')->nullable();
            $table->text('vision')->nullable();
            $table->decimal('latitud', 10, 7)->nullable();   
            $table->decimal('longitud', 10, 7)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresa');
    }
};
