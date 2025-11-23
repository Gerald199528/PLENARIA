<?php


use Livewire\Volt\Component;
use App\Models\Gaceta;
use Illuminate\Support\Facades\Storage;

new class extends Component {

    public function deleteGaceta(Gaceta $gaceta)
    {
        try {
            // Eliminar archivo físico si existe
            if ($gaceta->ruta) {
                $path = $gaceta->ruta; // ejemplo: "gacetas/miarchivo.pdf"

                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            // Eliminar el registro de la BD
            $gaceta->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Eliminado',
                'text' => 'La gaceta y su archivo se eliminaron correctamente',
            ]);

            // Refrescar la tabla
            $this->dispatch('pg:eventRefresh-gacetas-table');

        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar la gaceta',
            ]);
        }
    }

};
 ?>
<div class="mt-6"> <!-- margen superior para separar del nav -->
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            [
                'name' => 'Dashboard',
                'route' => route('admin.dashboard'),
            ],
            [
                'name' => 'Listado Gacetas',
            ],
        ]" />
    </x-slot>

<!-- Botón importar -->
@can('create-gaceta')    
<x-slot name="action">
    <div class="mt-4 flex justify-start sm:justify-end"> <!-- margen + responsive alineación -->
        
        <a
            href="{{ route('admin.gacetas.create') }}"
            wire:navigate
            class="inline-flex items-center gap-2 
                   px-3 py-2 text-xs 
                   sm:px-4 sm:py-2 sm:text-sm 
                   md:px-6 md:py-3 md:text-base
                   bg-gradient-to-r from-blue-600 to-indigo-500 
                   text-white font-semibold rounded-lg sm:rounded-xl 
                   shadow-lg transform transition-all duration-300 
                   hover:scale-105 hover:shadow-2xl 
                   hover:from-blue-500 hover:to-indigo-600 
                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400"
        >
            <i class="fa-solid fa-arrow-up-from-bracket animate-bounce"></i>
            <span class="whitespace-nowrap">Importar Gaceta</span>
        </a>
    </div>
</x-slot>
@endcan


<!-- Tabla de Gacetas -->
<x-container class="w-full px-4 mt-6">
    <livewire:gacetas-table />
</x-container>

<!-- Scripts eliminar -->
@push('scripts')  
    <script>    
        function confirmDelete(gaceta_id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'No podrás revertir esto!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {                 
                    @this.call('deleteGaceta', gaceta_id);
                }
            });
        }
    </script>
@endpush

</div>
