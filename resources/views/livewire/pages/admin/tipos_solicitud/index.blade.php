<?php

use Livewire\Volt\Component;
use App\Models\TipoSolicitud;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;
use Carbon\Carbon;

new class extends Component {

    public function deleteTipoSolicitud(TipoSolicitud $tipo_solicitud)
    {
        try {
            $tipo_solicitud->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Eliminado',
                'text' => 'El tipo de solicitud se eliminó correctamente.',
                'timer' => '2000',
                'timerProgressBar' => 'true',
            ]);

            $this->dispatch('pg:eventRefresh-tipo-solicitud-table');

        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al eliminar: ' . $e->getMessage(),
                'timer' => '2000',
                'timerProgressBar' => 'true',
            ]);
        }
    }

    public function generatePdf(TipoSolicitud $tipo_solicitud)
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
                ['label' => 'ID', 'value' => $tipo_solicitud->id],
                ['label' => 'Nombre', 'value' => $tipo_solicitud->nombre],
                ['label' => 'Descripción', 'value' => $tipo_solicitud->descripcion ?? 'N/A'],
                ['label' => 'Estado', 'value' => $tipo_solicitud->activo ? 'Activo' : 'Inactivo'],
                ['label' => 'Creado', 'value' => Carbon::parse($tipo_solicitud->created_at)->timezone('America/Caracas')->format('d/m/Y H:i')],
            ];

            $html = view('livewire.pages.admin.pdf.pdf-layout', [
                'fields' => $fields,
                'title' => $tipo_solicitud->nombre,
                'subtitle' => 'Tipo de Solicitud',
                'logo_icon' => $logoIcon,
                'primaryColor' => $primaryColor,
                'secondaryColor' => $secondaryColor,
                'tags' => ['Tipo de Solicitud'],
                'badgeTitle' => 'Clasificación',
                'sectionTitle' => 'Datos del Tipo de Solicitud'
            ])->render();

            $html = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>' . $html;

            $pdf = Pdf::loadHTML($html)
                ->setPaper('a4')
                ->setOption('encoding', 'UTF-8')
                ->setOption('default_font', 'DejaVu Sans');

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, "tipo_solicitud_" . $tipo_solicitud->id . "_" . now()->format('d-m-Y_H-i') . ".pdf", [
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
            ['name' => 'Tipos de Solicitud'],
        ]" />
    </x-slot>

    @can('create-tipos_solicitud')
    <x-slot name="action">
        <div class="mt-4">
            <a href="{{ route('admin.tipos_solicitud.create') }}"
               wire:navigate
               class="inline-flex items-center gap-2 px-4 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm md:text-base bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400">
                <i class="fa-solid fa-layer-group animate-bounce"></i>
                Crear Tipo de Solicitud
            </a>
        </div>
    </x-slot>
    @endcan

    <x-container class="w-full px-4 mt-6">
        <livewire:tipo-solicitud-table />
    </x-container>
</div>

@push('scripts')
    <script>
        function confirmDelete(tipo_solicitud_id) {
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
                    @this.call('deleteTipoSolicitud', tipo_solicitud_id);
                }
            });
        }
    </script>
@endpush
