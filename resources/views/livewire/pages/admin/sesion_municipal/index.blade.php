<?php

use Livewire\Volt\Component;
use App\Models\SesionMunicipal;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;
use Carbon\Carbon;

new class extends Component {
    
    public function deleteSesionMunicipal(SesionMunicipal $sesion_municipal)
    {
        try {
            $sesion_municipal->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Eliminada',
                'text' => 'La Agenda se eliminó correctamente.',
                'timer' => '2000',
                'timerProgressBar' => 'true',
            ]);

            $this->dispatch('pg:eventRefresh-sesion-municipal-table');

        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al eliminar la sesión municipal: ' . $e->getMessage(),
                'timer' => '2000',
                'timerProgressBar' => 'true',
            ]);
        }
    }

    public function generatePdf(SesionMunicipal $sesion)
    {
        try {
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
                ['label' => 'ID Sesión', 'value' => $sesion->id],
                ['label' => 'Título', 'value' => $sesion->titulo],
                ['label' => 'Categoría', 'value' => $sesion->categoria?->nombre ?? 'Sin categoría'],
                ['label' => 'Descripción', 'value' => $sesion->descripcion],
                ['label' => 'Fecha y Hora', 'value' => Carbon::parse($sesion->fecha_hora)->timezone('America/Caracas')->format('d/m/Y h:i A')],
                ['label' => 'Estado', 'value' => ucfirst($sesion->estado), 'highlight' => true],
                ['label' => 'Generado', 'value' => now()->format('d/m/Y H:i')],
            ];

            $html = view('livewire.pages.admin.pdf.pdf-layout', [
                'fields' => $fields,
                'title' => 'Sesión Municipal',
                'subtitle' => $sesion->titulo,
                'logo_icon' => $logoIcon,
                'primaryColor' => $primaryColor,
                'secondaryColor' => $secondaryColor,
                'tags' => ['Sesión Municipal', ucfirst($sesion->estado), $sesion->categoria?->nombre],
                'badgeTitle' => 'Información de Sesión',
                'sectionTitle' => 'Detalles de la Sesión',
            ])->render();

            $html = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>' . $html;

            $pdf = Pdf::loadHTML($html)
                ->setPaper('a4')
                ->setOption('encoding', 'UTF-8')
                ->setOption('default_font', 'DejaVu Sans');

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, "sesion_municipal_" . $sesion->id . "_" . now()->format('d-m-Y_H-i') . ".pdf", [
                'Content-Type' => 'application/pdf',
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en generatePdf: ' . $e->getMessage());
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Error al generar PDF: ' . $e->getMessage(),
            ]);
        }
    }
};
?>

<div>
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => 'Agenda Municipal'],
        ]" />
    </x-slot>
@can('create-sesion_municipal')
<x-slot name="action">
    <div class="mt-4">
        <a href="{{ route('admin.sesion_municipal.create') }}" 
           wire:navigate
           class="inline-flex items-center gap-2 px-4 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm md:text-base bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400">
            <i class="fa-solid fa-calendar-day animate-bounce"></i>
          Agendar Sesión
           
        </a>
    </div>
</x-slot>
@endcan

    <x-container class="w-full px-4 mt-6">
        <livewire:sesion-municipal-table />
    </x-container>
</div>

@push('scripts')
    <script>
        function confirmDelete(sesion_municipal_id) {
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
                    @this.call('deleteSesionMunicipal', sesion_municipal_id);
                }
            });
        }
    </script>
@endpush