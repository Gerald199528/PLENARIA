<?php

use Livewire\Volt\Component;
use App\Models\Gaceta;
use Livewire\Attributes\Title;

new #[Title('Editar Gaceta')] class extends Component
{
    public Gaceta $gaceta;

    public $fecha_aprobacion;
    public $observacion;

    protected $rules = [
        'fecha_aprobacion' => 'required|date',
        'observacion'      => 'nullable|string|max:1000',
    ];

    public function mount(Gaceta $gaceta)
    {
        $this->gaceta = $gaceta;
        $this->fecha_aprobacion = $this->gaceta->fecha_aprobacion?->format('Y-m-d');
        $this->observacion = $this->gaceta->observacion;
    }

    public function save()
    {
        $this->validate();

        $this->gaceta->update([
            'fecha_aprobacion' => $this->fecha_aprobacion,
            'observacion'      => $this->observacion,
        ]);

        $this->dispatch('showAlert', [
            'icon' => 'success',
            'title' => 'Éxito',
            'text'  => 'Gaceta actualizada correctamente.',
            'timer' => '2000',
            'timerProgressBar'=> 'true',
        ]);

        return $this->redirect(route('admin.gacetas.index'), navigate: true);
    }

    public function cancel()
    {
        return $this->redirect(route('admin.gacetas.index'), navigate: true);
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
           Editar  Gacetas
        </span>
    </nav>
</div>
      @include('livewire.pages.admin.gacetas.form.edit')
</div>
