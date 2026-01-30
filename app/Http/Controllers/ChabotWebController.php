<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
       
            $groqApiKey = config('services.groq.api_key');
            $groqUrl = config('services.groq.api_url', 'https://api.groq.com/openai/v1');

            if (!$groqApiKey) {
                \Log::error('GROQ_API_KEY no está configurada en config/services.php');
                return response()->json([
                    'success' => false,
                    'message' => 'Error de configuración: API key no disponible'
                ], 500);
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $groqApiKey,
                'Content-Type' => 'application/json',
            ])
            ->timeout(30)
            ->connectTimeout(15)
            ->retry(2, 100)
            ->post($groqUrl . '/chat/completions', [
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
                $data = $response->json();
                $botMessage = $data['choices'][0]['message']['content'] ?? 'No pude generar una respuesta válida de la IA.';
                
                return response()->json([
                    'success' => true,
                    'message' => $botMessage
                ]);

            } else {
                $statusCode = $response->status();
                $errorBody = $response->json();

                $errorMessageDetail = 'Respuesta de error desconocida o no JSON.';
                if (is_array($errorBody) && isset($errorBody['error']['message'])) {
                    $errorMessageDetail = $errorBody['error']['message'];
                } elseif (is_string($response->body())) {
                    $errorMessageDetail = substr($response->body(), 0, 200) . '...';
                }

                $userFriendlyError = "Error de Groq (HTTP $statusCode): $errorMessageDetail";

                \Log::error("Error de API de Groq: Estado $statusCode. Mensaje: " . json_encode($errorBody));

                return response()->json([
                    'success' => false,
                    'message' => $userFriendlyError
                ], 500);
            }

        } catch (RequestException $e) {
            $errorMsg = $e->getMessage();

            \Log::error("RequestException en Groq API: " . $errorMsg);

            if (strpos($errorMsg, 'timed out') !== false) {
                $userMessage = 'Timeout: La API tardó demasiado. Verifica la conexión del servidor.';
            } elseif (strpos($errorMsg, 'Failed to resolve') !== false || strpos($errorMsg, 'getaddrinfo') !== false) {
                $userMessage = 'Error DNS: El servidor no puede resolver api.groq.com';
            } elseif (strpos($errorMsg, 'Connection refused') !== false) {
                $userMessage = 'Conexión rechazada: El firewall podría estar bloqueando la conexión.';
            } else {
                $userMessage = 'Error de conexión: ' . $errorMsg;
            }

            return response()->json([
                'success' => false,
                'message' => $userMessage
            ], 500);

        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            \Log::error("Error inesperado en Chatbot: " . $errorMsg);

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $errorMsg
            ], 500);
        }
    }
}