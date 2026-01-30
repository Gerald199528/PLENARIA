<?php

use Livewire\Volt\Component;
use App\Models\CategoriaParticipacion;

new class extends Component {
    public $mode = 'create';
    
    public $nombre = '';
    public $descripcion = '';

    protected function rules()
    {
        return [
            'nombre' => 'required|string|max:255|regex:/^[a-zA-ZÀ-ÿ\s\-]+$/|unique:categorias_participacion,nombre',
            'descripcion' => 'required|string|min:20|max:1000|regex:/^[a-zA-ZÀ-ÿ\s\.\,\;\:\!\?\(\)\-]+$/',
        ];
    }

    protected $messages = [
        'nombre.required' => 'El nombre de la categoría es obligatorio.',
        'nombre.unique' => 'El nombre de la categoría ya ha sido registrado.',
        'nombre.max' => 'El nombre de la categoría no puede exceder 255 caracteres.',
        'nombre.regex' => 'El nombre de la categoría solo permite letras, espacios y guiones.',
        'descripcion.required' => 'La descripción de la categoría es obligatoria.',
        'descripcion.min' => 'La descripción de la categoría debe tener al menos 20 caracteres.',
        'descripcion.max' => 'La descripción de la categoría no puede exceder 1000 caracteres.',
        'descripcion.regex' => 'La descripción de la categoría contiene caracteres inválidos.',
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
                'nombre' => 'Nombre de la Categoría',
                'descripcion' => 'Descripción de la Categoría',
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
            CategoriaParticipacion::create([
                'nombre' => trim($this->nombre),
                'descripcion' => trim($this->descripcion),
            ]);

            $this->limpiar(false);

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Éxito',
                'text' => 'Categoría registrada con éxito.',
                'timer' => 2000,
                'timerProgressBar' => true,
            ]);

            return $this->redirect(route('admin.categorias_participacion.index'), navigate: true);

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

    public function limpiar($showAlert = true)
    {
        $this->reset([
            'nombre',
            'descripcion',
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
        return $this->redirect(route('admin.categorias_participacion.index'), navigate: true);
    }
}; ?>

<div>
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => 'Registrar Categoría'],
        ]" />
    </x-slot>
    
    @include('livewire.pages.admin.categorias_participacion.form.form', ['mode' => $mode])
</div>