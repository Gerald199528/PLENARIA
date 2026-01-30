<?php

use Livewire\Volt\Component;
use App\Models\Noticia;

new class extends Component {
    
    public function deleteNoticia($noticiaId)
    {
        try {
            $noticia = Noticia::findOrFail($noticiaId);
            
            // Eliminar imagen física si existe
            if ($noticia->imagen) {
                $path = $noticia->imagen;
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
                }
            }

            // Eliminar PDF si existe
            if ($noticia->archivo_pdf) {
                $path = $noticia->archivo_pdf;
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
                }
            }

            // Eliminar video si existe (solo si es archivo, no URL)
            if ($noticia->video_archivo && $noticia->tipo_video === 'archivo') {
                $path = $noticia->video_archivo;
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
                }
            }

            // Eliminar el registro de la BD
            $noticia->delete();

            // Alerta de éxito
            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Eliminado',
                'text' => 'La noticia y todos sus archivos se eliminaron correctamente',
                'timer' => 3000,
                'timerProgressBar' => true,
            ]);

            // Refrescar la tabla automáticamente
            $this->dispatch('pg:eventRefresh-noticia-table');

        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar la noticia: ' . $e->getMessage(),
                'timer' => 5000,
                'timerProgressBar' => true,
            ]);
        }
    }
};

?>
<div>
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => 'Lista Noticia'],
        ]" />
    </x-slot>
<!-- Botón Nueva Noticia -->
@can('create-noticias')
<x-slot name="action">
    <div class="mt-4">
        <a href="{{ route('admin.noticias.create') }}"
           wire:navigate
           class="inline-flex items-center gap-2 px-4 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm md:text-base bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400">
            <i class="fa-solid fa-newspaper animate-bounce"></i>              
           Nueva Noticia
           
        </a>
    </div>
</x-slot>
@endcan

    <x-container class="w-full px-4 mt-6">
        <livewire:noticia-table />
    </x-container>


    @push('scripts')  
        <script>    
            function confirmDeleteNoticia(noticia_id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'No podrás revertir esto! Se eliminará la noticia, imagen, PDF y video.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {                 
                        @this.call('deleteNoticia', noticia_id);
                    }
                });
            }
        </script>
    @endpush
</div>