<?php

use Livewire\Volt\Component;
use App\Models\Acuerdo;
use Illuminate\Support\Facades\Storage;

new class extends Component {

    public function deleteAcuerdo(Acuerdo $acuerdo)
    {
        try {
            // Eliminar archivo físico si existe
            if ($acuerdo->ruta) {
                $path = $acuerdo->ruta; // ejemplo: "acuerdos/miarchivo.pdf"
    
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            // Eliminar el registro de la BD
            $acuerdo->delete();

            // Alerta de éxito
            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Eliminado',
                'text' => 'El acuerdo y su archivo se eliminaron correctamente',
            ]);

            // Refrescar la tabla automáticamente
            $this->dispatch('pg:eventRefresh-acuerdos-tablet');


        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar el acuerdo',
            ]);
        }
    }

};
?>
<div class="mt-6"> <!-- margen superior para separar del nav -->

<!-- Breadcrumbs -->
<x-slot name="breadcrumbs">
    <nav class="flex items-center text-sm font-medium text-gray-600 dark:text-gray-300 space-x-2" aria-label="Breadcrumb">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 flex items-center gap-1">
            <x-icon name="home" class="w-4 h-4" />
            Dashboard
        </a>

        <!-- Separador -->
        <span class="text-gray-400 dark:text-gray-500">/</span>

        <!-- Sección actual -->
        <span class="text-gray-700 dark:text-gray-200 flex items-center gap-1">
            <x-icon name="document-text" class="w-4 h-4" />
            Acuerdos
        </span>
    </nav>
</x-slot>

<!-- Botón importar -->
@can('create-acuerdos')
<x-slot name="action">
    <div class="mt-4"> <!-- margen entre breadcrumbs y botón -->
        <a


 href='{{ route('admin.acuerdos.create') }}'
           wire:navigate


          
            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400"
        >
            <i class="fa-solid fa-arrow-up-from-bracket animate-bounce"></i>
            Importar Acuerdos
    </a>
    </div>
</x-slot>
@endcan

<!-- Tabla de Acuerdos -->
<x-container class="w-full px-4 mt-6">
    <livewire:acuerdos-tablet />
</x-container>

<!-- Scripts eliminar -->
@push('scripts')  
    <script>    
        function confirmDelete(acuerdo_id) {
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
                    @this.call('deleteAcuerdo', acuerdo_id);
                }
            });
        }
    </script>
@endpush

</div>
