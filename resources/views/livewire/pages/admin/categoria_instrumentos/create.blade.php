<?php

namespace App\Http\Livewire\Admin;

use Livewire\Volt\Component;
use App\Models\CategoriaInstrumento;

new class extends Component {

    public $nombre = '';
    public $tipo_categoria = '';
    public $observacion = '';
    public $mode = 'create'; 
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
        <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            [
                'name' => 'Dashboard',
                'route' => route('admin.dashboard'),
            ],
            [
                'name' => 'Nueva Categoria',
            ],
        ]" />
    </x-slot>
    </div>
        @include('livewire.pages.admin.categoria_instrumentos.form.form', ['mode' => $mode])

</div>

