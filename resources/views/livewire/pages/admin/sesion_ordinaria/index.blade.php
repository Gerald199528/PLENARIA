    <?php

    use Livewire\Volt\Component;
    use App\Models\SesionOrdinaria;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\View\View;

    new class extends Component {
        
        public function rendering(View $view)
        {
            $view->title('Sesiones Ordinarias');
        }

        public function deleteSesionOrdinaria(SesionOrdinaria $sesionOrdinaria)
        {
            try {
                // Eliminar archivo físico si existe
                if ($sesionOrdinaria->ruta) {
                    $path = $sesionOrdinaria->ruta; // ejemplo: "sesiones/miarchivo.pdf"
                    
                    if (Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                }
                
                // Eliminar el registro de la BD
                $sesionOrdinaria->delete();
                
                $this->dispatch('showAlert', [
                    'icon' => 'success',
                    'title' => 'Eliminada',
                    'text' => 'La sesión ordinaria y su archivo se eliminaron correctamente.',
                    'timer' => '2000',
                    'timerProgressBar' => 'true',
                ]);
                
                $this->dispatch('pg:eventRefresh-sesion-ordinaria-table');
                
            } catch (\Exception $e) {
                $this->dispatch('showAlert', [
                    'icon' => 'error',
                    'title' => 'Error',
                    'text' => 'Ocurrió un error al intentar eliminar la sesión ordinaria: ' . $e->getMessage(),
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
                'name' => 'Sesiones Ordinarias',
            ],
        ]" />
    </x-slot>    

<!-- Botón Nueva Sesión -->
@can('create-sesion_ordinaria')
    <x-slot name="action">
        <div class="mt-4">
            <a href="{{ route('admin.sesion_ordinaria.create') }}" wire:navigate class="inline-flex items-center gap-2 px-4 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm md:text-base bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400">
                <i class="fa-solid fa-arrow-right-to-file animate-bounce"></i>
                <span class="hidden sm:inline">Importar Sesión</span>
                <span class="sm:hidden">Importar</span>
            </a>
        </div>
    </x-slot>
@endcan
        <!-- Tabla de Sesiones Ordinarias -->
        <x-container class="w-full px-4 mt-6">
            <livewire:sesion-ordinaria-table />
        </x-container>
    </div>

    @push('scripts')
        <script>
            function confirmDelete(sesionOrdinaria_id) {
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
                        @this.call('deleteSesionOrdinaria', sesionOrdinaria_id);
                    }
                });
            }
        </script>
    @endpush