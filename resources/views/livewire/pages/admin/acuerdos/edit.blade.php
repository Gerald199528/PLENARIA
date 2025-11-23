<?php

namespace App\Http\Livewire\Admin;

use Livewire\Volt\Component;
use App\Models\Acuerdo;
use Livewire\Attributes\Title;
use Illuminate\Support\Carbon;

new #[Title('Editar Acuerdo')] class extends Component
{
    public Acuerdo $acuerdo;
    public $mode = 'edit';
    public $fecha_aprobacion;
    public $observacion;

    protected $rules = [
        'fecha_aprobacion' => 'required|date',
        'observacion'      => 'nullable|string|max:1000',
    ];

    public function mount(Acuerdo $acuerdo)
    {
        $this->acuerdo = $acuerdo->load('categoria');
        // Carga la fecha existente en formato datetime-local (Y-m-d\TH:i)
        $this->fecha_aprobacion = $acuerdo->fecha_aprobacion
            ? $acuerdo->fecha_aprobacion->format('Y-m-d\TH:i')
            : null;
        $this->observacion = $this->acuerdo->observacion;
    }
    

    public function save()
    {
        $this->validate();

        $this->acuerdo->update([
            'fecha_aprobacion' => $this->fecha_aprobacion,
            'observacion'      => $this->observacion,
        ]);

        $this->dispatch('showAlert', [
            'icon' => 'success',
            'title' => 'Éxito',
            'text'  => 'Acuerdo actualizado correctamente.',
            'timer' => '2000',
            'timerProgressBar'=> 'true',
        ]);

        return $this->redirect(route('admin.acuerdos.index'), navigate: true);
    }

    public function cancel()
    {
        return $this->redirect(route('admin.acuerdos.index'), navigate: true);
    }
};
?>
 
<div>

<div class="mt-6"> <!-- separa del nav superior -->
     <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => 'Listado Acuerdos', 'route' => route('admin.acuerdos.index')],
            ['name' => 'Editar Acuerdo'],
        ]" />
    </x-slot>
</div>
        @include('livewire.pages.admin.acuerdos.form.form', ['mode' => $mode])
</div>