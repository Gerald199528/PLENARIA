<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoriaInstrumento;

class CategoriaInstrumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [

            // 🔹 Tipo: Acuerdos
            [
                'nombre' => 'Acuerdos de declaratoria (Hijos)',
                'tipo_categoria' => 'Acuerdos',
                'observacion' => 'Resoluciones relacionadas con la declaratoria de hijos.',
            ],
            [
                'nombre' => 'Acuerdos de afectación de terrenos',
                'tipo_categoria' => 'Acuerdos',
                'observacion' => 'Decisiones sobre la afectación de terrenos municipales.',
            ],
            [
                'nombre' => 'Acuerdos de desafectación de terrenos',
                'tipo_categoria' => 'Acuerdos',
                'observacion' => 'Decisiones sobre la desafectación de terrenos municipales.',
            ],
            [
                'nombre' => 'Acuerdos de declaratoria de patrimonio',
                'tipo_categoria' => 'Acuerdos',
                'observacion' => 'Resoluciones de declaratoria de patrimonio.',
            ],
            [
                'nombre' => 'Acuerdos políticos',
                'tipo_categoria' => 'Acuerdos',
                'observacion' => 'Resoluciones de carácter político.',
            ],

            // 🔹 Tipo: Ordenanzas 
            [
                'nombre' => 'Bienes, Tierras y Ejidos',
                'tipo_categoria' => 'Ordenanzas',
            
            ],


            [
                'nombre' => 'Territorialidad',
                'tipo_categoria' => 'Ordenanzas',
            
            ],

            
            [
                'nombre' => 'Legislativo',
                'tipo_categoria' => 'Ordenanzas',
            
            ],

            
            [
                'nombre' => 'Niñes y adolecescencia',
                'tipo_categoria' => 'Ordenanzas',
            
            ],

            
            [
                'nombre' => 'Participación ciudadana y comunal',
                'tipo_categoria' => 'Ordenanzas',
            
            ],

                [
                'nombre' => 'Mujer e Igualdad de género',
                'tipo_categoria' => 'Ordenanzas',
            
            ],

             [
                'nombre' => 'Ambiente',
                'tipo_categoria' => 'Ordenanzas',
            
            ],


               [
                'nombre' => 'Servicios públicos',
                'tipo_categoria' => 'Ordenanzas',
            
            ],


               [
                'nombre' => 'Transporte y transito terrestre',
                'tipo_categoria' => 'Ordenanzas',
            
            ],

            
               [
                'nombre' => 'Arquitectura y Urbanismo',
                'tipo_categoria' => 'Ordenanzas',
            
            ],


               [
                'nombre' => 'Adulto mayor',
                'tipo_categoria' => 'Ordenanzas',
            
            ],


            
               [
                'nombre' => 'Educacion, cultura y deporte',
                'tipo_categoria' => 'Ordenanzas',
            
            ],


                 [
                'nombre' => 'Turismo',
                'tipo_categoria' => 'Ordenanzas',
            
            ],


                 [
                'nombre' => 'Control fiscal',
                'tipo_categoria' => 'Ordenanzas',
            
            ],

            
                 [
                'nombre' => 'Salubridad',
                'tipo_categoria' => 'Ordenanzas',
            
            ],


                  [
                'nombre' => 'Economia',
                'tipo_categoria' => 'Ordenanzas',
            
            ],



                    [
                'nombre' => 'Convivencia',
                'tipo_categoria' => 'Ordenanzas',
            
            ],



                       [
                'nombre' => 'Condecoración e hijos ilustres',
                'tipo_categoria' => 'Ordenanzas',
            
            ],


        ];

        foreach ($categorias as $categoria) {
            CategoriaInstrumento::create($categoria);

        }
    }
}
