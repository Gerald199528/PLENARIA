<?php

use Livewire\Volt\Component;
use App\Models\Concejal;
new class extends Component {
    
    public function deleteConcejal($concejalId)
    {
        try {
            $concejal = \App\Models\Concejal::findOrFail($concejalId);
            
            // Eliminar imagen física si existe
            if ($concejal->imagen_url) {
                $path = $concejal->imagen_url; // ejemplo: "concejales/imagen.jpg"
    
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
                }
            }

            // Eliminar el registro de la BD
            $concejal->delete();

            // Alerta de éxito
            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Eliminado',
                'text' => 'El concejal y su imagen se eliminaron correctamente',
            ]);

            // Refrescar la tabla automáticamente
            $this->dispatch('pg:eventRefresh-concejal-table');

        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar el concejal: ' . $e->getMessage(),
            ]);
        }
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
                'name' => ' Concejales',
            ],
        ]" />
    </x-slot>
<!-- Botón Nuevo Perfil Responsivo -->
@can('create-concejal')
<x-slot name="action">
    <div class="mt-2 sm:mt-3 md:mt-4">
        <a
           href="{{ route('admin.concejales.create') }}" 
           wire:navigate
           class="inline-flex items-center gap-1.5 sm:gap-2 md:gap-3 px-3 sm:px-4 md:px-6 py-1.5 sm:py-2 md:py-3 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-lg sm:rounded-xl shadow-md sm:shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400 text-xs sm:text-sm md:text-base"
        >
            <i class="fa-solid fa-user-plus animate-bounce text-xs sm:text-sm md:text-base flex-shrink-0"></i>
            Nuevo Perfil
        </a>
    </div>
</x-slot>
@endcan

    <!-- Tabla de Consejales -->
    <x-container class="w-full px-4 mt-6">
        <livewire:concejal-table />
    </x-container>

    <!-- Scripts eliminar -->
    @push('scripts')  
        <script>    
            function confirmDeleteConcejal(concejal_id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'No podrás revertir esto! Se eliminará el concejal y su imagen.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {                 
                        @this.call('deleteConcejal', concejal_id);
                    }
                });
            }
        </script>
    @endpush
</div>
</div>
