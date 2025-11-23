            <?php
            use Livewire\Volt\Component;
            use App\Models\CategoriaCronica;
            use Illuminate\Support\Facades\Storage;

            new class extends Component {

                public function deleteCategoria($categoriaId)
                {
                    try {
                        $categoria = CategoriaCronica::findOrFail($categoriaId);          
                        $categoria->delete();    
                        $this->dispatch('showAlert', [
                            'icon' => 'success',
                            'title' => 'Eliminado',
                            'text' => 'La categoría se eliminó correctamente',
                            'timer' => 4000,
                            'timerProgressBar' => true,
                        ]);      
                        $this->dispatch('pg:eventRefresh-categoria-cronicas-table');

                    } catch (\Exception $e) {
                        $this->dispatch('showAlert', [
                            'icon' => 'error',
                            'title' => 'Error',
                            'text' => 'Ocurrió un error al intentar eliminar la categoría: ' . $e->getMessage(),
                            'timer' => 8000,
                            'timerProgressBar' => true,
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
                'name' => ' Categorias Cronicas',
            ],
        ]" />
    </x-slot>
            <!-- Botón Nuevo Perfil -->
@can('create-categoria_cronicas')
<x-slot name="action">
    <div class="mt-4">
        <a href="{{ route('admin.categoria_cronicas.create') }}" 
           wire:navigate
           class="inline-flex items-center gap-2 px-4 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm md:text-base bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400">
            <i class="fa-solid fa-tags animate-bounce"></i>
          Nueva Categoría
           
        </a>
    </div>
</x-slot>
@endcan
                    <!-- Tabla de Consejales -->
                    <x-container class="w-full px-4 mt-6">
                        <livewire:categoria-cronicas-table />
                    </x-container>

            @push('scripts')  
            <script>
                function confirmDelete(categoriaId) {
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
                            @this.call('deleteCategoria', categoriaId);
                        }
                    });
                }
            </script>
            @endpush
        </div>
