<?php

use Livewire\Volt\Component;
use App\Models\TipoSolicitud;

new class extends Component {
    public $mode = 'create';

    public $nombre = '';
    public $descripcion = '';

    protected function rules()
    {
        return [
            'nombre' => 'required|string|max:100|unique:tipo_solicitud,nombre',
            'descripcion' => 'required|string|min:20|max:1000',
        ];
    }

    protected $messages = [
        'nombre.required' => 'El nombre del tipo es obligatorio.',
        'nombre.unique' => 'Este tipo de solicitud ya existe.',
        'nombre.max' => 'El nombre del tipo no puede exceder 100 caracteres.',
        'descripcion.required' => 'La descripción es obligatoria.',
        'descripcion.min' => 'La descripción debe tener al menos 20 caracteres.',
        'descripcion.max' => 'La descripción no puede exceder 1000 caracteres.',
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
                'nombre' => 'Nombre del Tipo',
                'descripcion' => 'Descripción',
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
            TipoSolicitud::create([
                'nombre' => trim($this->nombre),
                'descripcion' => trim($this->descripcion),
                'activo' => true,
            ]);

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Éxito',
                'text' => 'Tipo de solicitud registrado con éxito.',
                'timer' => 2000,
                'timerProgressBar' => true,
            ]);

            return $this->redirect(route('admin.tipos_solicitud.index'), navigate: true);

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

    public function limpiar()
    {
        $this->reset([
            'nombre',
            'descripcion',
        ]);
        $this->resetValidation();

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

    public function cancel()
    {
        return $this->redirect(route('admin.tipos_solicitud.index'), navigate: true);
    }
};
?>

<div>
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => 'Crear Tipo de Solicitud'],
        ]" />
    </x-slot>

    @include('livewire.pages.admin.tipos_solicitud.form.form', ['mode' => $mode])
</div>
