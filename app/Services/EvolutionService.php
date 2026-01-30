<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EvolutionService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('app.evolution_api_url', env('EVOLUTION_API_URL'));
        $this->apiKey = config('app.evolution_api_key', env('EVOLUTION_API_KEY'));

        if (!$this->baseUrl || !$this->apiKey) {
            Log::warning('Evolution API no configurada completamente');
        }
    }

    /**
     * Helper para hacer las peticiones
     */
    protected function request()
    {
        return Http::withHeaders([
            'apikey' => $this->apiKey,
            'Content-Type' => 'application/json'
        ])->baseUrl($this->baseUrl);
    }

    /**
     * 1. Crear una instancia y obtener el QR
     */
    public function createInstance($instanceName)
    {
        $response = $this->request()->post('/instance/create', [
            'instanceName' => $instanceName,
            'qrcode' => true,
            'integration' => 'WHATSAPP-BAILEYS'
        ]);

        return $response->json();
    }

    /**
     * 2. Enviar un mensaje de texto
     */
    public function sendText($instanceName, $phone, $message)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        $response = $this->request()->post("/message/sendText/{$instanceName}", [
            'number' => $phone,
            'text' => $message
        ]);

        return $response->json();
    }

    /**
     * 3. Verificar estado de la conexión
     */
    public function checkState($instanceName)
    {
        $response = $this->request()->get("/instance/connectionState/{$instanceName}");
        return $response->json();
    }

    /**
     * 4. Desconectar / Cerrar sesión
     */
    public function logout($instanceName)
    {
        $response = $this->request()->delete("/instance/logout/{$instanceName}");
        return $response->json();
    }

    /**
     * 5. Obtener el QR de una instancia existente
     */
    public function fetchQR($instanceName)
    {
        $response = $this->request()->get("/instance/connect/{$instanceName}");
        $result = $response->json();

        if (!isset($result['base64']) && (!isset($result['count']) || $result['count'] == 0)) {
            sleep(1);
            $response = $this->request()->get("/instance/connect/{$instanceName}");
            $result = $response->json();
        }

        return $result;
    }

    /**
     * 6. Eliminar una instancia completamente
     */
    public function deleteInstance($instanceName)
    {
        $response = $this->request()->delete("/instance/delete/{$instanceName}");
        return $response->json();
    }

    /**
     * 7. Obtener información de una instancia
     */
    public function getInstance($instanceName)
    {
        $response = $this->request()->get("/instance/fetchInstances?instanceName={$instanceName}");
        return $response->json();
    }

    /**
     * 8. Reiniciar una instancia
     */
    public function restartInstance($instanceName)
    {
        $response = $this->request()->put("/instance/restart/{$instanceName}");
        return $response->json();
    }

    /**
     * 9. Obtener QR con reintentos inteligentes
     */
    public function getQRWithRetry($instanceName, $maxRetries = 3)
    {
        for ($i = 0; $i < $maxRetries; $i++) {
            $result = $this->fetchQR($instanceName);

            if (isset($result['base64']) || (isset($result['count']) && $result['count'] > 0)) {
                return $result;
            }

            if ($i < $maxRetries - 1) {
                sleep(2);
            }
        }

        return $result;
    }

    /**
     * 10. Enviar mensaje
     * En LOCAL: Simula el envío
     * En PRODUCCIÓN: Envía realmente por Evolution API
     */
    public function sendMessage(string $number, string $message): array
    {
        $instanceName = 'optirango_1';

        // En ambiente LOCAL, simula el envío sin conectar a Evolution API
        if (config('app.env') === 'local') {
            Log::info('✅ SIMULACIÓN LOCAL - WhatsApp Message', [
                'number' => $number,
                'message_preview' => substr($message, 0, 50) . '...',
                'instance' => $instanceName,
                'environment' => 'LOCAL'
            ]);

            return [
                'error' => false,
                'success' => true,
                'message' => '✅ Mensaje simulado en LOCAL (no se envió realmente)',
                'response' => ['status' => 'SIMULATED_LOCAL']
            ];
        }

        // En PRODUCCIÓN, intenta conectar a Evolution API real
        try {
            $result = $this->sendText($instanceName, $number, $message);

            if (isset($result['error']) || isset($result['status']) && $result['status'] !== 'SUCCESS') {
                return [
                    'error' => true,
                    'message' => $result['message'] ?? 'Error al enviar mensaje',
                    'response' => $result
                ];
            }

            return [
                'error' => false,
                'success' => true,
                'message' => 'Mensaje enviado correctamente',
                'response' => $result
            ];

        } catch (\Exception $e) {
            Log::error('Error al enviar mensaje WhatsApp', [
                'error' => $e->getMessage(),
                'number' => $number,
                'instance' => $instanceName
            ]);

            return [
                'error' => true,
                'message' => 'Error de conexión: ' . $e->getMessage()
            ];
        }
    }
}
