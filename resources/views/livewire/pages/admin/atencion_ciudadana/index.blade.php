<?php

use Livewire\Volt\Component;
use App\Models\Solicitud;
use App\Models\Ciudadano;
use App\Models\Empresa;
use App\Mail\ConfirmarSolicitudMail;
use App\Services\EvolutionService;
use App\Services\GroqAIService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;
use Carbon\Carbon;

new class extends Component {
    public $solicitudId = null;
    public $ciudadanoId = null;
    public $email = '';
    public $whatsapp = '';
    public $tipo_solicitud = '';
    public $descripcion = '';
    public $estado = '';
    public $respuesta = '';

    protected EvolutionService $evolutionService;
    protected GroqAIService $groqService;

    public function __construct()
    {
        parent::__construct();
        $this->evolutionService = app(EvolutionService::class);
        $this->groqService = app(GroqAIService::class);
    }

    protected $listeners = [
        'abrir-confirmar-modal-solicitud' => 'abrirConfirmarModal',
    ];

    public function rules()
    {
        return [
            'estado' => 'required|in:pendiente,aprobado,rechazado',
            'respuesta' => 'required|string|max:1000',
            'email' => 'required|email',
        ];
    }

    public function messages()
    {
        return [
            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado seleccionado no es válido.',
            'respuesta.required' => 'La respuesta es obligatoria.',
            'respuesta.max' => 'La respuesta no puede exceder 1000 caracteres.',
            'email.required' => 'El email es requerido.',
            'email.email' => 'El email debe ser válido.',
        ];
    }

    public function abrirConfirmarModal($id)
    {
        try {
            $this->resetForm();

            $this->solicitudId = $id;
            $solicitud = Solicitud::with(['ciudadano', 'tipoSolicitud'])->findOrFail($id);

            Log::info('Modal abierto - ID: ' . $id . ' - Email: ' . $solicitud->ciudadano?->email);

            // Verificar si ya fue confirmada (aprobado o rechazado)
            if (in_array($solicitud->estado, ['aprobado', 'rechazado'])) {
                $this->dispatch('showAlert', [
                    'icon' => 'warning',
                    'title' => 'Ya Confirmada',
                    'text' => 'Esta solicitud ya ha sido confirmada anteriormente.',
                ]);
                return;
            }

            $this->ciudadanoId = $solicitud->ciudadano_id;
            $this->email = $solicitud->ciudadano?->email ?? '';
            $this->whatsapp = $solicitud->ciudadano?->whatsapp ?? '';
            $this->tipo_solicitud = $solicitud->tipoSolicitud?->nombre ?? '';
            $this->descripcion = $solicitud->descripcion ?? '';
            $this->estado = '';
            $this->respuesta = '';

            Log::info('Datos cargados - Email: ' . $this->email . ' - WhatsApp: ' . $this->whatsapp . ' - Tipo: ' . $this->tipo_solicitud);

        } catch (\Exception $e) {
            Log::error('Error al abrir modal: ' . $e->getMessage());
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo cargar la solicitud.',
            ]);
        }
    }

    public function confirmar()
    {
        try {
            $this->validate();

            $solicitud = Solicitud::findOrFail($this->solicitudId);

            // Verificar nuevamente si ya fue confirmada
            if (in_array($solicitud->estado, ['aprobado', 'rechazado'])) {
                $this->dispatch('showAlert', [
                    'icon' => 'warning',
                    'title' => 'Ya Confirmada',
                    'text' => 'Esta solicitud ya ha sido confirmada anteriormente. No se puede confirmar nuevamente.',
                ]);
                return;
            }

            $solicitud->update([
                'estado' => $this->estado,
                'respuesta' => trim($this->respuesta),
                'fecha_respuesta' => now(),
            ]);

            // Obtener el ciudadano
            $ciudadano = Ciudadano::findOrFail($this->ciudadanoId);

            // Enviar correo a Mailtrap
            try {
                Mail::send(new ConfirmarSolicitudMail($solicitud, $ciudadano, $this->respuesta));
                Log::info('Email enviado exitosamente a: ' . $ciudadano->email . ' para Solicitud ID: ' . $this->solicitudId);
            } catch (\Exception $e) {
                Log::warning('Error al enviar email: ' . $e->getMessage());
            }

            // Enviar WhatsApp
            try {
                $this->enviarWhatsAppAtencionCiudadana($ciudadano, $solicitud);
            } catch (\Exception $e) {
                Log::warning('Error al enviar WhatsApp: ' . $e->getMessage());
            }

            $this->dispatch('closeModal', name: 'confirmarModalSolicitud');
            $this->dispatch('pg:eventRefresh-solicitud-table');

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => '¡Éxito!',
                'text' => 'Solicitud confirmada. Email y WhatsApp enviados correctamente.',
                'timer' => 2000,
                'timerProgressBar' => true,
            ]);

            $this->resetForm();

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Errores de validación: ' . json_encode($e->errors()));
            $this->dispatch('showAlert', [
                'icon' => 'warning',
                'title' => 'Validación',
                'text' => 'Por favor completa todos los campos correctamente.',
            ]);
        } catch (\Exception $e) {
            Log::error('Error al confirmar solicitud: ' . $e->getMessage());
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error: ' . $e->getMessage(),
                'timer' => 2500,
                'timerProgressBar' => true,
            ]);
        }
    }

    /**
     * Enviar WhatsApp para Atención Ciudadana
     */
    private function enviarWhatsAppAtencionCiudadana(Ciudadano $ciudadano, Solicitud $solicitud)
    {
        // Obtener tipo de solicitud
        $tipoSolicitud = $solicitud->tipoSolicitud;
        $tipoSolicitudNombre = $tipoSolicitud ? $tipoSolicitud->nombre : 'atención ciudadana';

        // Obtener nombre de empresa
        $empresa = Empresa::first();
        $nombreEmpresa = $empresa && $empresa->razon_social ? $empresa->razon_social : 'Plenaria';

        // Construir mensaje según estado
        if ($solicitud->estado === 'aprobado') {
            // Usar Groq para generar mensaje de aprobación
            $respuestaGroq = $this->groqService->generarMensajeAprobacion([
                'nombre' => $ciudadano->nombre,
                'tipo_solicitud' => $tipoSolicitudNombre,
                'observaciones' => $this->respuesta,
            ], 'atencion_ciudadana');

            $mensaje = $respuestaGroq['mensaje'];

            Log::info('Mensaje de aprobación generado', [
                'es_ia' => $respuestaGroq['es_ia'],
                'motivo' => $respuestaGroq['motivo'] ?? 'IA',
            ]);
        } else {
            // Usar Groq para generar mensaje de rechazo
            $respuestaGroq = $this->groqService->generarMensajeRechazo([
                'nombre' => $ciudadano->nombre,
                'tipo_solicitud' => $tipoSolicitudNombre,
                'observaciones' => $this->respuesta,
            ], 'atencion_ciudadana');

            $mensaje = $respuestaGroq['mensaje'];

            Log::info('Mensaje de rechazo generado', [
                'es_ia' => $respuestaGroq['es_ia'],
                'motivo' => $respuestaGroq['motivo'] ?? 'IA',
            ]);
        }

        // Enviar por WhatsApp
        $response = $this->evolutionService->sendMessage($ciudadano->whatsapp, $mensaje);

        if (!$response['error']) {
            Log::info('✅ WhatsApp de atención ciudadana enviado', [
                'ciudadano_id' => $ciudadano->id,
                'solicitud_id' => $solicitud->id,
                'estado' => $solicitud->estado,
            ]);
        } else {
            Log::warning('⚠️ Error al enviar WhatsApp de atención ciudadana', [
                'ciudadano_id' => $ciudadano->id,
                'error' => $response['message'] ?? 'Error desconocido',
            ]);
        }
    }

    public function generatePdf($solicitudId)
    {
        try {
            $solicitud = Solicitud::with(['ciudadano', 'tipoSolicitud'])->findOrFail($solicitudId);
            $ciudadano = $solicitud->ciudadano;

            $logoPath = Setting::get('logo_horizontal');
            $logoIcon = null;
            if ($logoPath && Storage::disk('public')->exists($logoPath)) {
                $imageContent = Storage::disk('public')->get($logoPath);
                $mimeType = Storage::disk('public')->mimeType($logoPath);
                $logoIcon = 'data:' . $mimeType . ';base64,' . base64_encode($imageContent);
            }

            $primaryColor = Setting::get('primary_color', '#0f2440');
            $secondaryColor = Setting::get('secondary_color', '#00d4ff');

            $fields = [
                ['label' => 'Cédula', 'value' => $ciudadano->cedula],
                ['label' => 'Nombre', 'value' => $ciudadano->nombre . ' ' . $ciudadano->apellido],
                ['label' => 'Email', 'value' => $ciudadano->email],
                ['label' => 'Teléfono', 'value' => $ciudadano->telefono_movil],
                ['label' => 'WhatsApp', 'value' => $ciudadano->whatsapp ?? 'N/A'],
                ['label' => 'Tipo de Solicitud', 'value' => $solicitud->tipoSolicitud?->nombre ?? 'N/A'],
                ['label' => 'Descripción', 'value' => $solicitud->descripcion],
                ['label' => 'Estado', 'value' => ucfirst($solicitud->estado), 'highlight' => true],
                ['label' => 'Respuesta', 'value' => $solicitud->respuesta ?? 'N/A'],
                ['label' => 'Acepta Términos', 'value' => $solicitud->acepta_terminos ? 'Sí' : 'No'],
                ['label' => 'Fecha Respuesta', 'value' => $solicitud->fecha_respuesta ? Carbon::parse($solicitud->fecha_respuesta)->timezone('America/Caracas')->format('d/m/Y H:i') : 'Pendiente'],
                ['label' => 'Fecha Solicitud', 'value' => Carbon::parse($solicitud->created_at)->timezone('America/Caracas')->format('d/m/Y H:i')],
            ];

            $html = view('livewire.pages.admin.pdf.pdf-layout', [
                'fields' => $fields,
                'title' => 'Solicitud de Atención Ciudadana',
                'subtitle' => $ciudadano->nombre . ' ' . $ciudadano->apellido,
                'logo_icon' => $logoIcon,
                'primaryColor' => $primaryColor,
                'secondaryColor' => $secondaryColor,
                'tags' => ['Solicitud', ucfirst($solicitud->estado), $solicitud->tipoSolicitud?->nombre],
                'badgeTitle' => 'Clasificación',
                'sectionTitle' => 'Datos de la Solicitud',
            ])->render();

            $html = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>' . $html;

            $pdf = Pdf::loadHTML($html)
                ->setPaper('a4')
                ->setOption('encoding', 'UTF-8')
                ->setOption('default_font', 'DejaVu Sans');

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, "solicitud_" . $solicitud->id . "_" . now()->format('d-m-Y_H-i') . ".pdf", [
                'Content-Type' => 'application/pdf',
            ]);

        } catch (\Exception $e) {
            Log::error('Error en generatePdf: ' . $e->getMessage());
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Error al generar PDF: ' . $e->getMessage(),
            ]);
        }
    }

    public function deleteSolicitud($solicitudId)
    {
        try {
            $solicitud = Solicitud::findOrFail($solicitudId);

            $solicitud->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Eliminado',
                'text' => 'La solicitud se eliminó correctamente',
            ]);

            $this->dispatch('pg:eventRefresh-solicitud-table');

        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar la solicitud: ' . $e->getMessage(),
            ]);
        }
    }

    public function resetForm()
    {
        $this->reset(['solicitudId', 'ciudadanoId', 'email', 'whatsapp', 'tipo_solicitud', 'descripcion', 'estado', 'respuesta']);
        $this->resetValidation();
    }
}; ?>

<div>
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => 'Solicitudes Pendientes'],
        ]" />
    </x-slot>

    <x-container class="w-full px-4 mt-6">
        <livewire:solicitudTable />
    </x-container>

    <!-- Modal Confirmar -->
   @include('livewire.pages.admin.atencion_ciudadana.modal.modal-solicitud')


    <!-- Scripts eliminar -->
    @push('scripts')
        <script>
            function confirmDeleteSolicitud(solicitud_id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'No podrás revertir esto! Se eliminará la solicitud.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('deleteSolicitud', solicitud_id);
                    }
                });
            }
        </script>
    @endpush
</div>
