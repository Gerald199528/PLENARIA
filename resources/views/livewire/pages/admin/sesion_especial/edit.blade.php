<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Models\SesionEspecial;
use Carbon\Carbon;

new class extends Component {
    use WithFileUploads;

    public $mode = 'edit';
    public $sesionEspecial;
    public $nombre = '';
    public $ruta = null;
    public $fecha_sesion = '';
    public $orador_de_orden = '';

    public function mount($sesion_especial = null)
    {
        if ($sesion_especial) {
            $sesion = SesionEspecial::find($sesion_especial);
            if (!$sesion) {
                abort(404, 'No se encontró la sesión especial a editar');
            }
            $this->sesionEspecial = $sesion;
            $this->mode = 'edit';
        } else {
            $this->sesionEspecial = new SesionEspecial();
            $this->mode = 'create';
        }

        $this->nombre = $this->sesionEspecial->nombre ?? '';
        $this->fecha_sesion = $this->sesionEspecial->fecha_sesion
            ? $this->sesionEspecial->fecha_sesion->format('Y-m-d\TH:i')
            : '';
        $this->orador_de_orden = $this->sesionEspecial->orador_de_orden ?? '';
        $this->ruta = null;
    }

    protected function rules()
    {
        return [
            'nombre' => 'required|string|max:255|unique:sesion_especial,nombre,' . ($this->sesionEspecial->id ?? 'NULL') . ',id',
            'fecha_sesion' => 'required|date',
            'orador_de_orden' => 'required|string|max:255',
            'ruta' => 'nullable|file|mimes:pdf|max:10240',
        ];
    }

    protected $messages = [
        'nombre.required' => 'El nombre de la sesión especial es obligatorio.',
        'nombre.max' => 'El nombre no puede exceder 255 caracteres.',
        'nombre.unique' => 'Ya existe una sesión especial con este nombre.',
        'fecha_sesion.required' => 'Debe seleccionar una fecha para la sesión especial.',
        'fecha_sesion.date' => 'La fecha seleccionada no es válida.',
        'orador_de_orden.required' => 'El campo Orador de Orden es obligatorio.',
        'ruta.file' => 'El archivo debe ser un archivo válido.',
        'ruta.mimes' => 'El archivo debe ser un PDF.',
        'ruta.max' => 'El archivo PDF no puede exceder 10MB.',
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
                'orador_de_orden' => 'Orador de Orden',
                'ruta' => 'Archivo PDF',
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
            if ($this->mode === 'edit') {
                if ($this->ruta) {
                    if ($this->sesionEspecial->ruta && \Storage::exists('public/' . $this->sesionEspecial->ruta)) {
                        \Storage::delete('public/' . $this->sesionEspecial->ruta);
                    }

                    if ($this->ruta instanceof \Livewire\TemporaryUploadedFile || $this->ruta instanceof \Illuminate\Http\UploadedFile) {
                        $nombreOriginal = $this->ruta->getClientOriginalName();
                        $rutaArchivo = $this->ruta->storeAs('sesion_especial', $nombreOriginal, 'public');
                        $this->sesionEspecial->ruta = $rutaArchivo;
                    } else {
                        throw new \Exception('Tipo de archivo inválido al intentar actualizar.');
                    }
                }

                $this->sesionEspecial->nombre = $this->nombre;
                $this->sesionEspecial->fecha_sesion = Carbon::createFromFormat('Y-m-d\TH:i', $this->fecha_sesion);
                $this->sesionEspecial->orador_de_orden = $this->orador_de_orden;
                $this->sesionEspecial->save();

                $this->dispatch('showAlert', [
                    'icon' => 'success',
                    'title' => '¡Éxito!',
                    'text' => 'Sesión especial actualizada correctamente.',
                    'timer' => 3000,
                    'timerProgressBar' => true,
                ]);

            } else {
                if (!$this->ruta) {
                    $this->addError('ruta', 'Debe subir un PDF.');
                    return;
                }

                if ($this->ruta instanceof \Livewire\TemporaryUploadedFile || $this->ruta instanceof \Illuminate\Http\UploadedFile) {
                    $nombreOriginal = $this->ruta->getClientOriginalName();
                    $rutaArchivo = $this->ruta->storeAs('sesion_especial', $nombreOriginal, 'public');
                } else {
                    throw new \Exception('Tipo de archivo inválido al intentar guardar.');
                }

                SesionEspecial::create([
                    'nombre' => $this->nombre,
                    'fecha_sesion' => Carbon::createFromFormat('Y-m-d\TH:i', $this->fecha_sesion),
                    'orador_de_orden' => $this->orador_de_orden,
                    'ruta' => $rutaArchivo,
                ]);

                $this->dispatch('showAlert', [
                    'icon' => 'success',
                    'title' => '¡Éxito!',
                    'text' => 'Sesión especial creada correctamente.',
                    'timer' => 3000,
                    'timerProgressBar' => true,
                ]);
            }

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
        $this->reset(['nombre', 'fecha_sesion', 'ruta', 'orador_de_orden']);
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
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => 'Sesiones Especiales', 'route' => route('admin.sesion_especial.index')],
            ['name' => 'Editar sesión Especial'],
        ]" />
    </x-slot>

    @include('livewire.pages.admin.sesion_especial.form.form', [
        'mode' => $mode,
        'sesionEspecial' => $sesionEspecial
    ])
</div>
