<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Models\SesionExtraordinaria;
use Carbon\Carbon;

new class extends Component {
    use WithFileUploads;

    public $mode = 'edit';
    public $sesionExtraordinaria;
    public $nombre = '';
    public $ruta = null;
    public $fecha_sesion = '';

    public function mount($sesion_extraordinaria = null)
    {
        if ($sesion_extraordinaria) {
            $sesion = SesionExtraordinaria::find($sesion_extraordinaria);
            if (!$sesion) {
                abort(404, 'No se encontró la sesión extraordinaria a editar');
            }
            $this->sesionExtraordinaria = $sesion;
            $this->mode = 'edit';
        } else {
            $this->sesionExtraordinaria = new SesionExtraordinaria();
            $this->mode = 'create';
        }

        $this->nombre = $this->sesionExtraordinaria->nombre ?? '';
        $this->fecha_sesion = $this->sesionExtraordinaria->fecha_sesion
            ? $this->sesionExtraordinaria->fecha_sesion->format('Y-m-d\TH:i')
            : '';
        $this->ruta = null;
    }



    protected function rules()
    {
        return [
            'nombre' => 'required|string|max:255|unique:sesion_extraordinaria,nombre',
            'fecha_sesion' => 'required|date',
             'ruta' => 'nullable|file|mimes:pdf|max:10240',
        ];
    }

    protected $messages = [
        'nombre.required' => 'El nombre de la sesión extraordinaria es obligatorio.',
        'nombre.max' => 'El nombre no puede exceder 255 caracteres.',
        'nombre.unique' => 'Ya existe una sesión extraordinaria con este nombre.',
        'fecha_sesion.required' => 'Debe seleccionar una fecha para la sesión extraordinaria.',
        'fecha_sesion.date' => 'La fecha seleccionada no es válida.',     
        'ruta.file' => 'El archivo debe ser un archivo válido.',
        'ruta.mimes' => 'El archivo debe ser un PDF.',
        'ruta.max' => 'El archivo PDF no puede exceder 10MB.',
    ];

    public function validateWithAlert()
    {
        try {
            $this->validate([
                'nombre' => 'required|string|max:255|unique:sesion_extraordinaria,nombre',
                'fecha_sesion' => 'required|date',
              'ruta' => 'nullable|file|mimes:pdf|max:10240',
            ]);

            if ($this->ruta instanceof \Livewire\TemporaryUploadedFile || $this->ruta instanceof \Illuminate\Http\UploadedFile) {
                $originalFileName = $this->ruta->getClientOriginalName();
                $fullPath = 'sesion_extraordinaria/' . $originalFileName;

                $exists = SesionExtraordinaria::where('ruta', $fullPath)->exists();

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
            // Si se está editando y hay un nuevo archivo
            if ($this->ruta) {
                if ($this->sesionExtraordinaria->ruta && \Storage::exists('public/' . $this->sesionExtraordinaria->ruta)) {
                    \Storage::delete('public/' . $this->sesionExtraordinaria->ruta);
                }

                if ($this->ruta instanceof \Livewire\TemporaryUploadedFile || $this->ruta instanceof \Illuminate\Http\UploadedFile) {
                    $nombreOriginal = $this->ruta->getClientOriginalName();
                    $rutaArchivo = $this->ruta->storeAs('sesion_extraordinaria', $nombreOriginal, 'public');
                    $this->sesionExtraordinaria->ruta = $rutaArchivo;
                } else {
                    throw new \Exception('Tipo de archivo inválido al intentar actualizar.');
                }
            }

            $this->sesionExtraordinaria->nombre = $this->nombre;
            $this->sesionExtraordinaria->fecha_sesion = Carbon::createFromFormat('Y-m-d\TH:i', $this->fecha_sesion);
            $this->sesionExtraordinaria->save();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => '¡Éxito!',
                'text' => 'Sesión extraordinaria actualizada correctamente.',
                'timer' => 3000,
                'timerProgressBar' => true,
            ]);

        } else {
            // Si se está creando una nueva sesión
            if (!$this->ruta) {
                $this->addError('ruta', 'Debe subir un PDF.');
                return;
            }

            if ($this->ruta instanceof \Livewire\TemporaryUploadedFile || $this->ruta instanceof \Illuminate\Http\UploadedFile) {
                $nombreOriginal = $this->ruta->getClientOriginalName();
                $rutaArchivo = $this->ruta->storeAs('sesion_extraordinaria', $nombreOriginal, 'public');
            } else {
                throw new \Exception('Tipo de archivo inválido al intentar guardar.');
            }

            SesionExtraordinaria::create([
                'nombre' => $this->nombre,
                'fecha_sesion' => Carbon::createFromFormat('Y-m-d\TH:i', $this->fecha_sesion),
                'ruta' => $rutaArchivo,
            ]);

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => '¡Éxito!',
                'text' => 'Sesión extraordinaria creada correctamente.',
                'timer' => 3000,
                'timerProgressBar' => true,
            ]);
        }

        return $this->redirect(route('admin.sesion_extraordinaria.index'), navigate: true);

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
    $this->reset(['nombre', 'fecha_sesion', 'ruta']);

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
        return $this->redirect(route('admin.sesion_extraordinaria.index'), navigate: true);
    }
};
?>

<div>
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => 'Sesiones Ordinarias', 'route' => route('admin.sesion_extraordinaria.index')],
            ['name' => 'Editar sesión Extra Ordinaria'],
        ]" />
    </x-slot>

    @include('livewire.pages.admin.sesion_extraordinaria.form.form', [
        'mode' => $mode,
        'sesionExtraordinaria' => $sesionExtraordinaria
    ])
</div>
