<?php

use Livewire\Volt\Component;
use App\Models\Comision;
use Illuminate\Validation\ValidationException;

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
};

?>
<div>
    <!-- Breadcrumbs -->
    <x-slot name="breadcrumbs">
        <nav class="flex items-center text-sm font-medium text-gray-600 dark:text-gray-300 space-x-2" aria-label="Breadcrumb">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 flex items-center gap-1">
                <x-icon name="home" class="w-4 h-4" />
                Dashboard
            </a>
            <span class="text-gray-400 dark:text-gray-500">/</span>
            <span class="text-gray-700 dark:text-gray-200 flex items-center gap-1">
                <x-icon name="document-text" class="w-4 h-4" />
                Listado de Comisiones
            </span>
        </nav>
    </x-slot>
            <!-- Botón Nueva Comisión -->
                @can('create-comision')
                <x-slot name="action">
                    <div class="mt-4">
            <a x-on:click.prevent=" await Livewire.dispatch('abrir-nueva-comision-modal'); $openModal('comisionModal') " class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl shadow-lg
                    transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600
                    focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400">
                <i class="fa-solid fa-handshake  animate-bounce"></i>
                Nueva Comisión
            </a>
        </div>
  </x-slot>
@endcan
          <!-- Table Comisión -->
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
