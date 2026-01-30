<?php

namespace App\Http\Livewire\Admin;

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Models\Acuerdo;
use App\Models\CategoriaInstrumento;
use Livewire\Attributes\Title;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

new #[Title('Cargar Acuerdo')] class extends Component
{
    use WithFileUploads;
    public $mode = 'create'; 
    public $nombre; 
    public $fecha_aprobacion; 
    public $categoria_id; 
    public $observacion = ''; 
    public $categorias = []; 
    
        public function mount()
    {
        $this->categorias = CategoriaInstrumento::where('tipo_categoria', 'Acuerdos')->get();

        if ($this->categorias->isEmpty()) {
            $this->dispatch('showAlert', [
                'icon' => 'warning',
                'title' => 'Sin Categorías',
                'text' => 'No hay categorías de tipo "Acuerdos" disponibles. Contacta al administrador.',
                'timer' => 5000,
                'timerProgressBar' => true,
            ]);
        }
    }


    protected $rules = [
        'nombre' => 'required|file|mimes:pdf|max:10240',
        'fecha_aprobacion' => 'required|date|after:1900-01-01|before:2100-12-31',
        'categoria_id' => 'required|exists:categoria_instrumentos,id,tipo_categoria,Acuerdos',
        'observacion' => 'required|string|max:1000', 
    ];

    protected $messages = [
        'nombre.required' => 'Debe seleccionar un archivo PDF.',
        'nombre.file' => 'El archivo debe ser válido.',
        'nombre.mimes' => 'El archivo debe tener formato PDF.',
        'nombre.max' => 'El archivo no puede exceder 10MB.',
        'fecha_aprobacion.required' => 'Debe ingresar una fecha de aprobación.',
        'fecha_aprobacion.date' => 'La fecha de aprobación no es válida.',
        'fecha_aprobacion.after' => 'La fecha no puede ser anterior a 1900.',
        'fecha_aprobacion.before' => 'La fecha no puede ser posterior al año 2100.',
        'categoria_id.required' => 'Debe seleccionar una categoría.',
        'categoria_id.exists' => 'La categoría seleccionada no existe.',
        'observacion.required' => 'Debe ingresar una observación.',
        'observacion.max' => 'La observación no puede superar los 1000 caracteres.',
    ];


 
    public function validateWithAlert()
    {
        try {
            $this->validate();

            // Validar duplicado de PDF
            if ($this->nombre instanceof \Livewire\TemporaryUploadedFile || $this->nombre instanceof \Illuminate\Http\UploadedFile) {
                $originalFileName = $this->nombre->getClientOriginalName();
                $exists = Acuerdo::where('nombre', $originalFileName)->exists();

                if ($exists) {
                    $this->dispatch('showAlert', [
                        'icon' => 'error',
                        'title' => 'Archivo Duplicado',
                        'text' => 'Ya existe un acuerdo con el archivo "' . $originalFileName . '".',
                        'timer' => 4000,
                        'timerProgressBar' => true,
                    ]);
                    return false;
                }
            }

            return true;
        } catch (ValidationException $e) {
            $errors = $e->validator->errors();
            $firstField = array_keys($errors->toArray())[0];
            $firstError = $errors->first($firstField);

            $fieldNames = [
                'nombre' => 'Archivo PDF',
                'fecha_aprobacion' => 'Fecha de Aprobación',
                'categoria_id' => 'Categoría',
                'observacion' => 'Observación',
            ];

            $fieldTitle = $fieldNames[$firstField] ?? 'Campo';

            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error en ' . $fieldTitle,
                'text' => $firstError,
                'timer' => 4000,
                'timerProgressBar' => true,
            ]);
            return false;
        }
    }

    public function save()
    {   
        if (!$this->validateWithAlert()) {
            return;
        }
        if (!$this->fecha_aprobacion || !strtotime($this->fecha_aprobacion)) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error de Fecha',
                'text' => 'Por favor selecciona una fecha válida.',
                'timer' => 3000,
                'timerProgressBar' => true,
            ]);
            return;
        }

        try {
            if ($this->nombre) {
                $originalFileName = $this->nombre->getClientOriginalName();
            
                $acuerdoExistente = Acuerdo::where('nombre', $originalFileName)->first();
                if ($acuerdoExistente) {
                    $this->dispatch('showAlert', [
                        'icon' => 'error',
                        'title' => 'Archivo duplicado',
                        'text' => 'Ya existe un acuerdo con el nombre "' . $originalFileName . '".',
                        'timer' => 4000,
                        'timerProgressBar' => true,
                    ]);
                    return;
                }          
                $sanitizedFileName = pathinfo($originalFileName, PATHINFO_FILENAME);
                $extension = $this->nombre->getClientOriginalExtension();
                $fileNameToStore = $sanitizedFileName . '_' . time() . '.' . $extension;

                $filePath = $this->nombre->storeAs('acuerdos', $fileNameToStore, 'public');
            
                Acuerdo::create([
                    'nombre' => $originalFileName,
                    'categoria_instrumento_id' => $this->categoria_id,
                    'ruta' => $filePath,
                    'fecha_aprobacion' => Carbon::parse($this->fecha_aprobacion),
                    'fecha_importacion' => Carbon::now(),
                    'observacion' => $this->observacion,
                ]);
                $this->dispatch('showAlert', [
                    'icon' => 'success',
                    'title' => 'Éxito',
                    'text' => 'Acuerdo cargado correctamente.',
                    'timer' => 3000,
                    'timerProgressBar' => true,
                ]);             
                $this->reset(['nombre', 'fecha_aprobacion', 'categoria_id', 'observacion']);
                $this->dispatch('redirectAfterSave');
                return $this->redirect(route('admin.acuerdos.index'), navigate: true);

            } else {
                $this->dispatch('showAlert', [
                    'icon' => 'error',
                    'title' => 'Error',
                    'text' => 'No se pudo subir el archivo PDF.',
                    'timer' => 3000,
                    'timerProgressBar' => true,
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error inesperado',
                'text' => 'Ocurrió un error al guardar: ' . $e->getMessage(),
                'timer' => 5000,
                'timerProgressBar' => true,
            ]);
        }
    }

    public function cancel()
    {
        return $this->redirect(route('admin.acuerdos.index'), navigate: true);
    }

    public function limpiar()
    {
        $this->reset(['nombre', 'fecha_aprobacion', 'categoria_id', 'observacion']);
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
<div class="mt-6"> <!-- separa del nav superior -->
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            [
                'name' => 'Dashboard',
                'route' => route('admin.dashboard'),
            ],
            [
                'name' => 'Importar Acuerdo',
            ],
        ]" />
    </x-slot>
</div>

        @include('livewire.pages.admin.acuerdos.form.form', ['mode' => $mode])

</div>
