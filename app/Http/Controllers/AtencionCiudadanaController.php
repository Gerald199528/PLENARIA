<?php

namespace App\Http\Controllers;

use App\Models\Comision;
use App\Models\CategoriaSolicitud;
use App\Models\Empresa;
use App\Models\Ciudadano;
use App\Models\DerechoDePalabra;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AtencionCiudadanaController extends Controller
{
    /**
     * Mostrar formulario de nueva solicitud
     */
    public function create(): View
    {
        $sesionController = new SesionMunicipalController();

        return view('web.page.participacion_ciudadana.atencion_ciudadana', [
            'empresa' => Empresa::first(),
            'sesionesProximas' => $sesionController->getSesionesDisponibles(),
            'comisiones' => Comision::where('activo', true)
                ->orderBy('nombre', 'asc')
                ->get(['id', 'nombre', 'descripcion']),
            'tiposSolicitud' => CategoriaSolicitud::where('activo', true)
                ->orderBy('nombre', 'asc')
                ->get(['id', 'nombre', 'descripcion']),
        ]);
    }

    /**
     * Guardar nueva solicitud (unificado para ambos tipos)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cedula' => ['required', 'regex:/^[0-9]{8}$/'],
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'email' => 'required|email',
            'telefono_movil' => 'required|regex:/^0?4[0-2][0-9]{8}$/',
            'whatsapp' => 'required|regex:/^0?4[0-2][0-9]{8}$/',
            'tipo_solicitud' => 'required|in:derecho_palabra,atencion',
            'sesion_municipal_id' => 'nullable|required_if:tipo_solicitud,derecho_palabra|exists:sesions_municipal,id',
            'comision_id' => 'nullable|exists:comisions,id',
            'motivo_solicitud' => 'nullable|required_if:tipo_solicitud,derecho_palabra|string|min:10|max:1000',
            'tipo_solicitud_id' => 'nullable|required_if:tipo_solicitud,atencion|exists:tipo_solicitud,id',
            'descripcion' => 'nullable|required_if:tipo_solicitud,atencion|string|min:10|max:2000',
            'acepta_terminos' => 'required|accepted',
        ], [
            'cedula.required' => 'La cédula es requerida',
            'cedula.regex' => 'La cédula debe tener exactamente 8 dígitos',
            'nombre.required' => 'El nombre es requerido',
            'apellido.required' => 'El apellido es requerido',
            'email.required' => 'El correo es requerido',
            'email.email' => 'El correo debe ser válido',
            'telefono_movil.required' => 'El teléfono móvil es requerido',
            'telefono_movil.regex' => 'El teléfono debe comenzar con 04',
            'whatsapp.required' => 'El WhatsApp es requerido',
            'whatsapp.regex' => 'El WhatsApp debe comenzar con 04',
            'tipo_solicitud.required' => 'Debe seleccionar un tipo de solicitud',
            'sesion_municipal_id.required_if' => 'Debe seleccionar una sesión municipal',
            'motivo_solicitud.required_if' => 'El motivo de solicitud es requerido',
            'motivo_solicitud.min' => 'El motivo debe tener al menos 10 caracteres',
            'tipo_solicitud_id.required_if' => 'Debe seleccionar un tipo de solicitud',
            'descripcion.required_if' => 'La descripción es requerida',
            'descripcion.min' => 'La descripción debe tener al menos 10 caracteres',
            'acepta_terminos.required' => 'Debe aceptar los términos y condiciones',
            'acepta_terminos.accepted' => 'Debe aceptar los términos y condiciones',
        ]);

        // Normalizar cédula (agregar V-)
        $cedulaNormalizada = 'V' . $validated['cedula'];

        // Normalizar teléfonos
        $telefonoMovil = $this->normalizarTelefono($validated['telefono_movil']);
        $whatsapp = $this->normalizarTelefono($validated['whatsapp']);

        // Crear o buscar ciudadano
        $ciudadano = Ciudadano::firstOrCreate(
            ['cedula' => $cedulaNormalizada],
            [
                'nombre' => $validated['nombre'],
                'apellido' => $validated['apellido'],
                'email' => $validated['email'],
                'telefono_movil' => $telefonoMovil,
                'whatsapp' => $whatsapp,
            ]
        );

        // Guardar según tipo de solicitud
        if ($validated['tipo_solicitud'] === 'derecho_palabra') {
            return $this->guardarDerechoPalabra($ciudadano, $validated);
        } else {
            return $this->guardarAtencionCiudadana($ciudadano, $validated);
        }
    }

    /**
     * Guardar solicitud de Derecho de Palabra
     */
    private function guardarDerechoPalabra(Ciudadano $ciudadano, array $validated)
    {
        DerechoDePalabra::create([
            'ciudadano_id' => $ciudadano->id,
            'sesion_municipal_id' => $validated['sesion_municipal_id'],
            'comision_id' => $validated['comision_id'] ?? null,
            'motivo_solicitud' => $validated['motivo_solicitud'],
            'estado' => 'pendiente',
            'acepta_terminos' => true,
        ]);

        return redirect(route('home') . '#participacion')
            ->with('success', 'Estimado ciudadano, su solicitud de derecho de palabra fue enviada exitosamente. Pronto nos comunicaremos con usted vía correo electrónico, llamada o WhatsApp.');
    }

    /**
     * Guardar solicitud de Atención Ciudadana
     */
    private function guardarAtencionCiudadana(Ciudadano $ciudadano, array $validated)
    {
        Solicitud::create([
            'ciudadano_id' => $ciudadano->id,
            'tipo_solicitud_id' => $validated['tipo_solicitud_id'],
            'descripcion' => $validated['descripcion'],
            'estado' => 'pendiente',
            'acepta_terminos' => true,
        ]);

        return redirect(route('home') . '#participacion')
            ->with('success', 'Estimado ciudadano, su solicitud de atención fue enviada exitosamente. Pronto nos comunicaremos con usted vía correo electrónico, llamada o WhatsApp.');
    }

    /**
     * Normalizar números de teléfono a formato +58
     */
    private function normalizarTelefono(string $telefono): string
    {
        $telefono = ltrim($telefono, '0');
        return '+58' . $telefono;
    }
}
