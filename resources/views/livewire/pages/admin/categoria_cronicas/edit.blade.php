<?php

use Livewire\Volt\Component;
use App\Models\CategoriaCronica;
use Livewire\WithFileUploads;
new class extends Component {
        use WithFileUploads;
        public $categoria;
        public $nombre;
        public $descripcion;
        public $mode = 'edit';

public function mount($categoria_cronicas = null)
{
    if ($categoria_cronicas) {
        // Modo edición: cargar la categoría específica
        $categoria = CategoriaCronica::find($categoria_cronicas);
        if (!$categoria) {
            abort(404, 'No se encontró la categoría a editar');
        }
        $this->categoria = $categoria;
    } else {
        // Modo "ver datos sin ID": tomar la primera categoría existente
        $categoria = CategoriaCronica::first();
        if ($categoria) {
            $this->categoria = $categoria;
        } else {
            // Si no hay ninguna categoría en la BD, crear un objeto vacío
            $this->categoria = new CategoriaCronica();
        }
    }

    // Inicializar los campos del formulario
    $this->nombre = $this->categoria->nombre ?? '';
    $this->descripcion = $this->categoria->descripcion ?? '';
}


protected function rules()
{
    $categoriaId = $this->categoria->id ?? null;

    return [
        'nombre' => 'required|string|min:3|max:255|unique:categoria_cronicas,nombre,' . $categoriaId,
        'descripcion' => 'nullable|string|max:1000',
    ];
}
    protected $messages = [
        'nombre.required' => 'El nombre de la categoría es obligatorio.',
        'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
        'nombre.max' => 'El nombre no puede superar 255 caracteres.',
        'nombre.unique' => 'Ya existe una categoría con ese nombre.',
        'descripcion.max' => 'La descripción no puede superar 1000 caracteres.',
    ];

    // Validar con alertas
    public function validateWithAlert()
    {
        try {
            $this->validate();
            return true;
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors();
            $firstField = array_keys($errors->toArray())[0];
            $firstError = $errors->first($firstField);
            $fieldNames = [
                'nombre' => 'Nombre',
                'descripcion' => 'Descripción',
            ];
            $fieldTitle = $fieldNames[$firstField] ?? 'Campo';
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error en ' . $fieldTitle,
                'text' => $firstError,
                'timer' => 8000,
                'timerProgressBar' => true,
            ]);
            return false;
        }
    }
public function save()
{
    if (!$this->validateWithAlert()) return;

    try {
        // Asignar valores y guardar
        if ($this->categoria) {
            // Editar
            $this->categoria->nombre = $this->nombre;
            $this->categoria->descripcion = $this->descripcion;
            $this->categoria->save();
        } else {
            // Crear nueva categoría
            $this->categoria = CategoriaCronica::create([
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
            ]);
        }

        // Alerta de éxito
        $this->dispatch('showAlert', [
            'icon' => 'success',
            'title' => $this->categoria->id ? 'Categoría actualizada' : 'Categoría creada',
            'text' => 'Los cambios se guardaron correctamente.',
            'timer' => 4000,
            'timerProgressBar' => true,
        ]);
        // Redirección opcional
    return $this->redirect(route('admin.categoria_cronicas.index'), navigate: true);

    } catch (\Exception $e) {
        // Alerta de error
        $this->dispatch('showAlert', [
            'icon' => 'error',
            'title' => 'Error',
            'text' => 'Ocurrió un problema: ' . $e->getMessage(),
            'timer' => 8000,
            'timerProgressBar' => true,
        ]);
    }
}
    public function limpiar()
    {
        $this->reset(['nombre', 'descripcion']);
        $this->resetValidation();
        $this->dispatch('showAlert', [
            'icon' => 'info',
            'title' => 'Formulario limpiado',
            'timer' => 2000,
            'timerProgressBar' => true,
        ]);
    }
    public function cancel()
    {
        return $this->redirect(route('admin.categoria_cronicas.index'), navigate: true);
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
                    Editar Categoria
                </span>
            </nav>
        </x-slot> 

        @include('livewire.pages.admin.categoria_cronicas.form.form', ['mode' => 'edit'])
</div>