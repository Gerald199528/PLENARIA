<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ordenanza;
use App\Models\Gaceta;
use App\Models\Acuerdo;
use App\Models\Empresa;
use Illuminate\Support\Facades\Storage;

class InstrumentosLegalesController extends Controller
{
    public function index(Request $request)
    {
        // 🔹 AGREGAR ESTO
        $empresa = Empresa::first();

        // Obtener filtros
        $tipo = $request->input('tipo');
        $anio = $request->input('anio');
        $search = $request->input('search');

        $documentos = collect();

        // ===== ORDENANZAS =====
        if (is_null($tipo) || $tipo === '' || $tipo === 'ordenanza') {
            $query = Ordenanza::query();
            if ($anio) $query->whereYear('fecha_aprobacion', (int)$anio);
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                      ->orWhere('observacion', 'like', "%{$search}%");
                });
            }
            $resultados = $query->with('categoria')->get();
            foreach($resultados as $doc){
                $doc->tipo_documento = 'Ordenanza';
                $doc->color_badge = 'blue';
                $documentos->push($doc);
            }
        }

        // ===== GACETAS =====
        if (is_null($tipo) || $tipo === '' || $tipo === 'gaceta') {
            $query = Gaceta::query();
            if ($anio) $query->whereYear('fecha_aprobacion', (int)$anio);
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                      ->orWhere('observacion', 'like', "%{$search}%");
                });
            }
            $resultados = $query->get();
            foreach($resultados as $doc){
                $doc->tipo_documento = 'Gaceta';
                $doc->color_badge = 'purple';
                if (!isset($doc->categoria) || !is_object($doc->categoria)) {
                    $doc->categoria = (object)['nombre' => $doc->categoria ?? 'Gaceta Municipal'];
                }
                $documentos->push($doc);
            }
        }

        // ===== ACUERDOS =====
        if (is_null($tipo) || $tipo === '' || $tipo === 'acuerdo') {
            $query = Acuerdo::query();
            if ($anio) $query->whereYear('fecha_aprobacion', (int)$anio);
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                      ->orWhere('observacion', 'like', "%{$search}%");
                });
            }
            $resultados = $query->with('categoria')->get();
            foreach($resultados as $doc){
                $doc->tipo_documento = 'Acuerdo';
                $doc->color_badge = 'orange';
                $documentos->push($doc);
            }
        }

        // Ordenar por fecha más reciente
        $documentos = $documentos->sortByDesc(fn($doc) => $doc->fecha_aprobacion ?? now())->values();

        // 🔹 Limitar a 3 registros por categoría SOLO si NO hay filtro de tipo
        if (!$tipo) {
            $documentos = $documentos->groupBy('tipo_documento')
                                     ->map(fn($grupo) => $grupo->take(3))
                                     ->flatten(1);
        }

        $tipos = [
            ['value' => 'ordenanza', 'label' => 'Ordenanzas'],
            ['value' => 'gaceta', 'label' => 'Gacetas'],
            ['value' => 'acuerdo', 'label' => 'Acuerdos'],
        ];

        return view('web.page.instrumentos_legales.index', [
            'documentos' => $documentos,
            'tipos' => $tipos,
            'total' => $documentos->count(),
            'filtros' => ['tipo' => $tipo, 'anio' => $anio, 'search' => $search],
            'empresa' => $empresa,  // 🔹 AGREGAR ESTO
        ]);
    }
}
