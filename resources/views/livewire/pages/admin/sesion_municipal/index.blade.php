<?php

use Livewire\Volt\Component;
use App\Models\SesionMunicipal;

new class extends Component {
    
    public function deleteSesionMunicipal(SesionMunicipal $sesion_municipal)
    {
        try {
            // Eliminar el registro de la base de datos
            $sesion_municipal->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Eliminada',
                'text' => 'La Agenda se eliminó correctamente.',
                'timer' => '2000',
                'timerProgressBar' => 'true',
            ]);

            // Refrescar la tabla
            $this->dispatch('pg:eventRefresh-sesion-municipal-table');

        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al eliminar la sesión municipal: ' . $e->getMessage(),
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
            ['name' => 'Agenda Municipal'],
        ]" />
    </x-slot>

    @can('create-sesion_municipal')
        <x-slot name="action">
            <div class="mt-4">
                <a
                   href="{{ route('admin.sesion_municipal.create') }}" 
                   wire:navigate
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400"
                >
                   <i class="fa-solid fa-calendar-day animate-bounce"></i>
                   Agendar Sesión 
                </a>
            </div>
        </x-slot>
    @endcan

    <x-container class="w-full px-4 mt-6">
        <livewire:sesion-municipal-table />
    </x-container>
</div>

@push('scripts')
    <script>
        function confirmDelete(sesion_municipal_id) {
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
                    @this.call('deleteSesionMunicipal', sesion_municipal_id);
                }
            });
        }
    </script>
@endpush