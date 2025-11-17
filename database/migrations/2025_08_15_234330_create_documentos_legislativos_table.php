<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\CategoriaInstrumento;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabla de ordenanzas
        Schema::create('ordenanzas', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CategoriaInstrumento::class)
                 
                 ;
            $table->string('nombre'); // Nombre del documento
            $table->string('ruta');   // Ruta del documento
            $table->text('observacion')->nullable();
            $table->timestamp('fecha_importacion')->nullable(); // Fecha de importación
            $table->timestamp('fecha_aprobacion')->nullable();  // Fecha de aprobación
            $table->timestamps();
        });

        // Tabla de gacetas
        Schema::create('gacetas', function (Blueprint $table) {
            $table->id();       
            $table->string('nombre'); // Nombre del documento
            $table->string('ruta');   // Ruta del documento
            $table->text('categoria')->nullable();
            $table->text('observacion')->nullable();
            $table->timestamp('fecha_importacion')->nullable(); // Fecha de importación
            $table->timestamp('fecha_aprobacion')->nullable();  // Fecha de aprobación
            $table->timestamps();
        });

        // Tabla de acuerdos
        Schema::create('acuerdos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CategoriaInstrumento::class)
                 ;
            $table->string('nombre');
            $table->string('ruta');
            $table->timestamp('fecha_aprobacion')->nullable();
            $table->timestamp('fecha_importacion')->nullable();
            $table->text('observacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acuerdos');
        Schema::dropIfExists('gacetas');
        Schema::dropIfExists('ordenanzas');
    }
};
