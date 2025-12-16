<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
// Asegúrate de que esta clase de Excepción global está disponible
use Illuminate\Http\Client\RequestException; 

class ChabotWebController extends Controller
{
    public function index()
    {
        $faviconPath = Setting::get('logo_icon');
        $faviconUrl = $faviconPath ? asset('storage/' . $faviconPath) : asset('default-favicon.ico');

        $logoPath = Setting::get('logo_horizontal_background_solid');
        $logoUrl = $logoPath ? asset('storage/' . $logoPath) : null;

        return view('web.page.chabot_web.index', [
            'faviconUrl' => $faviconUrl,
            'logoUrl' => $logoUrl
        ]);
    }

    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        try {
            $response = Http::withHeaders([
                // Doble chequeo aquí: ¿se está cargando GROQ_API_KEY?
                'Authorization' => 'Bearer ' . env('GROQ_API_KEY'), 
                'Content-Type' => 'application/json',
            ])->post(env('GROQ_API_URL') . '/chat/completions', [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Eres un asistente de IA amable y profesional para PLENARIA. Responde en español de manera concisa y útil.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $validated['message']
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 1024,
            ]);

            if ($response->successful()) {
                // Éxito (código 2xx)
                $data = $response->json();
                $botMessage = $data['choices'][0]['message']['content'] ?? 'No pude generar una respuesta válida de la IA.';
                
                return response()->json([
                    'success' => true,
                    'message' => $botMessage
                ]);

            } else {
                // Error de Groq (código 4xx o 5xx)
                $statusCode = $response->status();
                $errorBody = $response->json();

                // Intentamos extraer el mensaje de error detallado de Groq
                $errorMessageDetail = 'Respuesta de error desconocida o no JSON.';
                if (is_array($errorBody) && isset($errorBody['error']['message'])) {
                    $errorMessageDetail = $errorBody['error']['message'];
                } elseif (is_string($response->body())) {
                     // Si no es JSON, capturamos el cuerpo como texto (útil para errores internos del servidor)
                     $errorMessageDetail = substr($response->body(), 0, 200) . '...';
                }

                // Generamos un mensaje claro para enviar al frontend
                $userFriendlyError = "Error de Groq (HTTP $statusCode): $errorMessageDetail";

                // Opcional: Loguear el error completo para el desarrollador
                \Log::error("Error de API de Groq: Estado $statusCode. Mensaje: " . json_encode($errorBody));

                return response()->json([
                    'success' => false,
                    'message' => $userFriendlyError // Enviamos el error detallado al frontend
                ], 500);
            }
        } catch (\Exception $e) {
            // Error de conexión (DNS, timeout) o excepción de PHP
            return response()->json([
                'success' => false,
                'message' => 'Error de conexión o servidor: ' . $e->getMessage()
            ], 500);
        }
    }
}