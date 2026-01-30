<?php

use Livewire\Volt\Component;
use Illuminate\View\View;
use App\Models\Ordenanza;
use Illuminate\Support\Facades\Storage;

new class extends Component 
{
    // Se ejecuta antes de renderizar la vista
    public function rendering(View $view)
    {
        $view->title('Ordenanzas');
    }
    public function deleteOrdenanza(Ordenanza $ordenanza)
    {
        try {
            // Eliminar archivo físico si existe
            if ($ordenanza->ruta) {
                $path = $ordenanza->ruta; // ejemplo: "ordenanzas/miarchivo.pdf"
    
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
    
            // Eliminar el registro de la BD
            $ordenanza->delete();
    
            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Eliminado',
                'text' => 'La ordenanza y su archivo se eliminaron correctamente',
            ]);     
            $this->dispatch('pg:eventRefresh-ordenanzas-table');
    
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar la ordenanza',
            ]);
        }
    }
    
    
};?>
<div class="mt-6"> <!-- margen superior para separar del nav -->

    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            [
                'name' => 'Dashboard',
                'route' => route('admin.dashboard'),
            ],
            [
                'name' => 'Listado Ordenanzas',
            ],
        ]" />
    </x-slot>


    <!-- Botón acción -->
    @can('create-ordenanza')    
    <x-slot name="action">
        <div class="mt-4 flex justify-center sm:justify-start">
            <a href="{{ route('admin.ordenanzas.create') }}" wire:navigate
                class="flex items-center gap-1 sm:gap-2 
                       px-2 sm:px-3 md:px-5 
                       py-1 sm:py-2 md:py-3 
                       bg-gradient-to-r from-blue-600 to-indigo-500
                       text-white font-semibold rounded-md sm:rounded-lg md:rounded-xl 
                       shadow-md hover:shadow-xl transform transition-all duration-300
                       hover:scale-105 hover:from-blue-500 hover:to-indigo-600
                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400
                       text-xs sm:text-sm md:text-base 
                       w-auto max-w-full">
                <i class="fa-solid fa-arrow-up-from-bracket animate-bounce 
                          text-xs sm:text-sm md:text-lg"></i>
                <span class="truncate">Importar</span>
                <span class="hidden sm:inline">Ordenanza</span>
            </a>
        </div>
    </x-slot>
    @endcan

    <!-- Contenedor responsive tabla -->
    <div class="overflow-x-auto overflow-y-auto max-h-[80vh] 
                p-2 sm:p-4 bg-white dark:bg-gray-800 
                rounded-lg sm:rounded-xl shadow-md sm:shadow-lg">
        <livewire:ordenanzas-table />
    </div>

    @push('scripts')  
        <script>    
            function confirmDelete(ordenanza_id) {
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
                        @this.call('deleteOrdenanza', ordenanza_id);
                    }
                });
            }
        </script>
    @endpush
</div>
