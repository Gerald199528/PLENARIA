    <?php
    use Livewire\Volt\Component;
    new class extends Component {

    public function deleteCronica($cronicaId)
    {
        try {
            $cronica = \App\Models\Cronica::findOrFail($cronicaId);

            // Eliminar archivo físico si existe
            if ($cronica->archivo_pdf && Storage::disk('public')->exists($cronica->archivo_pdf)) {
                Storage::disk('public')->delete($cronica->archivo_pdf);
            }

            // Eliminar registro en la DB
            $cronica->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Eliminado',
                'text' => 'La crónica y su archivo se eliminaron correctamente',
            ]);

            // Refrescar la tabla (si usas PowerGrid o Livewire Table)
            $this->dispatch('pg:eventRefresh-cronica-table');

        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar la crónica',
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
                'name' => 'Listado Cronicas',
            ],
        ]" />
    </x-slot>
  <!-- Botón Nuevo Perfil -->
@can('create-concejal')
<x-slot name="action">
    <div class="mt-4">
        <a href="{{ route('admin.cronicas.create') }}" 
           wire:navigate
           class="inline-flex items-center gap-2 px-4 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm md:text-base bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400">
            <i class="fa-solid fa-book-open animate-bounce"></i>
           Crear Crónica
           
        </a>
    </div>
</x-slot>
@endcan

        <!-- Tabla de Consejales -->
        <x-container class="w-full px-4 mt-6">
            <livewire:cronica-table />
        </x-container>

        @push('scripts')  
    <script>
        function confirmDelete(cronicaId) {
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
                    @this.call('deleteCronica', cronicaId);
                }
            });
        }
    </script>
    @endpush    
    </div>
