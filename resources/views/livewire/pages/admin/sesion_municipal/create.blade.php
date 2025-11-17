<?php
use Livewire\Volt\Component;
use App\Models\SesionMunicipal;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;

new class extends Component {
    public $mode = 'create';
    public $sesionId = null;
    
    // Categoría
    public $categoria_nombre = '';
    public $categoria_descripcion = '';
    public $categoria_id = null;
    
    // Sesión
    public $titulo = '';
    public $descripcion = '';
    public $fecha_hora = '';
    public $estado = 'proxima';
    
    #[Computed]
    public function categorias()
    {
        return DB::table('categorias_participacion')->get();
    }

    protected function rules()
    {
        $rules = [
            'titulo' => 'required|string|max:255|regex:/^[a-zA-ZÀ-ÿ\s\-]+$/',
            'descripcion' => 'required|string|min:20|max:2000|regex:/^[a-zA-ZÀ-ÿ\s\.\,\;\:\!\?\(\)\-]+$/',
            'fecha_hora' => 'required|date_format:Y-m-d\TH:i|after_or_equal:now',
            'estado' => 'required|in:proxima',
            'categoria_id' => 'nullable|exists:categorias_participacion,id',
        ];

        // Si no seleccionó categoría existente, debe crear una nueva
        if (!$this->categoria_id) {
            $rules['categoria_nombre'] = 'required|string|max:255|regex:/^[a-zA-ZÀ-ÿ\s\-]+$/|unique:categorias_participacion,nombre';
            $rules['categoria_descripcion'] = 'required|string|min:20|max:1000|regex:/^[a-zA-ZÀ-ÿ\s\.\,\;\:\!\?\(\)\-]+$/';
        }

        return $rules;
    }

    protected $messages = [
        'categoria_nombre.required' => 'El nombre de la categoría es obligatorio si no selecciona una existente.',
        'categoria_nombre.unique' => 'El nombre de la categoría ya ha sido registrado.',
        'categoria_nombre.max' => 'El nombre de la categoría no puede exceder 255 caracteres.',
        'categoria_nombre.regex' => 'El nombre de la categoría solo permite letras, espacios y guiones.',
        'categoria_descripcion.required' => 'La descripción de la categoría es obligatoria si no selecciona una existente.',
        'categoria_descripcion.min' => 'La descripción de la categoría debe tener al menos 20 caracteres.',
        'categoria_descripcion.max' => 'La descripción de la categoría no puede exceder 1000 caracteres.',
        'categoria_descripcion.regex' => 'La descripción de la categoría contiene caracteres inválidos.',
        'titulo.required' => 'El título de la sesión es obligatorio.',
        'titulo.regex' => 'El título de la sesión solo permite letras, espacios y guiones.',
        'descripcion.required' => 'La descripción de la sesión es obligatoria.',
        'descripcion.min' => 'La descripción de la sesión debe tener al menos 20 caracteres.',
        'descripcion.max' => 'La descripción no puede exceder 2000 caracteres.',
        'descripcion.regex' => 'La descripción contiene caracteres inválidos.',
        'fecha_hora.required' => 'La fecha y hora de la sesión es obligatoria.',
        'fecha_hora.date_format' => 'La fecha y hora debe estar en formato válido.',
        'fecha_hora.after_or_equal' => 'La fecha y hora no puede ser menor a la fecha y hora actual.',
        'estado.required' => 'El estado de la sesión es obligatorio.',
        'categoria_id.exists' => 'La categoría seleccionada no es válida.',
    ];

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
                'categoria_nombre' => 'Nombre de la Categoría',
                'categoria_descripcion' => 'Descripción de la Categoría',
                'titulo' => 'Título de la Sesión',
                'descripcion' => 'Descripción de la Sesión',
                'fecha_hora' => 'Fecha y Hora',
                'estado' => 'Estado',
                'categoria_id' => 'Categoría',
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
            // Si no seleccionó categoría existente, crear nueva
            if (!$this->categoria_id && $this->categoria_nombre) {
                $this->categoria_id = DB::table('categorias_participacion')->insertGetId([
                    'nombre' => trim($this->categoria_nombre),
                    'descripcion' => trim($this->categoria_descripcion),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Crear sesión municipal
            SesionMunicipal::create([
                'titulo' => trim($this->titulo),
                'descripcion' => trim($this->descripcion),
                'fecha_hora' => $this->fecha_hora,
                'estado' => $this->estado,
                'categoria_participacion_id' => $this->categoria_id,
            ]);

            $this->limpiar(false);

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Éxito',
                'text' => 'Sesión municipal registrada con éxito.',
                'timer' => 2000,
                'timerProgressBar' => true,
            ]);

            return $this->redirect(route('admin.sesion_municipal.index'), navigate: true);

        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error: ' . $e->getMessage(),
                'timer' => 3000,
                'timerProgressBar' => true,
            ]);
        }
    }
    
    public function limpiar($showAlert = true)
    {
        $this->reset([
            'categoria_nombre',
            'categoria_descripcion',
            'titulo',
            'descripcion',
            'fecha_hora',
            'estado',
            'categoria_id',
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
        return $this->redirect(route('admin.sesion_municipal.index'), navigate: true);
    }
}; ?>

<div>
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => ' Agendar Sesión'],
        ]" />
    </x-slot>
    @include('livewire.pages.admin.sesion_municipal.form.form', ['mode' => $mode])
</div>