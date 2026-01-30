<?php
use Livewire\Volt\Component;
use App\Models\DerechoDePalabra;
use App\Models\Ciudadano;
use App\Mail\ConfirmarDerechoPalabraMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;
use Carbon\Carbon;

new class extends Component
{
    public $derechoPalabraId = null;
    public $ciudadanoId = null;
    public $email = '';
    public $comision = '';
    public $estado = '';
    public $observaciones = '';

    protected $listeners = [
        'abrir-confirmar-modal' => 'abrirConfirmarModal',
    ];

    public function rules()
    {
        return [
            'estado' => 'required|in:pendiente,aprobada,rechazada',
            'observaciones' => 'required|string|max:1000',
            'email' => 'required|email',
        ];
    }

    public function messages()
    {
        return [
            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado seleccionado no es válido.',
            'observaciones.required' => 'Las observaciones son obligatorias.',
            'observaciones.max' => 'Las observaciones no pueden exceder 1000 caracteres.',
            'email.required' => 'El email es requerido.',
            'email.email' => 'El email debe ser válido.',
        ];
    }

    public function abrirConfirmarModal($id)
    {
        try {
            $this->resetForm();

            $this->derechoPalabraId = $id;
            $derecho = DerechoDePalabra::with(['ciudadano', 'comision'])->findOrFail($id);

            Log::info('Modal abierto - ID: ' . $id . ' - Email: ' . $derecho->ciudadano?->email);

            if ($derecho->estado === 'aprobada') {
                $this->dispatch('showAlert', [
                    'icon' => 'warning',
                    'title' => 'Ya Confirmada',
                    'text' => 'Esta solicitud ya ha sido confirmada anteriormente.',
                ]);
                return;
            }

            $this->ciudadanoId = $derecho->ciudadano_id;
            $this->email = $derecho->ciudadano?->email ?? '';
            $this->comision = $derecho->comision?->nombre ?? '';
            $this->estado = '';
            $this->observaciones = '';

            Log::info('Datos cargados - Email: ' . $this->email . ' - Comisión: ' . $this->comision);

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

            $derecho = DerechoDePalabra::findOrFail($this->derechoPalabraId);

            if ($derecho->estado === 'aprobada') {
                $this->dispatch('showAlert', [
                    'icon' => 'warning',
                    'title' => 'Ya Confirmada',
                    'text' => 'Esta solicitud ya ha sido confirmada anteriormente. No se puede confirmar nuevamente.',
                ]);
                return;
            }

            $derecho->update([
                'estado' => $this->estado,
                'observaciones' => trim($this->observaciones),
                'fecha_respuesta' => now(),
            ]);

            // Obtener el ciudadano para enviar el correo
            $ciudadano = Ciudadano::findOrFail($this->ciudadanoId);
            Mail::send(new ConfirmarDerechoPalabraMail($derecho, $ciudadano, $this->observaciones));

            Log::info('Email enviado exitosamente a: ' . $ciudadano->email . ' para ID: ' . $this->derechoPalabraId);

            $this->dispatch('closeModal', name: 'confirmarModal');
            $this->dispatch('pg:eventRefresh-derecho-palabra-table');

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => '¡Éxito!',
                'text' => 'Solicitud confirmada y correo enviado correctamente.',
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

    public function generatePdf(DerechoDePalabra $derecho)
    {
        try {
            $ciudadano = $derecho->ciudadano;

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
                ['label' => 'Sesión', 'value' => $derecho->sesion?->titulo ?? 'Sin sesión'],
                ['label' => 'Comisión', 'value' => $derecho->comision?->nombre ?? 'Sin comisión'],
                ['label' => 'Motivo', 'value' => $derecho->motivo_solicitud],
                ['label' => 'Estado', 'value' => ucfirst($derecho->estado), 'highlight' => true],
                ['label' => 'Observaciones', 'value' => $derecho->observaciones ?? 'N/A'],
                ['label' => 'Fecha Respuesta', 'value' => $derecho->fecha_respuesta ? Carbon::parse($derecho->fecha_respuesta)->timezone('America/Caracas')->format('d/m/Y H:i') : 'Pendiente'],
                ['label' => 'Fecha Solicitud', 'value' => Carbon::parse($derecho->created_at)->timezone('America/Caracas')->format('d/m/Y H:i')],
            ];

            $html = view('livewire.pages.admin.pdf.pdf-layout', [
                'fields' => $fields,
                'title' => 'Derecho de Palabra',
                'subtitle' => $ciudadano->nombre . ' ' . $ciudadano->apellido,
                'logo_icon' => $logoIcon,
                'primaryColor' => $primaryColor,
                'secondaryColor' => $secondaryColor,
                'tags' => ['Derecho de Palabra', ucfirst($derecho->estado), $derecho->sesion?->titulo],
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
            }, "derecho_palabra_" . $derecho->id . "_" . now()->format('d-m-Y_H-i') . ".pdf", [
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

    public function deleteDerechoPalabra($derechoPalabraId)
    {
        try {
            $derecho = DerechoDePalabra::findOrFail($derechoPalabraId);

            $derecho->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Eliminado',
                'text' => 'La solicitud de derecho de palabra se eliminó correctamente',
            ]);

            $this->dispatch('pg:eventRefresh-derecho-palabra-table');

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
        $this->reset(['derechoPalabraId', 'ciudadanoId', 'email', 'comision', 'estado', 'observaciones']);
        $this->resetValidation();
    }
};
?>
<div>
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => 'Solicitudes de derecho de palabra'],
        ]" />
    </x-slot>

    <x-container class="w-full px-4 mt-6">
        <livewire:derechode-palabra-table />
    </x-container>

    <!-- Modal Confirmar -->
    @include('livewire.pages.admin.derecho_palabra.modal.modal')

    <!-- Scripts eliminar -->
    @push('scripts')
        <script>
            function confirmDeleteDerechoPalabra(derechoPalabra_id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'No podrás revertir esto! Se eliminará la solicitud de derecho de palabra.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('deleteDerechoPalabra', derechoPalabra_id);
                    }
                });
            }
        </script>
    @endpush
</div>
