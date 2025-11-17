<?php

use Livewire\Volt\Component;
use App\Models\SesionExtraordinaria;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

new class extends Component {
    
 

    public function deleteSesionExtraordinaria(SesionExtraordinaria $sesionExtraordinaria)
    {
        try {
            // Eliminar archivo físico si existe
            if ($sesionExtraordinaria->ruta) {
                $path = $sesionExtraordinaria->ruta;
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            // Eliminar el registro de la base de datos
            $sesionExtraordinaria->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Eliminada',
                'text' => 'La sesión extraordinaria y su archivo se eliminaron correctamente.',
                'timer' => '2000',
                'timerProgressBar' => 'true',
            ]);

            // Refrescar la tabla
            $this->dispatch('pg:eventRefresh-sesion-extraordinaria-table');

        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al eliminar la sesión extraordinaria: ' . $e->getMessage(),
                'timer' => '2000',
                'timerProgressBar' => 'true',
            ]);
        }
    }
};
?>

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
                Listado Sesiones Extraordinarias
            </span>
        </nav>
    </x-slot>

    <!-- Botón Nueva Sesión -->
    @can('create-sesion_extraordinaria')
        <x-slot name="action">
            <div class="mt-4">
                <a
                    href="{{ route('admin.sesion_extraordinaria.create') }}" 
                    wire:navigate
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400"
                >
                    <i class="fa-solid fa-arrow-right-to-file animate-bounce"></i>
                    Importar Sesión
                </a>
            </div>
        </x-slot>
    @endcan

    <!-- Tabla de Sesiones Extraordinarias -->
    <x-container class="w-full px-4 mt-6">
        <livewire:sesion-extraordinaria-table />
    </x-container>
</div>

@push('scripts')
    <script>
        function confirmDelete(sesionExtraordinaria_id) {
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
                    @this.call('deleteSesionExtraordinaria', sesionExtraordinaria_id);
                }
            });
        }
    </script>
@endpush
