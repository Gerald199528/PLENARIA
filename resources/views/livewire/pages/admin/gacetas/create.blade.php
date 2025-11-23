<?php

namespace App\Http\Livewire\Admin;

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Models\Gaceta;
use Livewire\Attributes\Title;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

new #[Title('Cargar Gaceta')] class extends Component
{
    use WithFileUploads;
    public $mode = 'create'; 
    public $nombre; 
    public $categoria; 
    public $fecha_aprobacion; 
    public $observacion = ''; 

    protected $rules = [
        'nombre' => 'required|file|mimes:pdf|max:10240',
        'fecha_aprobacion' => 'required|date|after:1900-01-01|before:2100-12-31',
        'categoria' => 'required|string|max:255',   
        'observacion' => 'required|string|max:1000',
    ];

    protected $messages = [
        'nombre.required' => 'Debe seleccionar un archivo PDF.',
        'nombre.file' => 'El archivo debe ser válido.',
        'nombre.mimes' => 'El archivo debe estar en formato PDF.',
        'nombre.max' => 'El archivo no puede exceder los 10MB.',
        'categoria.required' => 'Debe seleccionar una categoría.',
        'categoria.max' => 'La categoría no puede exceder 255 caracteres.',
        'fecha_aprobacion.required' => 'Debe ingresar una fecha de aprobación.',
        'fecha_aprobacion.date' => 'La fecha de aprobación no es válida.',
        'fecha_aprobacion.after' => 'La fecha no puede ser anterior a 1900.',
        'fecha_aprobacion.before' => 'La fecha no puede ser posterior al año 2100.',
        'observacion.max' => 'La observación no puede superar los 1000 caracteres.',
        'observacion.required' => 'Campo Obligatorio',
    ];


    public function validateWithAlert()
    {
        try {
            $this->validate();

            // Validar duplicado de PDF
            if ($this->nombre instanceof \Livewire\TemporaryUploadedFile || $this->nombre instanceof \Illuminate\Http\UploadedFile) {
                $originalFileName = $this->nombre->getClientOriginalName();
                $exists = Gaceta::where('nombre', $originalFileName)->exists();

                if ($exists) {
                    $this->dispatch('showAlert', [
                        'icon' => 'error',
                        'title' => 'Archivo Duplicado',
                        'text' => 'Ya existe una gaceta con el archivo "' . $originalFileName . '".',
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
                'categoria' => 'Categoría',
                'fecha_aprobacion' => 'Fecha de Aprobación',
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

                $gacetaExistente = Gaceta::where('nombre', $originalFileName)->first();
                if ($gacetaExistente) {
                    $this->dispatch('showAlert', [
                        'icon' => 'error',
                        'title' => 'Archivo Duplicado',
                        'text' => 'Ya existe una gaceta con el nombre "' . $originalFileName . '".',
                        'timer' => 4000,
                        'timerProgressBar' => true,
                    ]);
                    return;
                }

                $sanitizedFileName = pathinfo($originalFileName, PATHINFO_FILENAME);
                $extension = $this->nombre->getClientOriginalExtension();
                $fileNameToStore = $sanitizedFileName . '_' . time() . '.' . $extension;
                $filePath = $this->nombre->storeAs('gacetas', $fileNameToStore, 'public');

            
                Gaceta::create([
                    'nombre' => $originalFileName,
                    'categoria' => $this->categoria,
                    'ruta' => $filePath,
                    'fecha_aprobacion' => Carbon::parse($this->fecha_aprobacion),
                    'fecha_importacion' => Carbon::now(),
                    'observacion' => $this->observacion,
                ]);
            
                $this->dispatch('showAlert', [
                    'icon' => 'success',
                    'title' => 'Éxito',
                    'text' => 'Gaceta cargada correctamente.',
                    'timer' => 3000,
                    'timerProgressBar' => true,
                ]);
            
                $this->reset(['nombre', 'fecha_aprobacion', 'categoria', 'observacion']);
                $this->dispatch('redirectAfterSave');
                return $this->redirect(route('admin.gacetas.index'), navigate: true);

            } else {
                $this->dispatch('showAlert', [
                    'icon' => 'error',
                    'title' => 'Error',
                    'text' => 'El archivo PDF no se pudo subir.',
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
        return $this->redirect(route('admin.gacetas.index'), navigate: true);
    }

    public function limpiar()
    {
        $this->reset(['nombre', 'fecha_aprobacion', 'categoria', 'observacion']);
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
                'name' => 'Importar Gacetas',
            ],
        ]" />
    </x-slot>
</div>
        @include('livewire.pages.admin.gacetas.form.form', ['mode' => $mode])
</div>
