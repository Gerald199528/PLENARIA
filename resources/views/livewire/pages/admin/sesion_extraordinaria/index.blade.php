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
      <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            [
                'name' => 'Dashboard',
                'route' => route('admin.dashboard'),
            ],
            [
                'name' => 'Sesiones Extra Ordinarias',
            ],
        ]" />
    </x-slot>    

    <!-- Botón Nueva Sesión -->
    @can('create-sesion_extraordinaria')
       <x-slot name="action">
        <div class="mt-4">
            <a href="{{ route('admin.sesion_extraordinaria.create') }}"  wire:navigate class="inline-flex items-center gap-2 px-4 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm md:text-base bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400">
                <i class="fa-solid fa-arrow-right-to-file animate-bounce"></i>
                <span class="hidden sm:inline">Importar Sesión</span>
                <span class="sm:hidden">Importar</span>
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
