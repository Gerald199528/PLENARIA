<?php

use Livewire\Volt\Component;
use App\Models\Cronista;


new class extends Component {
       
 public function deleteCronista($cronistaId)
    {
        try {
            $cronista= \App\Models\Cronista::findOrFail($cronistaId);
            
            // Eliminar imagen física si existe
            if ($cronista->imagen_url) {
                $path = $cronista->imagen_url; // ejemplo: "cronista/imagen.jpg"
    
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
                }
            }

            // Eliminar el registro de la BD
            $cronista->delete();

            // Alerta de éxito
            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Eliminado',
                'text' => 'El cronista y su imagen se eliminaron correctamente',
            ]);

            // Refrescar la tabla automáticamente
            $this->dispatch('pg:eventRefresh-cronista-table');

        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar el cronista: ' . $e->getMessage(),
            ]);
        }
    }
}; ?>

<div>

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
 Listado cronistas
        </span>
    </nav>
</x-slot>




<!-- Botón Nuevo Perfil -->
@can('create-cronista')
<x-slot name="action">
    <div class="mt-4">
    
          <a  href="{{ route('admin.cronistas.create') }}"
           wire:navigate
           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400"
        >
          <i class="fa-solid fa-solid fa-user-plus animate-bounce"></i>              
            Nuevo Cronista
        </a>
    </div>
</x-slot>
@endcan


    <!-- Tabla de Ccronista -->
    <x-container class="w-full px-4 mt-6">
        <livewire:cronista-table />
    </x-container>

    <!-- Scripts eliminar -->
    @push('scripts')  
        <script>    
            function confirmDeleteCronista(cronista_id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'No podrás revertir esto! Se eliminará el cronista y su imagen.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {                 
                        @this.call('deleteCronista', cronista_id);
                    }
                });
            }
        </script>
    @endpush
</div>
