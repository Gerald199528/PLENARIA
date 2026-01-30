<?php

use Livewire\Volt\Component;
use App\Models\Miembro;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;

new class extends Component {

    public function deleteMiembro($miembroId)
    {
        try {
            $miembro = Miembro::findOrFail($miembroId);
            $miembro->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Eliminado',
                'text' => 'El miembro se eliminó correctamente.',
            ]);

            $this->dispatch('pg:eventRefresh-miembro-table');

        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar el miembro: ' . $e->getMessage(),
            ]);
        }
    }

public function downloadMiembroPdf($miembroId)
{
    try {
        $miembro = Miembro::findOrFail($miembroId);
        
        // Obtener datos de la tabla pivote comision_concejal
        $pivote = DB::table('comision_concejal')
            ->where('miembro_id', $miembroId)
            ->first();

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
            ['label' => 'Concejal', 'value' => $pivote->nombre_concejal ?? 'N/A'],
            ['label' => 'Comisión', 'value' => $pivote->nombre_comision ?? 'N/A'],
            ['label' => 'Fecha Inicio', 'value' => $miembro->fecha_inicio ? \Carbon\Carbon::parse($miembro->fecha_inicio)->format('d/m/Y') : 'N/A'],
            ['label' => 'Fecha Fin', 'value' => $miembro->fecha_fin ? \Carbon\Carbon::parse($miembro->fecha_fin)->format('d/m/Y') : 'N/A'],
            ['label' => 'Estado', 'value' => $miembro->estado ?? 'N/A'],
            ['label' => 'Creado', 'value' => $miembro->created_at->format('d/m/Y H:i')],
        ];

        $tags = [];
        if ($pivote->nombre_comision) {
            $tags[] = $pivote->nombre_comision;
        }
        if ($miembro->estado) {
            $tags[] = ucfirst($miembro->estado);
        }

        $html = view('livewire.pages.admin.pdf.pdf-layout', [
            'fields' => $fields,
            'title' => 'Miembro - ' . ($pivote->nombre_concejal ?? 'N/A'),
            'subtitle' => 'Información del Miembro',
            'logo_icon' => $logoIcon,
            'primaryColor' => $primaryColor,
            'secondaryColor' => $secondaryColor,
            'tags' => $tags,
            'sectionTitle' => 'Datos del Miembro',
            'badgeTitle' => 'Detalles'
        ])->render();

        $html = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>' . $html;

        $pdf = Pdf::loadHTML($html)
            ->setPaper('a4')
            ->setOption('encoding', 'UTF-8')
            ->setOption('default_font', 'DejaVu Sans');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, "miembro_{$miembro->id}.pdf", [
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
                'name' => 'Miembros',
            ],
        ]" />
    </x-slot>

    @can('create-concejal')
    <x-slot name="action">
        <div class="mt-2 sm:mt-3 md:mt-4">
            <a
               href="{{ route('admin.miembros.create') }}" 
               wire:navigate
               class="inline-flex items-center gap-1.5 sm:gap-2 md:gap-3 px-3 sm:px-4 md:px-6 py-1.5 sm:py-2 md:py-3 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-lg sm:rounded-xl shadow-md sm:shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400 text-xs sm:text-sm md:text-base"
            >
                <i class="fa-solid fa-users animate-bounce text-xs sm:text-sm md:text-base flex-shrink-0"></i>
                Añadir Miembro
            </a>
        </div>
    </x-slot>
    @endcan

    <x-container class="w-full px-4 mt-6">
        <livewire:miembro-table />
    </x-container>

    @push('scripts')
    <script>
        function confirmDeleteMiembro(miembro_id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'No podrás revertir esto. Se eliminará el miembro perteneciente a esta comisión.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('deleteMiembro', miembro_id);
                }
            });
        }
    </script>
    @endpush
</div>