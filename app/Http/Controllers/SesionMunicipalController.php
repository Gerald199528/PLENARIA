<?php

namespace App\Http\Controllers;

use App\Models\SesionMunicipal;
use App\Models\Noticia;
use Illuminate\View\View;

class SesionMunicipalController extends Controller
{
    /**
     * Obtener sesiones próximas y abiertas
     */
    public function getSesionesProximas(): array
    {
        return SesionMunicipal::whereIn('estado', ['proxima', 'abierta'])
            ->orderBy('fecha_hora', 'asc')
            ->limit(4)
            ->get()
            ->map(function ($sesion) {
                return [
                    'id' => $sesion->id,
                    'titulo' => $sesion->titulo,
                    'descripcion' => $sesion->descripcion,
                    'fecha_hora' => $sesion->fecha_hora
                        ->timezone('America/Caracas')
                        ->format('d \d\e F, h:i A'),
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

    
    public function getEstadisticas(): array
    {
        $totalSolicitudes = \DB::table('derecho_palabra')->count();
        $solicitudesAprobadas = \DB::table('derecho_palabra')
            ->where('estado', 'aprobada')
            ->count();
        $ciudadanosUnicos = \DB::table('derecho_palabra')
            ->distinct('cedula')
            ->count();

        $tasa = $totalSolicitudes > 0 
            ? round(($solicitudesAprobadas / $totalSolicitudes) * 100)
            : 0;

        return [
            'ciudadanos' => $ciudadanosUnicos,
            'solicitudes' => $totalSolicitudes,
            'aprobadas' => $solicitudesAprobadas,
            'tasa' => $tasa,
        ];
    }

    /**
     * Obtener todas las sesiones
     */
     public function index(): View
    {
        $sesiones = SesionMunicipal::with('categoria')
            ->orderBy('fecha_hora', 'asc')
            ->get();

        return view('web.page.participacion_ciudadana.index', compact('sesiones'));
    }

/**
 * Mostrar estadísticas de participación ciudadana
 */
public function show(): View
{
    $estadisticas = $this->getEstadisticas();
    
    return view('web.page.participacion_ciudadana.show', compact('estadisticas'));
}

   
}