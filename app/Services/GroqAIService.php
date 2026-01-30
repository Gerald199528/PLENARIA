<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqAIService
{
    protected ?string $apiKey;
    protected string $model;
    protected string $baseUrl;
    protected float $temperature;
    protected int $maxTokens;
    protected int $timeout;

    public function __construct()
    {
        $this->apiKey = env('GROQ_API_KEY');
        $this->model = env('GROQ_MODEL', 'llama-3.3-70b-versatile');
        $this->baseUrl = env('GROQ_API_URL', 'https://api.groq.com/openai/v1');
        $this->temperature = (float) env('GROQ_TEMPERATURE', 0.7);
        $this->maxTokens = (int) env('GROQ_MAX_TOKENS', 8000);
        $this->timeout = (int) env('GROQ_TIMEOUT', 60);
    }

    /**
     * Verifica si el servicio está configurado correctamente
     */
    protected function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Llama a la API de Groq (compatible con OpenAI)
     * Método PÚBLICO para que CitizenMessageService pueda usarlo
     */
    public function llamarGroqAPI(string $prompt): array
    {
        try {
            // Validar que está configurado
            if (!$this->isConfigured()) {
                Log::warning('Groq API no configurada. Variable GROQ_API_KEY no encontrada en .env');
                return [
                    'success' => false,
                    'error' => 'Groq API no configurada',
                ];
            }

            $url = "{$this->baseUrl}/chat/completions";

            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => "Bearer {$this->apiKey}",
                ])
                ->post($url, [
                    'model' => $this->model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Eres un asistente profesional de la Administración Pública Municipal. Genera mensajes cordiales y profesionales para WhatsApp sobre participación ciudadana.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'temperature' => $this->temperature,
                    'max_tokens' => $this->maxTokens,
                    'top_p' => (float) env('GROQ_TOP_P', 0.95),
                    'stream' => false
                ]);

            if (!$response->successful()) {
                Log::error('Error en respuesta de Groq API', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return [
                    'success' => false,
                    'error' => 'Error en la API de Groq: ' . $response->body(),
                ];
            }

            $data = $response->json();

            // Extraer el contenido generado (formato compatible con OpenAI)
            $contenido = $data['choices'][0]['message']['content'] ?? null;

            if (!$contenido) {
                return [
                    'success' => false,
                    'error' => 'No se pudo extraer contenido de la respuesta de Groq',
                ];
            }

            // Calcular tokens usados
            $tokensUsados = $data['usage']['total_tokens'] ?? 0;

            return [
                'success' => true,
                'contenido' => $contenido,
                'tokens_usados' => $tokensUsados,
                'prompt_tokens' => $data['usage']['prompt_tokens'] ?? 0,
                'completion_tokens' => $data['usage']['completion_tokens'] ?? 0,
            ];

        } catch (\Exception $e) {
            Log::error('Excepción al llamar a Groq API', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Obtiene el nombre de la empresa registrada, default: Plenaria
     */
    protected function obtenerNombreEmpresa(): string
    {
        try {
            $empresa = \App\Models\Empresa::first();
            if ($empresa && !empty($empresa->razon_social)) {
                return $empresa->razon_social;
            }
        } catch (\Exception $e) {
            Log::warning('Error al obtener nombre de empresa', ['error' => $e->getMessage()]);
        }

        return 'Plenaria';
    }

    /**
     * Genera un mensaje de confirmación para Derecho de Palabra
     *
     * @param array $datosDerechoPalabra Información de la solicitud
     * @return array
     */
    public function generarMensajeConfirmacionDerechoPalabra(array $datosDerechoPalabra): array
    {
        if (!$this->isConfigured()) {
            Log::warning('Groq API no configurada');
            return [
                'success' => true,
                'mensaje' => $this->mensajePredeterminadoDerechoPalabra($datosDerechoPalabra),
                'es_ia' => false,
                'motivo' => 'API no configurada',
            ];
        }

        try {
            $prompt = $this->construirPromptDerechoPalabra($datosDerechoPalabra);
            $response = $this->llamarGroqAPI($prompt);

            if (!$response['success']) {
                return [
                    'success' => true,
                    'mensaje' => $this->mensajePredeterminadoDerechoPalabra($datosDerechoPalabra),
                    'es_ia' => false,
                    'motivo' => 'Error en API',
                ];
            }

            $mensaje = trim($response['contenido']);

            if (strlen($mensaje) > 1000) {
                return [
                    'success' => true,
                    'mensaje' => $this->mensajePredeterminadoDerechoPalabra($datosDerechoPalabra),
                    'es_ia' => false,
                    'motivo' => 'Mensaje muy largo',
                ];
            }

            return [
                'success' => true,
                'mensaje' => $mensaje,
                'tokens_usados' => $response['tokens_usados'] ?? 0,
                'es_ia' => true,
            ];

        } catch (\Exception $e) {
            Log::error('Error generando mensaje derecho de palabra', ['error' => $e->getMessage()]);
            return [
                'success' => true,
                'mensaje' => $this->mensajePredeterminadoDerechoPalabra($datosDerechoPalabra),
                'es_ia' => false,
                'motivo' => 'Excepción: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Construye el prompt para mensaje de Derecho de Palabra
     */
    protected function construirPromptDerechoPalabra(array $datos): string
    {
        $nombreCiudadano = $datos['nombre'] ?? 'Ciudadano';
        $sesion = $datos['sesion'] ?? 'N/A';
        $comision = $datos['comision'] ?? null;
        $nombreEmpresa = $this->obtenerNombreEmpresa();

        $prompt = "Eres un asistente profesional de la Administración Pública Municipal de {$nombreEmpresa}. ";
        $prompt .= "Genera un mensaje de WhatsApp cordial y profesional para confirmar que una solicitud de derecho de palabra ha sido recibida.\n\n";

        $prompt .= "## INFORMACIÓN DE LA SOLICITUD\n";
        $prompt .= "- Ciudadano: {$nombreCiudadano}\n";
        $prompt .= "- Sesión Municipal: {$sesion}\n";

        if ($comision) {
            $prompt .= "- Comisión: {$comision}\n";
        }

        $prompt .= "\n## INSTRUCCIONES\n";
        $prompt .= "1. El mensaje debe ser profesional y cordial\n";
        $prompt .= "2. Confirma que la solicitud de derecho de palabra ha sido recibida exitosamente\n";
        $prompt .= "3. Menciona la sesión municipal a la que se refiere\n";
        $prompt .= "4. Indica que pronto se comunicarán para confirmar la asignación\n";
        $prompt .= "5. Agradece la participación ciudadana\n";
        $prompt .= "6. Ofrece disponibilidad para consultas\n";
        $prompt .= "7. Usa emojis de forma moderada (máximo 2-3)\n";
        $prompt .= "8. El mensaje debe ser CORTO (máximo 600 caracteres)\n";
        $prompt .= "9. Usa formato WhatsApp: *negritas*, _cursivas_\n";
        $prompt .= "10. NO uses HTML ni código\n";
        $prompt .= "11. El tono debe ser de la administración pública\n\n";
        $prompt .= "Genera SOLO el mensaje, sin introducción ni explicación adicional.";

        return $prompt;
    }

    /**
     * Mensaje predeterminado para Derecho de Palabra
     */
    protected function mensajePredeterminadoDerechoPalabra(array $datos): string
    {
        $nombreCiudadano = $datos['nombre'] ?? 'Ciudadano';
        $sesion = $datos['sesion'] ?? 'N/A';
        $nombreEmpresa = $this->obtenerNombreEmpresa();

        $mensaje = "✅ *Solicitud de Derecho de Palabra Recibida*\n\n";
        $mensaje .= "Estimado/a *{$nombreCiudadano}*,\n\n";
        $mensaje .= "Le confirmamos que su solicitud de derecho de palabra ha sido recibida exitosamente por {$nombreEmpresa}.\n\n";
        $mensaje .= "📋 *Sesión:* {$sesion}\n\n";
        $mensaje .= "Pronto nos comunicaremos con usted para confirmar su participación. Agradecemos su interés en participar activamente en la vida municipal.\n\n";
        $mensaje .= "Si tiene alguna consulta, estamos a su disposición.";

        return $mensaje;
    }

    /**
     * Genera un mensaje de confirmación para Atención Ciudadana
     *
     * @param array $datosAtencion Información de la solicitud
     * @return array
     */
    public function generarMensajeConfirmacionAtencionCiudadana(array $datosAtencion): array
    {
        if (!$this->isConfigured()) {
            Log::warning('Groq API no configurada');
            return [
                'success' => true,
                'mensaje' => $this->mensajePredeterminadoAtencionCiudadana($datosAtencion),
                'es_ia' => false,
                'motivo' => 'API no configurada',
            ];
        }

        try {
            $prompt = $this->construirPromptAtencionCiudadana($datosAtencion);
            $response = $this->llamarGroqAPI($prompt);

            if (!$response['success']) {
                return [
                    'success' => true,
                    'mensaje' => $this->mensajePredeterminadoAtencionCiudadana($datosAtencion),
                    'es_ia' => false,
                    'motivo' => 'Error en API',
                ];
            }

            $mensaje = trim($response['contenido']);

            if (strlen($mensaje) > 1000) {
                return [
                    'success' => true,
                    'mensaje' => $this->mensajePredeterminadoAtencionCiudadana($datosAtencion),
                    'es_ia' => false,
                    'motivo' => 'Mensaje muy largo',
                ];
            }

            return [
                'success' => true,
                'mensaje' => $mensaje,
                'tokens_usados' => $response['tokens_usados'] ?? 0,
                'es_ia' => true,
            ];

        } catch (\Exception $e) {
            Log::error('Error generando mensaje atención ciudadana', ['error' => $e->getMessage()]);
            return [
                'success' => true,
                'mensaje' => $this->mensajePredeterminadoAtencionCiudadana($datosAtencion),
                'es_ia' => false,
                'motivo' => 'Excepción: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Construye el prompt para mensaje de Atención Ciudadana
     */
    protected function construirPromptAtencionCiudadana(array $datos): string
    {
        $nombreCiudadano = $datos['nombre'] ?? 'Ciudadano';
        $tipoSolicitud = $datos['tipo_solicitud'] ?? 'N/A';
        $nombreEmpresa = $this->obtenerNombreEmpresa();

        $prompt = "Eres un asistente profesional de la Administración Pública Municipal de {$nombreEmpresa}. ";
        $prompt .= "Genera un mensaje de WhatsApp cordial y profesional para confirmar que una solicitud de atención ciudadana ha sido recibida.\n\n";

        $prompt .= "## INFORMACIÓN DE LA SOLICITUD\n";
        $prompt .= "- Ciudadano: {$nombreCiudadano}\n";
        $prompt .= "- Tipo de Solicitud: {$tipoSolicitud}\n";

        $prompt .= "\n## INSTRUCCIONES\n";
        $prompt .= "1. El mensaje debe ser profesional y cordial\n";
        $prompt .= "2. Confirma que la solicitud ha sido recibida exitosamente\n";
        $prompt .= "3. Menciona el tipo de solicitud\n";
        $prompt .= "4. Indica que pronto se comunicarán por correo, llamada o WhatsApp\n";
        $prompt .= "5. Agradece por usar los canales de participación ciudadana\n";
        $prompt .= "6. Ofrece disponibilidad para consultas\n";
        $prompt .= "7. Usa emojis de forma moderada (máximo 2-3)\n";
        $prompt .= "8. El mensaje debe ser CORTO (máximo 600 caracteres)\n";
        $prompt .= "9. Usa formato WhatsApp: *negritas*, _cursivas_\n";
        $prompt .= "10. NO uses HTML ni código\n";
        $prompt .= "11. El tono debe ser de la administración pública\n\n";
        $prompt .= "Genera SOLO el mensaje, sin introducción ni explicación adicional.";

        return $prompt;
    }

    /**
     * Mensaje predeterminado para Atención Ciudadana
     */
    protected function mensajePredeterminadoAtencionCiudadana(array $datos): string
    {
        $nombreCiudadano = $datos['nombre'] ?? 'Ciudadano';
        $tipoSolicitud = $datos['tipo_solicitud'] ?? 'atención ciudadana';
        $nombreEmpresa = $this->obtenerNombreEmpresa();

        $mensaje = "✅ *Solicitud de Atención Recibida*\n\n";
        $mensaje .= "Estimado/a *{$nombreCiudadano}*,\n\n";
        $mensaje .= "Le confirmamos que su solicitud de {$tipoSolicitud} ha sido recibida exitosamente por {$nombreEmpresa}.\n\n";
        $mensaje .= "Pronto nos comunicaremos con usted vía correo electrónico, llamada o WhatsApp para brendarle la atención que requiere.\n\n";
        $mensaje .= "Agradecemos su confianza en nuestros servicios de participación ciudadana. Si tiene alguna consulta adicional, estamos a su disposición.";

        return $mensaje;
    }
}
