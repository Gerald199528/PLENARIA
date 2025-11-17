<?php

use Livewire\Volt\Component;
use App\Models\Ordenanza;
use Livewire\Attributes\Title;
use Illuminate\Support\Carbon;

new #[Title('Editar Ordenanza')] class extends Component
{
    public Ordenanza $ordenanza;

    public $fecha_aprobacion;
    public $observacion;

    protected $rules = [
        'fecha_aprobacion' => 'required|date',
        'observacion' => 'nullable|string|max:1000',
    ];

    public function mount(Ordenanza $ordenanza)
    {
        $this->ordenanza = $ordenanza->load('categoria');
        $this->fecha_aprobacion = null; // obligatorio que el usuario lo ingrese
        $this->observacion = $this->ordenanza->observacion;
    }

    public function save()
    {
        $this->validate();

        $this->ordenanza->update([
            'fecha_aprobacion' => $this->fecha_aprobacion,
            'observacion' => $this->observacion,
        ]);

        $this->dispatch('showAlert', [
            'icon' => 'success',
            'title' => 'Éxito',
            'text' => 'Ordenanza actualizada correctamente.',
            'timer' => '2000',
            'timerProgressBar'=> 'true',
        ]);

        return $this->redirect(route('admin.ordenanzas.index'), navigate: true);
    }

    public function cancel()
    {
        return $this->redirect(route('admin.ordenanzas.index'), navigate: true);
    }
};
?>

<div>

   
<div class="mt-6"> <!-- separa del nav superior -->
    <nav class="flex items-center text-sm font-medium text-gray-600 dark:text-gray-300 space-x-2" aria-label="Breadcrumb">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 flex items-center gap-1">
            <x-icon name="home" class="w-4 h-4" />
            Dashboard
        </a>

        <!-- Separador -->
        <span class="text-gray-400 dark:text-gray-500">/</span>

        <!-- Sección actual -->
        <span class="text-gray-700 dark:text-gray-200 flex items-center gap-1">
            <x-icon name="document-text" class="w-4 h-4" />
           Editar  Ordenanzas
        </span>
    </nav>
</div>

  @include('livewire.pages.admin.ordenanzas.form.form_edit')



</div>