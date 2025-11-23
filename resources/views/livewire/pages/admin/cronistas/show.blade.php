<?php

use Livewire\Volt\Component;
use App\Models\Cronista;

new class extends Component {
    public $cronista;

    public function mount()
    {
        // Trae el primer cronista registrado con sus relaciones
        $this->cronista = Cronista::with(['parroquia.municipio.estado'])->first();
    }
    
}; ?>

<div>
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            [
                'name' => 'Dashboard',
                'route' => route('admin.dashboard'),
            ],
            [
                'name' => ' Perfil Cronista',
            ],
        ]" />
    </x-slot>
<br>
    <!-- Incluir formulario con diseño completo -->
    @include('livewire.pages.admin.cronistas.form.from-view', ['cronista' => $cronista ?? null])

</div>
