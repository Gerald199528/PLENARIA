<?php

use Livewire\Volt\Component;
use App\Models\Empresa;
use Illuminate\Support\Facades\Storage;

new class extends Component {

    public function deleteEmpresa(Empresa $empresa)
    {
        try {
            // Eliminar archivo físico si existe
            if ($empresa->organigrama_ruta) {
                $path = $empresa->organigrama_ruta;

                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            // Eliminar el registro de la BD
            $empresa->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Eliminado',
                'text' => 'La empresa y su organigrama se eliminaron correctamente',
            ]);

            // Refrescar la tabla
            $this->dispatch('pg:eventRefresh-empresa-table');

        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar la empresa',
            ]);
        }
    }

};
?>

<div>
      <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => 'Datos de la empresa'],
        ]" />
    </x-slot>
@can('create-empresa')
<x-slot name="action">
    <div class="mt-2 sm:mt-3 md:mt-4">
        <a
           href="{{ route('admin.empresa.create') }}" 
           wire:navigate
           class="inline-flex items-center justify-center gap-2 px-4 sm:px-5 md:px-6 py-2 sm:py-2.5 md:py-3 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400 text-sm md:text-base w-full sm:w-auto"
        >
            <i class="fa-solid fa-building animate-bounce"></i>
            <span>Nueva Empresa</span>
        </a>
    </div>
</x-slot>
@endcan
    <x-container class="w-full px-4 mt-6">
        <livewire:empresa-table />
    </x-container>

@push('scripts')
<script>
    function confirmDeleteEmpresa(empresa_id) {
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
                @this.call('deleteEmpresa', empresa_id);
            }
        });
    }
</script>
@endpush


</div>
