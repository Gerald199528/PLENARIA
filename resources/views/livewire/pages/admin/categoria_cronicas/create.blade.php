        <?php

    use Livewire\Volt\Component;
    use App\Models\CategoriaCronica;
    new class extends Component {

        public $mode = 'create';
        public $nombre;
        public $descripcion;
        protected $categoria; 



public function mount($categoriaId = null)
{
    if ($categoriaId) {
        $categoria = CategoriaCronica::find($categoriaId);
        if (!$categoria) abort(404);

        $this->nombre = $categoria->nombre;
        $this->descripcion = $categoria->descripcion;
        $this->categoria = $categoria;
        $this->mode = 'edit';
    } else {
        $this->mode = 'create';
    }
}


        // Reglas de validación
        protected function rules()
        {
            return [
                'nombre' => 'required|string|min:3|max:255|unique:categoria_cronicas,nombre',
                'descripcion' => 'nullable|string|max:1000',
            ];
        }
        // Mensajes personalizados
        protected $messages = [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
            'nombre.max' => 'El nombre no puede superar 255 caracteres.',
            'nombre.unique' => 'Ya existe una categoría con ese nombre.',
            'descripcion.max' => 'La descripción no puede superar 1000 caracteres.',
        ];

        // Validar y mostrar alerta
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

        // Guardar categoría
        public function save()
        {
            if (!$this->validateWithAlert()) return;

            try {
                CategoriaCronica::create([
                    'nombre' => $this->nombre,
                    'descripcion' => $this->descripcion,
                ]);

                $this->dispatch('showAlert', [
                    'icon' => 'success',
                    'title' => '¡Categoría guardada!',
                    'text' => 'La categoría se ha registrado correctamente.',
                    'timer' => 4000,
                    'timerProgressBar' => true,
                ]);

                return $this->redirect(route('admin.categoria_cronicas.index'), navigate: true);

            } catch (\Exception $e) {
                $this->dispatch('showAlert', [
                    'icon' => 'error',
                    'title' => 'Error al guardar',
                    'text' => 'Ocurrió un problema: ' . $e->getMessage(),
                    'timer' => 8000,
                    'timerProgressBar' => true,
                ]);
            }
        }

        // Limpiar formulario
        public function limpiar($showAlert = true)
        {
            $this->reset([
                'nombre',
                'descripcion',
            ]);
            $this->resetValidation();

            if ($showAlert) {
                $this->dispatch('showAlert', [
                    'icon' => 'info',
                    'title' => 'Formulario limpiado',
                    'text' => 'Todos los campos han sido reiniciados.',
                    'timer' => 2000,
                    'timerProgressBar' => true,
                    'toast' => true,
                    'position' => 'top-end',
                    'showConfirmButton' => false,
                ]);
            }
        }
        
    public function cancel()
    {
        return $this->redirect(route('admin.categoria_cronicas.index'), navigate: true);
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
                'name' => ' Registrar Categoria Cronica',
            ],
        ]" />   
        </x-slot>      
        @include('livewire.pages.admin.categoria_cronicas.form.form', ['mode' => 'create'])
    </div>
