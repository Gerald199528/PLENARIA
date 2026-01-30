<?php

use Livewire\Volt\Component;
use App\Models\CategoriaCronica;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;

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

    public function downloadCategoriaPdf($categoriaId)
    {
        try {
            $categoria = CategoriaCronica::findOrFail($categoriaId);

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
                ['label' => 'Descripción', 'value' => $categoria->descripcion ?? 'N/A'],
                ['label' => 'Crónicas', 'value' => $categoria->cronicas()->count() ?? '0'],
                ['label' => 'Creado', 'value' => $categoria->created_at->format('d/m/Y H:i')],
            ];

            $tags = ['Categoría de Crónicas'];

            $html = view('livewire.pages.admin.pdf.pdf-layout', [
                'fields' => $fields,
                'title' => $categoria->nombre,
                'subtitle' => 'Categoría de Crónicas',
                'logo_icon' => $logoIcon,
                'primaryColor' => $primaryColor,
                'secondaryColor' => $secondaryColor,
                'tags' => $tags,
                'badgeTitle' => 'Clasificación',
                'sectionTitle' => 'Datos de la Categoría'
            ])->render();

            $html = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>' . $html;

            $pdf = Pdf::loadHTML($html)
                ->setPaper('a4')
                ->setOption('encoding', 'UTF-8')
                ->setOption('default_font', 'DejaVu Sans');

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->stream();
            }, "categoria_cronica_{$categoria->id}.pdf", [
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

<div>
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            [
                'name' => 'Dashboard',
                'route' => route('admin.dashboard'),
            ],
            [
                'name' => 'Categorías Crónicas',
            ],
        ]" />
    </x-slot>

    <!-- Botón Nueva Categoría -->
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

    <!-- Tabla de Categorías Crónicas -->
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