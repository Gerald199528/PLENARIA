<?php

use Livewire\Volt\Component;
use App\Models\CategoriaParticipacion;

new class extends Component {
    
    public function deleteCategoriaParticipacion(CategoriaParticipacion $categorias_participacion)
    {
        try {
            // Eliminar el registro de la base de datos
            $categorias_participacion->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Eliminada',
                'text' => 'La categoría se eliminó correctamente.',
                'timer' => '2000',
                'timerProgressBar' => 'true',
            ]);

            // Refrescar la tabla
            $this->dispatch('pg:eventRefresh-categoria-participacion-table');

        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al eliminar la categoría: ' . $e->getMessage(),
                'timer' => '2000',
                'timerProgressBar' => 'true',
            ]);
        }
    }
};
?>

<div>
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => 'Categorías Participación'],
        ]" />
    </x-slot>

    @can('create-categorias_participacion')
        <x-slot name="action">
            <div class="mt-4">
                <a
                   href="{{ route('admin.categorias_participacion.create') }}" 
                   wire:navigate
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400"
                >
                   <i class="fa-solid fa-layer-group animate-bounce"></i>
                   Crear Categoría
                </a>
            </div>
        </x-slot>
    @endcan

    <x-container class="w-full px-4 mt-6">
        <livewire:categoria-participacion-table />
    </x-container>
</div>

@push('scripts')
    <script>
        function confirmDelete(categorias_participacion_id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'No podrás revertir esto!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('deleteCategoriaParticipacion', categorias_participacion_id);
                }
            });
        }
    </script>
@endpush