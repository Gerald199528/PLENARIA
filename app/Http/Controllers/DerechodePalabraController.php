<?php

namespace App\Http\Controllers;

use App\Models\DerechoDePalabra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DerechodePalabraController extends Controller
{
    /**
     * Obtiene las estadísticas de derecho de palabra calculadas dinámicamente
     */
    public function getEstadisticas()
    {
        // Contar total de solicitudes
        $totalSolicitudes = DB::table('derecho_palabra')->count();
        
        // Si no hay registros, retornar array vacío
        if ($totalSolicitudes == 0) {
            return [];
        }
        
        // Contar total de ciudadanos únicos (por cédula)
        $totalCiudadanos = DB::table('derecho_palabra')
            ->distinct()
            ->count('cedula');
        
        // Contar solicitudes aprobadas
        $solicitudesAprobadas = DB::table('derecho_palabra')
            ->where('estado', 'aprobada')
            ->count();
        
        // Calcular tasa de aprobación
        $tasaAprobacion = round(($solicitudesAprobadas / $totalSolicitudes) * 100, 2);
        
        return [
            'ciudadanos' => $totalCiudadanos,
            'solicitudes' => $totalSolicitudes,
            'aprobadas' => $solicitudesAprobadas,
            'tasa' => $tasaAprobacion,
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación completa con mensajes personalizados
        $validated = $request->validate([
            'cedula' => ['required', 'regex:/^[0-9]{8}$/', 'unique:derecho_palabra,cedula,NULL,id,cedula,V'],
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:derecho_palabra,email',
            'telefono_movil' => 'required|digits_between:10,11|regex:/^(0?4[0-9]{9})$/',
            'whatsapp' => 'required|digits_between:10,11|regex:/^(0?4[0-9]{9})$/',
            'sesion_municipal_id' => 'required|exists:sesions_municipal,id',
            'motivo_solicitud' => 'required|string|min:10',
            'acepta_terminos' => 'required|accepted',
        ], [
            'cedula.required' => 'La cédula es requerida',
            'cedula.regex' => 'La cédula debe tener exactamente 8 dígitos',
            'cedula.unique' => 'Esta cédula ya tiene una solicitud registrada.',
            'nombre.required' => 'El nombre es requerido',
            'apellido.required' => 'El apellido es requerido',
            'email.required' => 'El correo es requerido',
            'email.email' => 'El correo debe ser válido',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'telefono_movil.required' => 'El teléfono móvil es requerido',
            'telefono_movil.digits_between' => 'El teléfono debe tener 10 u 11 dígitos',
            'telefono_movil.regex' => 'El teléfono debe comenzar con 0414 o 414 seguido de dígitos',
            'whatsapp.required' => 'El WhatsApp es requerido',
            'whatsapp.digits_between' => 'El WhatsApp debe tener 10 u 11 dígitos',
            'whatsapp.regex' => 'El WhatsApp debe comenzar con 0414 o 414 seguido de dígitos',
            'sesion_municipal_id.required' => 'Debe seleccionar una sesión municipal',
            'sesion_municipal_id.exists' => 'La sesión seleccionada no existe.',
            'motivo_solicitud.required' => 'El motivo de solicitud es requerido',
            'motivo_solicitud.min' => 'El motivo debe tener al menos 10 caracteres.',
            'acepta_terminos.required' => 'Debe aceptar los términos y condiciones.',
            'acepta_terminos.accepted' => 'Debe aceptar los términos y condiciones.',
        ]);

        // Normalizar cédula (agregar V-)
        $cedula = 'V' . $validated['cedula'];
        
        // Verificar si la cédula normalizada ya existe
        if (DerechoDePalabra::where('cedula', $cedula)->exists()) {
            return redirect()->back()
                ->withErrors(['cedula' => 'Esta cédula ya tiene una solicitud registrada.'])
                ->withInput();
        }
        
        // Normalizar teléfonos (eliminar 0 al inicio si existe, luego agregar +58)
        $telefonoMovil = ltrim($validated['telefono_movil'], '0');
        $telefonoMovil = '+58' . $telefonoMovil;
        
        $whatsapp = ltrim($validated['whatsapp'], '0');
        $whatsapp = '+58' . $whatsapp;

        // Crear la solicitud
        DerechoDePalabra::create([
            'cedula' => $cedula,
            'nombre' => $validated['nombre'],
            'apellido' => $validated['apellido'],
            'email' => $validated['email'],
            'telefono_movil' => $telefonoMovil,
            'whatsapp' => $whatsapp,
            'sesion_municipal_id' => $validated['sesion_municipal_id'],
            'motivo_solicitud' => $validated['motivo_solicitud'],
            'estado' => 'pendiente',
            'acepta_terminos' => true,
        ]);

        return redirect(route('home') . '#participacion')
            ->with('success', 'Estimado ciudadano, su solicitud fue enviada exitosamente. Pronto nos comunicaremos con usted vía correo electrónico, llamada o WhatsApp.');
    }
}