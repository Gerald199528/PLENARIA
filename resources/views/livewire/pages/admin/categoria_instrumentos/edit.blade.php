<?php

use Livewire\Volt\Component;
use App\Models\CategoriaInstrumento;
use Livewire\Attributes\Title;

new #[Title('Editar Categoría')] class extends Component {
    public CategoriaInstrumento $categoriaInstrumento;

    public ?string $nombre = '';
    public ?string $tipo_categoria = '';
    public ?string $observacion = '';

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'tipo_categoria' => 'required|string|max:255',
        'observacion' => 'nullable|string|max:1000',
    ];

    public function mount(CategoriaInstrumento $categoria_instrumento): void
    {
        // Usamos el mismo nombre que en la ruta {categoria_instrumento}
        $this->categoriaInstrumento = $categoria_instrumento;

        $this->nombre         = $categoria_instrumento->nombre;
        $this->tipo_categoria = $categoria_instrumento->tipo_categoria;
        $this->observacion    = $categoria_instrumento->observacion;

        // Debug opcional
        \Log::info('Editando categoría', [
            'id' => $categoria_instrumento->id,
            'nombre' => $this->nombre,
            'tipo_categoria' => $this->tipo_categoria,
            'observacion' => $this->observacion,
        ]);
    }

    public function update(): void
    {
        $this->validate();

        try {
            $this->categoriaInstrumento->update([
                'nombre' => $this->nombre,
                'tipo_categoria' => $this->tipo_categoria,
                'observacion' => $this->observacion,
            ]);

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Éxito',
                'text' => 'Categoría actualizada correctamente.',
                'timer' => 2000,
                'timerProgressBar' => true,
            ]);

            // Redireccionar después de un breve delay
            $this->dispatch('redirectAfterSave');

        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al actualizar: ' . $e->getMessage(),
                'timer' => 3000,
                'timerProgressBar' => true,
            ]);
        }
    }

    public function cancel()
    {
        return $this->redirect(route('admin.categoria-instrumentos.index'), navigate: true);
    }
};
?>

<div>
    <!-- Breadcrumb -->
    <div class="mt-6">
        <nav class="flex items-center text-sm font-medium text-gray-600 dark:text-gray-300 space-x-2" aria-label="Breadcrumb">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 flex items-center gap-1">
                <x-icon name="home" class="w-4 h-4" />
                Dashboard
            </a>
            <span class="text-gray-400 dark:text-gray-500">/</span>
            <span class="text-gray-700 dark:text-gray-200 flex items-center gap-1">
                <x-icon name="tag" class="w-4 h-4" />
                Editar Categoría
            </span>
        </nav>
    </div>
@include('livewire.pages.admin.categoria_instrumentos.form.edit_categoria')

    @push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('fadeIn', () => ({
            show: false,
            init() {
                setTimeout(() => this.show = true, 200)
            }
        }))
    })

    Livewire.on('redirectAfterSave', () => {
        setTimeout(() => {
            window.location.href = "{{ route('admin.categoria-instrumentos.index') }}";
        }, 1000);
    });
</script>
@endpush
</div>
