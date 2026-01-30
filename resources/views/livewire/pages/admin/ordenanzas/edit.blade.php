<?php

use Livewire\Volt\Component;
use App\Models\Ordenanza;
use Livewire\Attributes\Title;
use Illuminate\Support\Carbon;

new #[Title('Editar Ordenanza')] class extends Component
{
    public Ordenanza $ordenanza;
    public $mode = 'edit';

    public $fecha_aprobacion;
    public $observacion;

    protected $rules = [
        'fecha_aprobacion' => 'required|date',
        'observacion' => 'nullable|string|max:1000',
    ];

    public function mount(Ordenanza $ordenanza)
    {
        $this->ordenanza = $ordenanza->load('categoria');
        // Carga la fecha existente en formato datetime-local (Y-m-d\TH:i)
        $this->fecha_aprobacion = $ordenanza->fecha_aprobacion
            ? $ordenanza->fecha_aprobacion->format('Y-m-d\TH:i')
            : null;
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
     <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => 'Listado Ordenanzas', 'route' => route('admin.ordenanzas.index')],
            ['name' => 'Editar Ordenanza'],
        ]" />
    </x-slot>
</div>

      @include('livewire.pages.admin.ordenanzas.form.form', ['mode' => $mode])

</div>