<?php

use Livewire\Volt\Component;
use App\Models\Comision;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;
new class extends Component
{
    public $comisionId;
    public $nombre = '';
    public $descripcion = '';

    // ============================ ============================ ============================
    // Reglas de validación
    // ============================ ============================ ============================
    public function rules()
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:255',
                'unique:comisions,nombre' . ($this->comisionId ? ",{$this->comisionId}" : ''),
                'regex:/^[a-zA-ZÁÉÍÓÚáéíóúÑñ\s]+$/',
            ],
            'descripcion' => 'required|string|max:1000',
        ];
    }
    public function messages()
    {
        return [
            'nombre.required' => 'El nombre de la comision es obligatorio.',
            'nombre.max' => 'El nombre no puede exceder 255 caracteres.',
            'nombre.unique' => 'Ya existe una comisión con este nombre.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios, sin números ni caracteres especiales.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.max' => 'La descripción no puede exceder 1000 caracteres.',
        ];
    }

  // ============================ =========================== =============================
    // Resetear formulario en general codigo  necesario para guardar
    // ============================ ========================== ============================
    public function resetForm()
    {
        $this->reset(['comisionId', 'nombre', 'descripcion']);
        $this->resetValidation();
    }
    // ============================ ============================ ============================
    // Resetear formulario nueva comision filtrado
    // ============================ ============================ ============================
        protected $listeners = [
            'open-edit-modal' => 'editComision',
            'abrir-nueva-comision-modal' => 'resetForm', 
        ]; 
    // ============================ ============================ ============================
    // Nueva Comision
    // ============================ ============================ ============================
    public function saveComision()
    {
        try {
            $this->validate();
            Comision::create([
                'nombre' => trim($this->nombre),
                'descripcion' => trim($this->descripcion) ?: null,
            ]);
            $this->resetForm();
            $this->dispatch('close-modal', name: 'comisionModal');
            $this->dispatch('pg:eventRefresh-comisiones-table');
            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => '¡Éxito!',
                'text' => 'Comisión creada correctamente.',
                'timer' => 2000,
                'timerProgressBar' => true,
            ]);
            return $this->redirect(route('admin.comisiones.index'), navigate: true);
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [ 
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al crear la comisión. Inténtalo de nuevo.',
                'timer' => 2500,
                'timerProgressBar' => true,
            ]);
        }
    }
     // ============================ =========================== ============================
    //  Metodo para abrir modal edit de la tabla
    // ============================ ============================ ============================

    public function editComision($id)    {
        $comision = Comision::findOrFail($id);
        $this->comisionId = $comision->id;
        $this->nombre = $comision->nombre;
        $this->descripcion = $comision->descripcion;

        $this->dispatch('abrir-editar-comision-modal');
    }
    // ============================ ============================ ============================
    // Actualizar comision
    // ============================ ============================ ============================

    public function updateComision()
    {
        try {
            $this->validate();
            $comision = Comision::findOrFail($this->comisionId);
            $comision->update([
                'nombre' => trim($this->nombre),
                'descripcion' => trim($this->descripcion) ?: null,
            ]);
            $this->resetForm();
            $this->dispatch('close-modal', name: 'editarComisionModal');
            $this->dispatch('pg:eventRefresh-comisiones-table');
            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => '¡Éxito!',
                'text' => 'Comisión actualizada correctamente.',
                'timer' => 2000,
                'timerProgressBar' => true,
            ]);
            return $this->redirect(route('admin.comisiones.index'), navigate: true);
            } catch (ValidationException $e) {
            $errors = collect($e->errors())->flatten()->join("\n");
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error de validación',
                'text' => $errors,
                'timer' => 4000,
                'timerProgressBar' => true,
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al actualizar la comisión. Inténtalo de nuevo.',
                'timer' => 2500,
                'timerProgressBar' => true,
            ]);          
        }
    }
    // ============================ ============================ ============================
    // Eliminar comisión
    // ============================ ============================ ============================
    public function deleteComision(Comision $comision)
    {
        try {
            $comision->delete();
            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Eliminado',
                'text' => 'La comisión se eliminó correctamente',
                'timer' => 2000,
                'timerProgressBar' => true,
            ]);
        return $this->redirect(route('admin.comisiones.index'), navigate: true);
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar la comisión',
                'timer' => 2500,
                'timerProgressBar' => true,
            ]);
        }
    }
 

     // ============================ ============================ ============================
    // Metodo para limpair todo el formulario 
    // ============================ ============================ ============================
    public function limpiar()
    {
        if (empty($this->nombre) && empty($this->descripcion)) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Formulario vacío',
                'text' => 'No hay datos en el formulario para limpiar.',
                'timer' => 2000,
                'timerProgressBar' => true,
            ]);
            return;
                    }
        $this->resetForm();
        $this->dispatch('showAlert', [
            'icon' => 'info',
            'title' => 'Formulario limpio',
            'text' => 'Se han borrado todos los campos del formulario.',
            'timer' => 2000,
            'timerProgressBar' => true,
        ]);
    }

public function downloadComisionPdf($comisionId)
{
    try {
        $comision = Comision::findOrFail($comisionId);

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
            ['label' => 'Nombre', 'value' => $comision->nombre],
            ['label' => 'Descripción', 'value' => $comision->descripcion ?? 'N/A'],
            ['label' => 'Miembros', 'value' => DB::table('comision_concejal')->where('comision_id', $comisionId)->count()],
            ['label' => 'Creado', 'value' => $comision->created_at->format('d/m/Y H:i')],
        ];

        $tags = ['Comisión'];

        $html = view('livewire.pages.admin.pdf.pdf-layout', [
            'fields' => $fields,
            'title' => $comision->nombre,
            'subtitle' => 'Información de la Comisión',
            'logo_icon' => $logoIcon,
            'primaryColor' => $primaryColor,
            'secondaryColor' => $secondaryColor,
            'tags' => $tags,
            'sectionTitle' => 'Datos de la Comisión',
            'badgeTitle' => 'Clasificación'
        ])->render();

        $html = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>' . $html;

        $pdf = Pdf::loadHTML($html)
            ->setPaper('a4')
            ->setOption('encoding', 'UTF-8')
            ->setOption('default_font', 'DejaVu Sans');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, "comision_{$comision->id}.pdf", [
            'Content-Type' => 'application/pdf',
        ]);

    } catch (\Exception $e) {
        $this->dispatch('showAlert', [
            'icon' => 'error',
            'title' => 'Error',
            'text' => 'Error al generar el PDF: ' . $e->getMessage(),
        ]);
    }
}

};

?>
<div>
      <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            [
                'name' => 'Dashboard',
                'route' => route('admin.dashboard'),
            ],
            [
                'name' => 'Comisiones',
            ],
        ]" />
    </x-slot>
       <!-- Botón Nueva Comisión -->
        @can('create-comision')
        <x-slot name="action">
            <div class="mt-4">
                <a x-on:click.prevent="await Livewire.dispatch('abrir-nueva-comision-modal'); $openModal('comisionModal')" class="inline-flex items-center gap-2 px-4 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm md:text-base bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400">
                    <i class="fa-solid fa-handshake animate-bounce"></i>
                    <span class="hidden sm:inline">Nueva Comisión</span>
                    <span class="sm:hidden">Nueva</span>
                </a>
            </div>
        </x-slot>
        @endcan          <!-- Table Comisión -->
    <x-container class="w-full px-4 mt-6">
      <livewire:comisiones-table />    
    </x-container>
          <!-- Modales -->
      @include('livewire.pages.admin.comisiones.modales.modal_edit')
     @include('livewire.pages.admin.comisiones.modales.modal_nueva_comision')

    <!-- Scripts -->
    @push('scripts')
    <script>    
        function confirmDelete(comision_id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'No podrás revertir esto!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('deleteComision', comision_id);
                }
            });
        }
    </script>
    @endpush
</div>
