<?php

namespace App\Http\Livewire\Admin;

use Livewire\Volt\Component;
use App\Models\CategoriaInstrumento;

new class extends Component {

    public $nombre = '';
    public $tipo_categoria = '';
    public $observacion = '';

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'tipo_categoria' => 'required|string|max:255',
        'observacion' => 'nullable|string|max:1000',
    ];

    /**
     * Guardar categoría
     */
    public function save()
    {
        // Validar los campos básicos
        $this->validate();

        // Verificar si ya existe el nombre
        $existe = CategoriaInstrumento::where('nombre', $this->nombre)->first();
        if ($existe) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Nombre duplicado',
                'text' => 'Ya existe una categoría con este nombre. Por favor usa otro.',
                'timer' => 2500,
                'timerProgressBar' => true,
            ]);
            return;
        }

        try {
            CategoriaInstrumento::create([
                'nombre' => $this->nombre,
                'tipo_categoria' => $this->tipo_categoria,
                'observacion' => $this->observacion,
            ]);

            // Alerta de éxito
            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Éxito',
                'text' => 'Categoría creada correctamente.',
                'timer' => 2000,
                'timerProgressBar' => true,
            ]);

        
         
        return $this->redirect(route('admin.categoria-instrumentos.index'), navigate: true);

        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al guardar: ' . $e->getMessage(),
                'timer' => 2500,
                'timerProgressBar' => true,
            ]);
        }
    }

    public function limpiar()
    {
        if (empty($this->nombre) && empty($this->tipo_categoria) && empty($this->observacion)) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Formulario vacío',
                'text' => 'No hay datos en el formulario para limpiar.',
                'timer' => 2000,
                'timerProgressBar' => true,
            ]);
            return;
        }

        $this->reset(['nombre', 'tipo_categoria', 'observacion']);

        $this->dispatch('showAlert', [
            'icon' => 'info',
            'title' => 'Formulario limpio',
            'text' => 'Se han borrado todos los campos del formulario.',
            'timer' => 2000,
            'timerProgressBar' => true,
        ]);
    }

    public function cancel()
    {
        return $this->redirect(route('admin.categoria-instrumentos.index'), navigate: true);
    }
};

?>



<div class="min-h-screen"> <!-- Único contenedor raíz -->

    <!-- Breadcrumbs -->
    <div class="mt-6">
        <nav class="flex items-center text-sm font-medium text-gray-600 dark:text-gray-300 space-x-2" aria-label="Breadcrumb">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 flex items-center gap-1">
                <x-icon name="home" class="w-4 h-4" />
                Dashboard
            </a>
            <span class="text-gray-400 dark:text-gray-500">/</span>
            <span class="text-gray-700 dark:text-gray-200 flex items-center gap-1">
                <x-icon name="document-text" class="w-4 h-4" />
                Nueva Categoría
            </span>
        </nav>
    </div>

    <!-- Formulario -->
    @include('livewire.pages.admin.categoria_instrumentos.form.nueva_categoria')

    @push('scripts')
    <script>
        Livewire.on('showAlert', params => {
            Swal.fire({
                icon: params.icon,
                title: params.title,
                text: params.text,
                timer: params.timer || 2000,
                timerProgressBar: params.timerProgressBar || false,
                showConfirmButton: false,
            });
        });

        Livewire.on('redirectAfterSave', () => {
            setTimeout(() => {
                window.location.href = "{{ route('admin.categoria-instrumentos.index') }}";
            }, 1500);
        });
    </script>
    @endpush

</div>

