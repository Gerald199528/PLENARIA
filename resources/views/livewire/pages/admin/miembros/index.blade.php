<?php

use Livewire\Volt\Component;
use App\Models\Miembro;

new class extends Component {

    public function deleteMiembro($miembroId)
    {
        try {
            // Buscar el miembro en la base de datos
            $miembro = Miembro::findOrFail($miembroId);

            // Eliminar el registro
            $miembro->delete();

            // Mostrar alerta de éxito
            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Eliminado',
                'text' => 'El miembro se eliminó correctamente.',
            ]);

            // Refrescar la tabla Livewire automáticamente
            $this->dispatch('pg:eventRefresh-miembro-table');

        } catch (\Exception $e) {
            // Mostrar alerta de error si algo falla
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar el miembro: ' . $e->getMessage(),
            ]);
        }
    }
};
 ?>

<div>
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            [
                'name' => 'Dashboard',
                'route' => route('admin.dashboard'),
            ],
            [
                'name' => 'Miembros',
            ],
        ]" />
    </x-slot>


<!-- Botón Nuevo Perfil -->
@can('create-concejal')
<x-slot name="action">
    <div class="mt-2 sm:mt-3 md:mt-4">
        <a
           href="{{ route('admin.miembros.create') }}" 
           wire:navigate
           class="inline-flex items-center gap-1.5 sm:gap-2 md:gap-3 px-3 sm:px-4 md:px-6 py-1.5 sm:py-2 md:py-3 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-lg sm:rounded-xl shadow-md sm:shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400 text-xs sm:text-sm md:text-base"
        >
            <i class="fa-solid fa-users animate-bounce text-xs sm:text-sm md:text-base flex-shrink-0"></i>
            Añadir Miembro
        </a>
    </div>
</x-slot>
@endcan

    <!-- Tabla de Consejales -->
    <x-container class="w-full px-4 mt-6">
        <livewire:miembro-table />
    </x-container>




@push('scripts')
<script>
    function confirmDeleteMiembro(miembro_id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'No podrás revertir esto. Se eliminará el miembro perteneciente a esta comisión.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('deleteMiembro', miembro_id);
            }
        });
    }
</script>
@endpush



</div>
