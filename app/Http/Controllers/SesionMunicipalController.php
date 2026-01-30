<?php

namespace App\Http\Controllers;

use App\Models\SesionMunicipal;
use App\Models\Solicitud;
use App\Models\Empresa;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SesionMunicipalController extends Controller
{
    /**
     * Obtener sesiones disponibles (próximas y abiertas)
     */
    public function getSesionesDisponibles(): array
    {
        return SesionMunicipal::whereIn('estado', ['proxima', 'abierta'])
            ->where('fecha_hora', '>=', now())
            ->orderBy('fecha_hora', 'asc')
            ->get()
            ->map(function ($sesion) {
                return [
                    'id' => $sesion->id,
                    'titulo' => $sesion->titulo,
                    'descripcion' => $sesion->descripcion,
                    'fecha_hora' => $sesion->fecha_hora,
                    'fecha_formateada' => $sesion->fecha_hora
                        ->timezone('America/Caracas')
                        ->format('d/m/Y H:i'),
                    'estado' => $sesion->estado,
                    'estado_badge' => match($sesion->estado) {
                        'proxima' => [
                            'bg' => 'bg-blue-100',
                            'text' => 'text-blue-800',
                            'label' => 'Próxima'
                        ],
                        'abierta' => [
                            'bg' => 'bg-green-100',
                            'text' => 'text-green-800',
                            'label' => 'Abierta'
                        ],
                        default => [
                            'bg' => 'bg-gray-100',
                            'text' => 'text-gray-800',
                            'label' => $sesion->estado
                        ]
                    }
                ];
            })
            ->toArray();
    }

    /**
     * Obtener todas las sesiones
     */
    public function index(): View
    {
        return view('web.page.participacion_ciudadana.index', [
            'empresa' => Empresa::first(),
            'sesiones' => SesionMunicipal::with('categoria')
                ->orderBy('fecha_hora', 'asc')
                ->get(),
        ]);
    }

    /**
     * Mostrar estadísticas de participación ciudadana
     */
    public function show(): View
    {
        return view('web.page.participacion_ciudadana.show', [
            'empresa' => Empresa::first(),
            'estadisticas' => $this->getEstadisticas(),
            'atencionesRealizadas' => Solicitud::with(['ciudadano', 'tipoSolicitud'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(),
        ]);
    }

    /**
     * Obtener estadísticas de solicitudes (Derecho de Palabra + Atención Ciudadana)
     */
    public function getEstadisticas(): array
    {
        // Estadísticas de Derecho de Palabra
        $totalDerechoPalabra = DB::table('derecho_palabra')->count();
        $derechoPalabraAprobadas = DB::table('derecho_palabra')
            ->where('estado', 'aprobada')
            ->count();
        $derechoPalabraPendientes = DB::table('derecho_palabra')
            ->where('estado', 'pendiente')
            ->count();
        $ciudadanosUnicos = DB::table('derecho_palabra')
            ->distinct('ciudadano_id')
            ->count('ciudadano_id');

        // Estadísticas de Atención Ciudadana
        $totalSolicitudes = DB::table('solicitudes')->count();
        $solicitudesAprobadas = DB::table('solicitudes')
            ->where('estado', 'aprobado')
            ->count();
        $solicitudesPendientes = DB::table('solicitudes')
            ->where('estado', 'pendiente')
            ->count();
        $solicitudesRechazadas = DB::table('solicitudes')
            ->where('estado', 'rechazado')
            ->count();

        // Tasas de aprobación
        $tasaDerechoPalabra = $totalDerechoPalabra > 0
            ? round(($derechoPalabraAprobadas / $totalDerechoPalabra) * 100)
            : 0;

        $tasaSolicitudes = $totalSolicitudes > 0
            ? round(($solicitudesAprobadas / $totalSolicitudes) * 100)
            : 0;

        // Calcular tasa total
        $totalSolicitudesGeneral = $totalDerechoPalabra + $totalSolicitudes;
        $totalAprobadas = $derechoPalabraAprobadas + $solicitudesAprobadas;
        $tasaTotal = $totalSolicitudesGeneral > 0
            ? round(($totalAprobadas / $totalSolicitudesGeneral) * 100)
            : 0;

        return [
            // Derecho de Palabra
            'ciudadanos' => $ciudadanosUnicos,
            'derechoPalabra' => [
                'total' => $totalDerechoPalabra,
                'aprobadas' => $derechoPalabraAprobadas,
                'pendientes' => $derechoPalabraPendientes,
                'tasa' => $tasaDerechoPalabra,
            ],
            // Atención Ciudadana
            'atencionCiudadana' => [
                'total' => $totalSolicitudes,
                'aprobadas' => $solicitudesAprobadas,
                'pendientes' => $solicitudesPendientes,
                'rechazadas' => $solicitudesRechazadas,
                'tasa' => $tasaSolicitudes,
            ],
            // Totales
            'solicitudes' => $totalSolicitudesGeneral,
            'aprobadas' => $totalAprobadas,
            'tasa' => $tasaTotal,
        ];
    }
}
