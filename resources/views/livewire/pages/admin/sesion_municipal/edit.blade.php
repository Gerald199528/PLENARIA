<?php
use Livewire\Volt\Component;
use App\Models\SesionMunicipal;
use Illuminate\Support\Facades\DB;

new class extends Component {
    public $mode = 'edit';
    public $sesionId = null;
    
    // Categoría
    public $categoria_nombre = '';
    public $categoria_descripcion = '';
    public $categoria_id = null;
    public $categoria_actual = null;
    public $categorias = [];
    
    // Sesión
    public $titulo = '';
    public $descripcion = '';
    public $fecha_hora = '';
    public $estado = 'proxima';

    public function mount($sesion_municipal)
    {
        $this->categorias = DB::table('categorias_participacion')->get();
        $this->sesionId = $sesion_municipal;
        $sesion = SesionMunicipal::findOrFail($sesion_municipal);
        
        $this->titulo = $sesion->titulo;
        $this->descripcion = $sesion->descripcion;
        $this->fecha_hora = $sesion->fecha_hora->format('Y-m-d\TH:i');
        $this->estado = $sesion->estado;
        $this->categoria_id = $sesion->categoria_participacion_id;
        
        // Obtener datos de la categoría actual
        $this->categoria_actual = DB::table('categorias_participacion')
            ->where('id', $this->categoria_id)
            ->first();
    }

    protected function rules()
    {
        return [
            'titulo' => 'required|string|max:255|regex:/^[a-zA-ZÀ-ÿ\s\-]+$/',
            'descripcion' => 'required|string|min:20|max:2000|regex:/^[a-zA-ZÀ-ÿ\s\.\,\;\:\!\?\(\)\-]+$/',
            'fecha_hora' => 'required|date_format:Y-m-d\TH:i',
            'estado' => 'required|in:abierta,proxima,cerrada,completada',
            'categoria_id' => 'required|exists:categorias_participacion,id',
        ];
    }

    protected $messages = [
        'titulo.required' => 'El título de la sesión es obligatorio.',
        'titulo.max' => 'El título de la sesión no puede exceder 255 caracteres.',
        'titulo.regex' => 'El título de la sesión solo permite letras, espacios y guiones.',
        'descripcion.required' => 'La descripción de la sesión es obligatoria.',
        'descripcion.min' => 'La descripción de la sesión debe tener al menos 20 caracteres.',
        'descripcion.max' => 'La descripción no puede exceder 2000 caracteres.',
        'descripcion.regex' => 'La descripción contiene caracteres inválidos.',
        'fecha_hora.required' => 'La fecha y hora de la sesión es obligatoria.',
        'fecha_hora.date_format' => 'La fecha y hora debe estar en formato válido.',
        'estado.required' => 'El estado de la sesión es obligatorio.',
        'estado.in' => 'El estado seleccionado no es válido.',
        'categoria_id.required' => 'La categoría es obligatoria.',
        'categoria_id.exists' => 'La categoría seleccionada no existe.',
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
            $sesion = SesionMunicipal::findOrFail($this->sesionId);
            
            $sesion->update([
                'titulo' => trim($this->titulo),
                'descripcion' => trim($this->descripcion),
                'fecha_hora' => $this->fecha_hora,
                'estado' => $this->estado,
                'categoria_participacion_id' => $this->categoria_id,
            ]);

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Éxito',
                'text' => 'Sesión municipal actualizada con éxito.',
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

    public function cancel()
    {
        return $this->redirect(route('admin.sesion_municipal.index'), navigate: true);
    }
}; ?>

<div>
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => 'Editar Agenda'],
        ]" />
    </x-slot>
    
    @include('livewire.pages.admin.sesion_municipal.form.form', ['mode' => $mode])
</div>