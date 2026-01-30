<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Models\SesionSolemne;
use Carbon\Carbon;


new class extends Component {
    use WithFileUploads;

    public $mode = 'edit';
    public $sesionSolemne;
    public $nombre = '';
    public $ruta = null;
    public $fecha_sesion = '';

    public function mount($sesion_solemne = null)
    {
        if ($sesion_solemne) {
            $sesion = SesionSolemne::find($sesion_solemne);
            if (!$sesion) {
                abort(404, 'No se encontró la sesión solemne a editar');
            }
            $this->sesionSolemne = $sesion;
            $this->mode = 'edit';
        } else {
            $this->sesionSolemne = new SesionSolemne();
            $this->mode = 'create';
        }

        $this->nombre = $this->sesionSolemne->nombre ?? '';
 if (!empty($this->sesionSolemne->fecha_sesion)) {
    $this->fecha_sesion = \Carbon\Carbon::parse($this->sesionSolemne->fecha_sesion)->format('Y-m-d\TH:i');
} else {
    $this->fecha_sesion = '';
}
    }

    protected function rules()
    {
        return [
            'nombre' => 'required|string|max:255|unique:sesion_solemne,nombre',
            'fecha_sesion' => 'required|date',
             'ruta' => 'nullable|file|mimes:pdf|max:10240',
        ];
    }

    protected $messages = [
        'nombre.required' => 'El nombre de la sesión solemne es obligatorio.',
        'nombre.max' => 'El nombre no puede exceder 255 caracteres.',
        'nombre.unique' => 'Ya existe una sesión solemne con este nombre.',
        'fecha_sesion.required' => 'Debe seleccionar una fecha para la sesión solemne.',
        'fecha_sesion.date' => 'La fecha seleccionada no es válida.',      
        'ruta.file' => 'El archivo debe ser un archivo válido.',
        'ruta.mimes' => 'El archivo debe ser un PDF.',
        'ruta.max' => 'El archivo PDF no puede exceder 10MB.',
    ];

    public function validateWithAlert()
    {
        try {
            $this->validate([
                'nombre' => 'required|string|max:255|unique:sesion_solemne,nombre',
                'fecha_sesion' => 'required|date',
                'ruta' => 'nullable|file|mimes:pdf|max:10240',
            ]);

            if ($this->ruta instanceof \Livewire\TemporaryUploadedFile || $this->ruta instanceof \Illuminate\Http\UploadedFile) {
                $originalFileName = $this->ruta->getClientOriginalName();
                $fullPath = 'sesion_solemne/' . $originalFileName;

                $exists = SesionSolemne::where('ruta', $fullPath)->exists();

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
                    if ($this->sesionSolemne->ruta && \Storage::exists('public/' . $this->sesionSolemne->ruta)) {
                        \Storage::delete('public/' . $this->sesionSolemne->ruta);
                    }

                    if ($this->ruta instanceof \Livewire\TemporaryUploadedFile || $this->ruta instanceof \Illuminate\Http\UploadedFile) {
                        $nombreOriginal = $this->ruta->getClientOriginalName();
                        $rutaArchivo = $this->ruta->storeAs('sesion_solemne', $nombreOriginal, 'public');
                        $this->sesionSolemne->ruta = $rutaArchivo;
                    } else {
                        throw new \Exception('Tipo de archivo inválido al intentar actualizar.');
                    }
                }

                $this->sesionSolemne->nombre = $this->nombre;
                $this->sesionSolemne->fecha_sesion = Carbon::createFromFormat('Y-m-d\TH:i', $this->fecha_sesion);
                $this->sesionSolemne->save();

                $this->dispatch('showAlert', [
                    'icon' => 'success',
                    'title' => '¡Éxito!',
                    'text' => 'Sesión solemne actualizada correctamente.',
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
                    $rutaArchivo = $this->ruta->storeAs('sesion_solemne', $nombreOriginal, 'public');
                } else {
                    throw new \Exception('Tipo de archivo inválido al intentar guardar.');
                }

                SesionSolemne::create([
                    'nombre' => $this->nombre,
                    'fecha_sesion' => Carbon::createFromFormat('Y-m-d\TH:i', $this->fecha_sesion),
                    'ruta' => $rutaArchivo,
                ]);

                $this->dispatch('showAlert', [
                    'icon' => 'success',
                    'title' => '¡Éxito!',
                    'text' => 'Sesión solemne creada correctamente.',
                    'timer' => 3000,
                    'timerProgressBar' => true,
                ]);
            }

            return $this->redirect(route('admin.sesion_solemne.index'), navigate: true);

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
        return $this->redirect(route('admin.sesion_solemne.index'), navigate: true);
    }
};
?>

<div>
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => 'Sesiones Solemne', 'route' => route('admin.sesion_solemne.index')],
            ['name' => 'Editar sesión Solemne'],
        ]" />
    </x-slot>
    @include('livewire.pages.admin.sesion_solemne.form.form', [
        'mode' => $mode,
        'sesionSolemne' => $sesionSolemne
    ])
</div>
