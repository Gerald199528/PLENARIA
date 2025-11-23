<?php

use Livewire\Volt\Component;
use Illuminate\Validation\ValidationException;
use Livewire\WithFileUploads;
use App\Models\Ordenanza;
use App\Models\CategoriaInstrumento;
use Livewire\Attributes\Title;
use Illuminate\Support\Carbon;

new #[Title('Gestionar Ordenanza')] class extends Component
{
    use WithFileUploads;

    // Propiedades del formulario
    public $mode = 'create'; // 'create' o 'edit'
    public $ordenanza = null;
    public $nombre = '';
    public $fecha_aprobacion;
    public $categoria_id;
    public $observacion = '';
    public $categorias = [];

    protected $rules = [
        'nombre' => 'required|file|mimes:pdf|max:10240',
        'fecha_aprobacion' => 'required|date|after:1900-01-01|before:2100-12-31',
        'categoria_id' => 'required|exists:categoria_instrumentos,id,tipo_categoria,Ordenanzas',
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
        'observacion.max' => 'La observación no puede superar los 1000 caracteres.',
    ];

    public function mount($ordenanza = null)
    {
        $this->categorias = CategoriaInstrumento::where('tipo_categoria', 'Ordenanzas')->get();

        if ($this->categorias->isEmpty()) {
            $this->dispatch('showAlert', [
                'icon' => 'warning',
                'title' => 'Sin Categorías',
                'text' => 'No hay categorías de tipo "Ordenanzas" disponibles. Contacta al administrador.',
                'timer' => 5000,
                'timerProgressBar' => true,
            ]);
        }

        // Si viene una ordenanza, está en modo edición
        if ($ordenanza) {
            $this->mode = 'edit';
            $this->ordenanza = $ordenanza;
            $this->fecha_aprobacion = $ordenanza->fecha_aprobacion
                ? $ordenanza->fecha_aprobacion->format('Y-m-d\TH:i')
                : null;
            $this->categoria_id = $ordenanza->categoria_instrumento_id;
            $this->observacion = $ordenanza->observacion;
        }
    }

    protected function rulesForMode()
    {
        if ($this->mode === 'edit') {
            return [
                'fecha_aprobacion' => 'required|date|after:1900-01-01|before:2100-12-31',
                'categoria_id' => 'required|exists:categoria_instrumentos,id,tipo_categoria,Ordenanzas',
                'observacion' => 'required|string|max:1000',
            ];
        }

        return $this->rules;
    }

    protected function messagesForMode()
    {
        return $this->messages;
    }

    public function validateWithAlert()
    {
        try {
            // Usar las reglas según el modo
            $this->validate($this->rulesForMode(), $this->messagesForMode());

            // Validar duplicado de PDF solo en modo create
            if ($this->mode === 'create' && $this->nombre instanceof \Livewire\TemporaryUploadedFile) {
                $originalFileName = $this->nombre->getClientOriginalName();
                $exists = Ordenanza::where('nombre', $originalFileName)->exists();

                if ($exists) {
                    $this->dispatch('showAlert', [
                        'icon' => 'error',
                        'title' => 'Archivo Duplicado',
                        'text' => 'Ya existe una ordenanza con el archivo "' . $originalFileName . '".',
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
            if ($this->mode === 'create') {
                $this->crearOrdenanza();
            } else {
                $this->actualizarOrdenanza();
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

    private function crearOrdenanza()
    {
        if (!$this->nombre) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo subir el archivo PDF.',
                'timer' => 3000,
                'timerProgressBar' => true,
            ]);
            return;
        }

        $originalFileName = $this->nombre->getClientOriginalName();
        $ordenanzaExistente = Ordenanza::where('nombre', $originalFileName)->first();

        if ($ordenanzaExistente) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Archivo duplicado',
                'text' => 'Ya existe una ordenanza con el nombre "' . $originalFileName . '".',
                'timer' => 4000,
                'timerProgressBar' => true,
            ]);
            return;
        }

        $sanitizedFileName = pathinfo($originalFileName, PATHINFO_FILENAME);
        $extension = $this->nombre->getClientOriginalExtension();
        $fileNameToStore = $sanitizedFileName . '_' . time() . '.' . $extension;

        $filePath = $this->nombre->storeAs('ordenanzas', $fileNameToStore, 'public');

        Ordenanza::create([
            'nombre' => $originalFileName,
            'ruta' => $filePath,
            'fecha_aprobacion' => Carbon::parse($this->fecha_aprobacion),
            'categoria_instrumento_id' => $this->categoria_id,
            'observacion' => $this->observacion,
            'fecha_importacion' => Carbon::now(),
        ]);

        $this->dispatch('showAlert', [
            'icon' => 'success',
            'title' => 'Éxito',
            'text' => 'Ordenanza cargada correctamente.',
            'timer' => 3000,
            'timerProgressBar' => true,
        ]);

        $this->reset(['nombre', 'fecha_aprobacion', 'categoria_id', 'observacion']);
        $this->dispatch('redirectAfterSave');
        return $this->redirect(route('admin.ordenanzas.index'), navigate: true);
    }

    private function actualizarOrdenanza()
    {
        $this->ordenanza->update([
            'fecha_aprobacion' => Carbon::parse($this->fecha_aprobacion),
            'categoria_instrumento_id' => $this->categoria_id,
            'observacion' => $this->observacion,
        ]);

        $this->dispatch('showAlert', [
            'icon' => 'success',
            'title' => 'Éxito',
            'text' => 'Ordenanza actualizada correctamente.',
            'timer' => 3000,
            'timerProgressBar' => true,
        ]);

        return $this->redirect(route('admin.ordenanzas.index'), navigate: true);
    }

    public function cancel()
    {
        return $this->redirect(route('admin.ordenanzas.index'), navigate: true);
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
    ;?>  

    <div>    
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            [
                'name' => 'Dashboard',
                'route' => route('admin.dashboard'),
            ],
            [
                'name' => 'Importar Ordenanzas',
            ],
        ]" />
    </x-slot>

    <!-- Formulario Importar Ordenanza -->
    <div class="mt-4">
            @include('livewire.pages.admin.ordenanzas.form.form', ['mode' => $mode])
    
    </div>
    </div>
