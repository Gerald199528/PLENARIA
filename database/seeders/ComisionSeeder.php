<?php

namespace Database\Seeders;
use App\Models\Comision;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
   
        $comisiones = [
            ' Legislacion y poder público',
            ' Proteccion social del pueblo',
            ' Ambiente y territorio y urbanismo',
            ' Economia productiva local',
            'Seguridad y convivencia y paz ciudadana',
            ' Contraloria',
            'Hacienda y finanza publica',
            'Servicios publicos',
            'Participacion ciudadana y poder popular',


        ];

        foreach ($comisiones as $comision) {
            Comision::create([
                'nombre' => $comision,
            ]);
        }

    }
}
