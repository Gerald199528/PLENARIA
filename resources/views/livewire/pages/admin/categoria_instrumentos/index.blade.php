<?php

use Livewire\Volt\Component;
use App\Models\CategoriaInstrumento;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;

new class extends Component {

    public function deleteCategoria(CategoriaInstrumento $categoria)
    {
        try {
            $categoria->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Eliminado',
                'text' => 'La categoría se eliminó correctamente',
            ]);

            $this->dispatch('pg:eventRefresh-categoria-intrumento-table');

        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar la categoría',
            ]);
        }
    }

    public function downloadCategoryPdf($categoryId)
    {
        try {
            $categoria = CategoriaInstrumento::findOrFail($categoryId);

            $logoPath = Setting::get('logo_horizontal');
            $logoIcon = null;
            if ($logoPath && Storage::disk('public')->exists($logoPath)) {
                $imageContent = Storage::disk('public')->get($logoPath);
                $mimeType = Storage::disk('public')->mimeType($logoPath);
                $logoIcon = 'data:' . $mimeType . ';base64,' . base64_encode($imageContent);
            }

            $primaryColor = Setting::get('primary_color', '#0f2440');
            $secondaryColor = Setting::get('secondary_color', '#00d4ff');

            $fields = [
                ['label' => 'Nombre', 'value' => $categoria->nombre],
                ['label' => 'Tipo', 'value' => $categoria->tipo_categoria ?? 'N/A'],
                ['label' => 'Observación', 'value' => $categoria->observacion ?? 'N/A'],
                ['label' => 'Creado', 'value' => $categoria->created_at->format('d/m/Y H:i')],
            ];

            $html = view('livewire.pages.admin.pdf.pdf-layout', [
                'fields' => $fields,
                'title' => $categoria->nombre,
                'subtitle' => 'Categoría de Instrumento',
                'logo_icon' => $logoIcon,
                'primaryColor' => $primaryColor,
                'secondaryColor' => $secondaryColor,
                'tags' => ['Instrumento', 'Categoría'],
                'badgeTitle' => 'Clasificación',
                'sectionTitle' => 'Información de la Categoría'
            ])->render();

            $html = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>' . $html;

            $pdf = Pdf::loadHTML($html)
                ->setPaper('a4')
                ->setOption('encoding', 'UTF-8')
                ->setOption('default_font', 'DejaVu Sans');

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->stream();
            }, "categoria_{$categoria->id}.pdf", [
                'Content-Type' => 'application/pdf',
            ]);

        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Error al generar el PDF: ' . $e->getMessage(),
            ]);
        }
    }

};
?>

<div class="mt-6">

    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            [
                'name' => 'Dashboard',
                'route' => route('admin.dashboard'),
            ],
            [
                'name' => 'Listado Categorias',
            ],
        ]" />
    </x-slot>

    @can('create-categoria-instrumento')
    <x-slot name="action">
        <div class="mt-3 sm:mt-4">
            <a href="{{ route('admin.categoria-instrumentos.create') }}" 
               wire:navigate
               class="inline-flex items-center gap-1.5 sm:gap-2 px-2.5 py-1.5 sm:px-4 sm:py-2 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-md sm:rounded-lg shadow-md transform transition-all duration-300 hover:scale-105 hover:shadow-lg hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400 text-xs sm:text-sm"
            >
                <i class="fa-solid fa-arrow-up-from-bracket animate-bounce text-sm sm:text-base"></i>
                <span class="truncate">Nueva Categoría</span>
            </a>
        </div>
    </x-slot>
    @endcan

    <x-container class="w-full px-4 mt-6">
        <livewire:categoria-intrumento-table />
    </x-container>

    @push('scripts')  
        <script>    
            function confirmDelete(categoria_id) {
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
                        @this.call('deleteCategoria', categoria_id);
                    }
                });
            }
        </script>
    @endpush

</div>