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

    <!-- Breadcrumbs -->
    <x-slot name="breadcrumbs">
        <nav class="flex items-center text-sm font-medium text-gray-600 dark:text-gray-300 space-x-2" aria-label="Breadcrumb">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 flex items-center gap-1">
                <x-icon name="home" class="w-6 h-" />
                Dashboard
            </a>
            <span class="text-gray-400 dark:text-gray-500">/</span>
            <span class="text-gray-700 dark:text-gray-200 flex items-center gap-1">
                <x-icon name="document-text" class="w-4 h-4" />
             Perfil  Cronista
            </span>
        </nav>
    </x-slot>
<br>

    <!-- Incluir formulario con diseño completo -->
    @include('livewire.pages.admin.cronistas.form.from-view', ['cronista' => $cronista ?? null])

</div>
