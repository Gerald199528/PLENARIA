<?php

use Livewire\Volt\Component;
use App\Models\CategoriaInstrumento;
use Livewire\Attributes\Title;

new #[Title('Editar Categoría')] class extends Component {
    public CategoriaInstrumento $categoriaInstrumento;
    public $mode = 'edit';
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
        $this->categoriaInstrumento = $categoria_instrumento;
        $this->nombre         = $categoria_instrumento->nombre;
        $this->tipo_categoria = $categoria_instrumento->tipo_categoria;
        $this->observacion    = $categoria_instrumento->observacion;
    }

    public function save()
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

            return $this->redirect(route('admin.categoria-instrumentos.index'), navigate: true);

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
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => 'Listado Categorias', 'route' => route('admin.categoria-instrumentos.index')],
            ['name' => 'Editar Categoria'],
        ]" />
    </x-slot>
    </div>
      @include('livewire.pages.admin.categoria_instrumentos.form.form', ['mode' => $mode])
</div>