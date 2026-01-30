<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            SettingsSeeder::class,
            DefaultUserSeeder::class,
            EstatusSeeder::class,
            CategoriaInstrumentoSeeder::class,
            ComisionSeeder::class,
             EstadoSeeder::class,
            MunicipioSeeder::class,
            ParroquiaSeeder::class,
            AtencionCiudadanaSeeder::class,
        ]);

    }
}
