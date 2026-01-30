<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoSolicitud;

class AtencionCiudadanaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. TIPO: Derecho de Palabra
        TipoSolicitud::create([
            'nombre' => 'Derecho de Palabra',
            'descripcion' => 'Solicitud para participar en sesiones municipales con derecho de palabra',
            'activo' => true,
        ]);

        // 2. TIPO: Atención Inmediata - Servicios Públicos
        TipoSolicitud::create([
            'nombre' => 'Agua Potable',
            'descripcion' => 'Problemas con el suministro de agua, tuberías rotas, falta de servicio',
            'activo' => true,
        ]);

        TipoSolicitud::create([
            'nombre' => 'Electricidad',
            'descripcion' => 'Fallas eléctricas, alumbrado público, postes caídos',
            'activo' => true,
        ]);

        TipoSolicitud::create([
            'nombre' => 'Aseo Urbano',
            'descripcion' => 'Recolección de basura, limpieza de calles, contenedores',
            'activo' => true,
        ]);

        TipoSolicitud::create([
            'nombre' => 'Aguas Servidas',
            'descripcion' => 'Cloacas tapadas, derrames, problemas de alcantarillado',
            'activo' => true,
        ]);

        // INFRAESTRUCTURA Y VIALIDAD
        TipoSolicitud::create([
            'nombre' => 'Vías y Calles',
            'descripcion' => 'Baches, pavimentación, señalización vial',
            'activo' => true,
        ]);

        TipoSolicitud::create([
            'nombre' => 'Aceras y Brocales',
            'descripcion' => 'Reparación de aceras, brocales rotos o inexistentes',
            'activo' => true,
        ]);

        TipoSolicitud::create([
            'nombre' => 'Puentes y Obras Públicas',
            'descripcion' => 'Mantenimiento de puentes, obras civiles municipales',
            'activo' => true,
        ]);

        // TRANSPORTE
        TipoSolicitud::create([
            'nombre' => 'Transporte Público',
            'descripcion' => 'Rutas de autobuses, paradas, frecuencia del servicio',
            'activo' => true,
        ]);

        TipoSolicitud::create([
            'nombre' => 'Tránsito y Movilidad',
            'descripcion' => 'Semáforos, congestión vehicular, estacionamientos',
            'activo' => true,
        ]);

        // SEGURIDAD
        TipoSolicitud::create([
            'nombre' => 'Seguridad Ciudadana',
            'descripcion' => 'Iluminación, vigilancia, zonas inseguras',
            'activo' => true,
        ]);

        TipoSolicitud::create([
            'nombre' => 'Protección Civil',
            'descripcion' => 'Emergencias, riesgos, desastres naturales',
            'activo' => true,
        ]);

        // AMBIENTE Y ESPACIOS PÚBLICOS
        TipoSolicitud::create([
            'nombre' => 'Parques y Plazas',
            'descripcion' => 'Mantenimiento de áreas verdes, juegos infantiles',
            'activo' => true,
        ]);

        TipoSolicitud::create([
            'nombre' => 'Arborización',
            'descripcion' => 'Poda de árboles, siembra, tala de árboles peligrosos',
            'activo' => true,
        ]);

        TipoSolicitud::create([
            'nombre' => 'Contaminación Ambiental',
            'descripcion' => 'Ruido, olores, contaminación visual o del aire',
            'activo' => true,
        ]);

        TipoSolicitud::create([
            'nombre' => 'Control de Plagas',
            'descripcion' => 'Fumigación, control de roedores, mosquitos',
            'activo' => true,
        ]);

        // CONSTRUCCIÓN Y URBANISMO
        TipoSolicitud::create([
            'nombre' => 'Permisos de Construcción',
            'descripcion' => 'Solicitud de permisos, consultas sobre normativas',
            'activo' => true,
        ]);

        TipoSolicitud::create([
            'nombre' => 'Construcciones Ilegales',
            'descripcion' => 'Denuncias de obras sin permiso, invasiones',
            'activo' => true,
        ]);

        TipoSolicitud::create([
            'nombre' => 'Catastro Municipal',
            'descripcion' => 'Actualización de datos catastrales, linderos',
            'activo' => true,
        ]);

        // SERVICIOS SOCIALES
        TipoSolicitud::create([
            'nombre' => 'Salud Comunitaria',
            'descripcion' => 'Ambulatorios, jornadas de salud, vacunación',
            'activo' => true,
        ]);

        TipoSolicitud::create([
            'nombre' => 'Educación y Cultura',
            'descripcion' => 'Escuelas, bibliotecas, actividades culturales',
            'activo' => true,
        ]);

        TipoSolicitud::create([
            'nombre' => 'Deporte y Recreación',
            'descripcion' => 'Canchas deportivas, programas recreativos',
            'activo' => true,
        ]);

        TipoSolicitud::create([
            'nombre' => 'Atención Social',
            'descripcion' => 'Asistencia a personas vulnerables, adultos mayores',
            'activo' => true,
        ]);

        // COMERCIO Y ECONOMÍA
        TipoSolicitud::create([
            'nombre' => 'Mercados y Ferias',
            'descripcion' => 'Permisos para ventas, ferias, mercados municipales',
            'activo' => true,
        ]);

        TipoSolicitud::create([
            'nombre' => 'Licencias Comerciales',
            'descripcion' => 'Patentes, permisos de funcionamiento',
            'activo' => true,
        ]);

        TipoSolicitud::create([
            'nombre' => 'Vendedores Ambulantes',
            'descripcion' => 'Regulación de ventas informales en espacios públicos',
            'activo' => true,
        ]);

        // ANIMALES Y MASCOTAS
        TipoSolicitud::create([
            'nombre' => 'Control de Animales',
            'descripcion' => 'Perros callejeros, animales peligrosos, maltrato animal',
            'activo' => true,
        ]);

        // TRIBUTACIÓN
        TipoSolicitud::create([
            'nombre' => 'Impuestos Municipales',
            'descripcion' => 'Consultas sobre pagos, exoneraciones, actualizaciones',
            'activo' => true,
        ]);

        // OTROS
        TipoSolicitud::create([
            'nombre' => 'Quejas y Reclamos',
            'descripcion' => 'Quejas sobre servicios municipales o funcionarios',
            'activo' => true,
        ]);

        TipoSolicitud::create([
            'nombre' => 'Sugerencias',
            'descripcion' => 'Sugerencias para mejorar servicios municipales',
            'activo' => true,
        ]);

        TipoSolicitud::create([
            'nombre' => 'Información General',
            'descripcion' => 'Solicitud de información sobre trámites y servicios',
            'activo' => true,
        ]);

        TipoSolicitud::create([
            'nombre' => 'Otros',
            'descripcion' => 'Solicitudes que no encajan en las categorías anteriores',
            'activo' => true,
        ]);
    }
}
