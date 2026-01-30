    <?php
    use Livewire\Volt\Component;
    use Livewire\WithFileUploads; 
    use App\Models\Cronista;
    use App\Models\CategoriaCronica;

    new class extends Component {
        use WithFileUploads;
        public $cronistas;
        public $categorias;
        public $mode = 'create'; 
        public $titulo;
        public $contenido;
        public $archivo_pdf;
        public $cronista_id = ''; 
        public $categoria_id;
        public $fecha_publicacion;
        public $cronista_nombre = ''; 
        public function mount()
        {
            $this->cronistas = Cronista::all();
            $this->categorias = CategoriaCronica::all();

            if ($this->mode === 'create') {
                $ultimoCronista = Cronista::latest()->first();             
                $this->cronista_id = $ultimoCronista ? $ultimoCronista->id : null;
                $this->cronista_nombre = $ultimoCronista
                    ? $ultimoCronista->nombre_completo . ' ' . $ultimoCronista->apellido_completo
                    : '';
            }

            if ($this->mode === 'edit' && isset($this->cronica)) {
                $this->cronista_nombre = $this->cronica->cronista ? $this->cronica->cronista->nombre_completo . ' ' . $this->cronica->cronista->apellido_completo : '';
            }
        }
protected function rules()
{
    return [
        'cronista_id' => 'required|exists:cronistas,id',
        'titulo' => 'required|string|min:5|max:255|unique:cronicas,titulo',
        'categoria_id' => 'required|exists:categoria_cronicas,id',
        'fecha_publicacion' => 'required|date',
        'archivo_pdf' => $this->mode === 'edit'
            ? 'nullable|file|mimes:pdf|max:5120|unique:cronicas,archivo_pdf,' . ($this->cronica->id ?? '')
            : 'required|file|mimes:pdf|max:5120|unique:cronicas,archivo_pdf',
        'contenido' => 'required|string|min:20|max:5000',
    ];
}

        // Mensajes personalizados
        protected $messages = [
            'titulo.required' => 'El título es obligatorio.',
            'titulo.min' => 'El título debe tener al menos 5 caracteres.',
            'titulo.max' => 'El título no puede exceder 255 caracteres.',
            'titulo.unique' => 'Ya existe una crónica con este título.',
            'contenido.required' => 'El contenido de la crónica es obligatorio.',
            'contenido.min' => 'El contenido debe tener al menos 20 caracteres.',
            'contenido.max' => 'El contenido no puede superar 5000 caracteres.',
            'archivo_pdf.unique' => 'El archivo ya se encuentra registrado en la base de datos.',
            'archivo_pdf.mimes' => 'El archivo debe ser un PDF válido.',
            'archivo_pdf.max' => 'El archivo PDF no puede exceder 5MB.',
            'archivo_pdf.unique' => 'Ya existe un archivo PDF con ese nombre.',
            'cronista_id.required' => 'Debe registrar un cronista para poder continuar.',
            'cronista_id.exists' => 'El cronista seleccionado no existe.',
            'categoria_id.required' => 'Debe seleccionar una categoría.',
            'categoria_id.exists' => 'La categoría seleccionada no existe.',
            'fecha_publicacion.required' => 'Debe seleccionar una fecha de publicación.',
        
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
                'titulo' => 'Título',
                'contenido' => 'Contenido',
                'archivo_pdf' => 'Archivo PDF',
                'cronista_id' => 'Cronista',
                'categoria_id' => 'Categoría',
                'fecha_publicacion' => 'Fecha de Publicación',
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
            $filePath = null;
            if ($this->archivo_pdf) {
                $originalFileName = $this->archivo_pdf->getClientOriginalName();
                $sanitizedFileName = pathinfo($originalFileName, PATHINFO_FILENAME);
                $extension = $this->archivo_pdf->getClientOriginalExtension();
                $fileNameToStore = $sanitizedFileName . '_' . time() . '.' . $extension;    
                $filePath = $this->archivo_pdf->storeAs('cronicas', $fileNameToStore, 'public');
            }    
            \App\Models\Cronica::create([
                'titulo' => $this->titulo,
                'contenido' => $this->contenido,
                'archivo_pdf' => $filePath,
                'cronista_id' => $this->cronista_id,
                'categoria_id' => $this->categoria_id,
                'fecha_publicacion' => $this->fecha_publicacion,
            ]);        
            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => '¡Crónica guardada!',
                'text' => 'La crónica se ha registrado correctamente.',
                'timer' => 4000,
                'timerProgressBar' => true,        
            ]);

                return $this->redirect(route('admin.cronicas.index'), navigate: true);

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
    public function limpiar($showAlert = true)
    {
        $this->reset([
            'titulo',
            'contenido',
            'archivo_pdf',     
            'categoria_id',
            'fecha_publicacion',    
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
        return $this->redirect(route('admin.cronicas.index'), navigate: true);
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
                'name' => ' Registrar Cronicas',
            ],
        ]" />
    </x-slot>
        @include('livewire.pages.admin.cronicas.form.form', ['mode' => $mode])
    </div>
