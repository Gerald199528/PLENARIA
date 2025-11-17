<?php
use Livewire\Volt\Component;
use App\Models\DerechoDePalabra;
use App\Mail\ConfirmarDerechoPalabraMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

new class extends Component
{
    public $derechoPalabraId = null;
    public $email = '';
    public $observaciones = '';

    protected $listeners = [
        'abrir-confirmar-modal' => 'abrirConfirmarModal',
    ];

    public function rules()
    {
        return [
            'observaciones' => 'required|string|max:1000',
            'email' => 'required|email',
        ];
    }

    public function messages()
    {
        return [
            'observaciones.required' => 'Las observaciones son obligatorias.',
            'observaciones.max' => 'Las observaciones no pueden exceder 1000 caracteres.',
            'email.required' => 'El email es requerido.',
            'email.email' => 'El email debe ser válido.',
        ];
    }

    public function abrirConfirmarModal($id)
    {
        try {
            $this->derechoPalabraId = $id;
            $derecho = DerechoDePalabra::findOrFail($id);
            
            // Validar si ya está aprobada
            if ($derecho->estado === 'aprobada') {
                $this->dispatch('showAlert', [
                    'icon' => 'warning',
                    'title' => 'Ya Confirmada',
                    'text' => 'Esta solicitud ya ha sido confirmada anteriormente.',
                ]);
                return;
            }
            
            $this->email = $derecho->email ?? '';
            $this->observaciones = '';
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
            // Validar datos
            $this->validate();

            $derecho = DerechoDePalabra::findOrFail($this->derechoPalabraId);
            
            // Validar si ya está aprobada
            if ($derecho->estado === 'aprobada') {
                $this->dispatch('showAlert', [
                    'icon' => 'warning',
                    'title' => 'Ya Confirmada',
                    'text' => 'Esta solicitud ya ha sido confirmada anteriormente. No se puede confirmar nuevamente.',
                ]);
                return;
            }
            
            // Actualizar estado ANTES de enviar email
            $derecho->update([
                'estado' => 'aprobada',
                'observaciones' => trim($this->observaciones),
                'fecha_respuesta' => now(),
            ]);

            // Enviar email - CORREGIDO
            Mail::send(new ConfirmarDerechoPalabraMail($derecho, $this->observaciones));

            Log::info('Email enviado exitosamente a: ' . $derecho->email);

            // Cerrar modal
            $this->dispatch('closeModal', name: 'confirmarModal');
            
            // Refrescar tabla
            $this->dispatch('pg:eventRefresh-derecho-palabra-table');
            
            // Mostrar éxito
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

    public function deleteDerechoPalabra($derechoPalabraId)
    {
        try {
            $derecho = DerechoDePalabra::findOrFail($derechoPalabraId);
            
            // Eliminar el registro de la BD
            $derecho->delete();

            // Alerta de éxito
            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Eliminado',
                'text' => 'La solicitud de derecho de palabra se eliminó correctamente',
            ]);

            // Refrescar la tabla automáticamente
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
        $this->reset(['derechoPalabraId', 'email', 'observaciones']);
        $this->resetValidation();
    }
}; ?>

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