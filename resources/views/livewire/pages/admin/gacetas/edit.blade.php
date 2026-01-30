<?php

use Livewire\Volt\Component;
use App\Models\Gaceta;
use Livewire\Attributes\Title;

new #[Title('Editar Gaceta')] class extends Component
{
    public Gaceta $gaceta;
    public $mode = 'edit';
    public $fecha_aprobacion;
    public $observacion;

    protected $rules = [
        'fecha_aprobacion' => 'required|date',
        'observacion'      => 'nullable|string|max:1000',
    ];

    public function mount(Gaceta $gaceta)
    {
        $this->gaceta = $gaceta;
        // Formato correcto para datetime-local: Y-m-d\TH:i
        $this->fecha_aprobacion = $this->gaceta->fecha_aprobacion
            ? $this->gaceta->fecha_aprobacion->format('Y-m-d\TH:i')
            : null;
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
     <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => 'Listado Gacetas', 'route' => route('admin.gacetas.index')],
            ['name' => 'Editar Gacetas'],
        ]" />
    </x-slot>
</div>
          @include('livewire.pages.admin.gacetas.form.form', ['mode' => $mode])
</div>