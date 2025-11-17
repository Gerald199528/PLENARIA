<?php

use Livewire\Volt\Component;
use App\Models\CategoriaInstrumento;

new class extends Component {

    public function deleteCategoria(CategoriaInstrumento $categoria)
    {
        try {
            // Eliminar registro de la BD
            $categoria->delete();

            // Mostrar alerta de éxito
            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Eliminado',
                'text' => 'La categoría se eliminó correctamente',
            ]);

            // Refrescar la tabla
            $this->dispatch('pg:eventRefresh-categoria-intrumento-table');

        } catch (\Exception $e) {
            // Mostrar alerta de error
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar la categoría',
            ]);
        }
    }

};
?>

<div class="mt-6">

    <!-- Breadcrumbs -->
    <x-slot name="breadcrumbs">
        <nav class="flex items-center text-sm font-medium text-gray-600 dark:text-gray-300 space-x-2" aria-label="Breadcrumb">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 flex items-center gap-1">
                <x-icon name="home" class="w-4 h-4" />
                Dashboard
            </a>
            <span class="text-gray-400 dark:text-gray-500">/</span>
            <span class="text-gray-700 dark:text-gray-200 flex items-center gap-1">
                <x-icon name="document-text" class="w-4 h-4" />
                Categorías de Instrumentos
            </span>
        </nav>
    </x-slot>
<!-- Botón Nueva Categoría -->
@can('create-categoria-instrumento')
<x-slot name="action">
    <div class="mt-4">
        <a
           href="{{ route('admin.categoria-instrumentos.create') }}" 
           wire:navigate
           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400"
        >
            <i class="fa-solid fa-arrow-up-from-bracket animate-bounce"></i>
            Nueva Categoría
        </a>
    </div>
</x-slot>
@endcan




    <!-- Tabla de Categorías -->
    <x-container class="w-full px-4 mt-6">
        <livewire:categoria-intrumento-table />
    </x-container>

    <!-- Scripts eliminar -->
    @push('scripts')  
        <script>    
            function confirmDelete(categoria_id) {
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
                        @this.call('deleteCategoria', categoria_id);
                    }
                });
            }
        </script>




    @endpush

</div>
