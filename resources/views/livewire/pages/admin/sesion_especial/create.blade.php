<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Models\SesionEspecial;
use Carbon\Carbon;

new class extends Component {
    use WithFileUploads;

    public $mode = 'create';
    public $nombre = '';
    public $ruta;
    public $fecha_sesion = '';
    public $orador_de_orden = '';

    protected function rules()
    {
        return [
            'nombre' => 'required|string|max:255|unique:sesion_especial,nombre',
            'fecha_sesion' => 'required|date',
             'orador_de_orden' => 'required|string|max:255',
            'ruta' => 'required|file|mimes:pdf|max:10240',
          
        ];
    }

    protected $messages = [
        'nombre.required' => 'El nombre de la sesión especial es obligatorio.',
        'nombre.max' => 'El nombre no puede exceder 255 caracteres.',
        'nombre.unique' => 'Ya existe una sesión especial con este nombre.',
        'fecha_sesion.required' => 'Debe seleccionar una fecha para la sesión especial.',
        'fecha_sesion.date' => 'La fecha seleccionada no es válida.',
        'ruta.required' => 'Debe subir un archivo PDF.',
        'ruta.file' => 'El archivo debe ser un archivo válido.',
        'ruta.mimes' => 'El archivo debe ser un PDF.',
        'ruta.max' => 'El archivo PDF no puede exceder 10MB.',
        'orador_de_orden.required' => 'Debe ingresar el nombre del Orador de Orden.',
        'orador_de_orden.max' => 'El nombre del Orador de Orden no puede exceder 255 caracteres.',
    ];

    public function validateWithAlert()
    {
        try {
            $this->validate($this->rules());

            if ($this->ruta instanceof \Livewire\TemporaryUploadedFile || $this->ruta instanceof \Illuminate\Http\UploadedFile) {
                $originalFileName = $this->ruta->getClientOriginalName();
                $fullPath = 'sesion_especial/' . $originalFileName;

                $exists = SesionEspecial::where('ruta', $fullPath)->exists();

                if ($exists) {
                    $this->dispatch('showAlert', [
                        'icon' => 'error',
                        'title' => 'Error en Archivo PDF',
                        'text' => 'Ya existe un PDF con este nombre registrado.',
                        'timer' => 5000,
                        'timerProgressBar' => true,
                    ]);
                    return false;
                }
            }
            return true;
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors();
            $firstField = array_keys($errors->toArray())[0];
            $firstError = $errors->first($firstField);
            $fieldNames = [
                'nombre' => 'Nombre',
                'fecha_sesion' => 'Fecha de Sesión',
                'ruta' => 'Archivo PDF',
                'orador_de_orden' => 'Orador de Orden',
            ];
            $fieldTitle = $fieldNames[$firstField] ?? 'Campo';
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error en ' . $fieldTitle,
                'text' => $firstError,
                'timer' => 5000,
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
            if ($this->ruta instanceof \Livewire\TemporaryUploadedFile || $this->ruta instanceof \Illuminate\Http\UploadedFile) {
                $originalFileName = $this->ruta->getClientOriginalName();
                $filePath = $this->ruta->storeAs('sesion_especial', $originalFileName, 'public');
                if (!$filePath) {
                    throw new \Exception('No se pudo guardar el archivo en el servidor.');
                }
            } else {
                $type = is_object($this->ruta) ? get_class($this->ruta) : gettype($this->ruta);
                throw new \Exception('Tipo de archivo inválido: ' . $type);
            }

            SesionEspecial::create([
                'nombre' => $this->nombre,
                'ruta' => $filePath,
                'fecha_sesion' => Carbon::parse($this->fecha_sesion),
                'orador_de_orden' => $this->orador_de_orden,
            ]);

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Éxito',
                'text' => 'Sesión especial guardada correctamente.',
                'timer' => 2000,
                'timerProgressBar' => true,
            ]);

            return $this->redirect(route('admin.sesion_especial.index'), navigate: true);

        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al guardar: ' . $e->getMessage(),
                'timer' => 3000,
                'timerProgressBar' => true,
            ]);
        }
    }

    public function limpiar()
    {
        $this->reset(['nombre', 'ruta', 'fecha_sesion', 'orador_de_orden']);
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
        return $this->redirect(route('admin.sesion_especial.index'), navigate: true);
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
                'name' => 'Registrar Sesión Especial',
            ],
        ]" />
    </x-slot>  
    @include('livewire.pages.admin.sesion_especial.form.form', ['mode' => $mode])
</div>
