<?php

use Livewire\Volt\Component;
use App\Models\Empresa;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Setting;
use Carbon\Carbon;

new class extends Component {

    public function deleteEmpresa(Empresa $empresa)
    {
        try {
            if ($empresa->organigrama_ruta) {
                $path = $empresa->organigrama_ruta;
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            $empresa->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Eliminado',
                'text' => 'La empresa y su organigrama se eliminaron correctamente',
            ]);

            $this->dispatch('pg:eventRefresh-empresa-table');

        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar la empresa',
            ]);
        }
    }

    public function generatePdf(Empresa $empresa)
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
                ['label' => 'ID', 'value' => $empresa->id],
                ['label' => 'Nombre', 'value' => $empresa->name],
                ['label' => 'Razón Social', 'value' => $empresa->razon_social],
                ['label' => 'RIF', 'value' => $empresa->rif],
                ['label' => 'Dirección Fiscal', 'value' => $empresa->direccion_fiscal],
                ['label' => 'Oficina Principal', 'value' => $empresa->oficina_principal ?? 'N/A'],
                ['label' => 'Horario de Atención', 'value' => $empresa->horario_atencion ?? 'N/A'],
                ['label' => 'Teléfono Principal', 'value' => $empresa->telefono_principal],
                ['label' => 'Teléfono Secundario', 'value' => $empresa->telefono_secundario ?? 'N/A'],
                ['label' => 'Email Principal', 'value' => $empresa->email_principal],
                ['label' => 'Email Secundario', 'value' => $empresa->email_secundario ?? 'N/A'],
                ['label' => 'Domain', 'value' => $empresa->domain ?? 'N/A'],
                ['label' => 'Actividad', 'value' => $empresa->actividad],
                ['label' => 'Descripción', 'value' => $empresa->description ?? 'N/A'],
                ['label' => 'Misión', 'value' => $empresa->mision ?? 'N/A'],
                ['label' => 'Visión', 'value' => $empresa->vision ?? 'N/A'],
                ['label' => 'Coordenadas', 'value' => $empresa->latitud && $empresa->longitud ? "Lat: {$empresa->latitud}, Lng: {$empresa->longitud}" : 'N/A'],
                ['label' => 'Creado', 'value' => Carbon::parse($empresa->created_at)->timezone('America/Caracas')->format('d/m/Y H:i')],
            ];

            $html = view('livewire.pages.admin.pdf.pdf-layout', [
                'fields' => $fields,
                'title' => $empresa->name,
                'subtitle' => 'Datos de la Empresa',
                'logo_icon' => $logoIcon,
                'primaryColor' => $primaryColor,
                'secondaryColor' => $secondaryColor,
                'tags' => ['Empresa', $empresa->actividad, 'RIF: ' . $empresa->rif],
                'badgeTitle' => 'Clasificación',
                'sectionTitle' => 'Datos de la Empresa'
            ])->render();

            $html = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>' . $html;

            $pdf = Pdf::loadHTML($html)
                ->setPaper('a4')
                ->setOption('encoding', 'UTF-8')
                ->setOption('default_font', 'DejaVu Sans');

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, "empresa_" . $empresa->id . "_" . now()->format('d-m-Y_H-i') . ".pdf", [
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
            ['name' => 'Datos de la empresa'],
        ]" />
    </x-slot>

@can('create-empresa')
<x-slot name="action">
    <div class="mt-2 sm:mt-3 md:mt-4">
        <a
           href="{{ route('admin.empresa.create') }}" 
           wire:navigate
           class="inline-flex items-center justify-center gap-2 px-4 sm:px-5 md:px-6 py-2 sm:py-2.5 md:py-3 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400 text-sm md:text-base w-full sm:w-auto"
        >
            <i class="fa-solid fa-building animate-bounce"></i>
            <span>Nueva Empresa</span>
        </a>
    </div>
</x-slot>
@endcan

    <x-container class="w-full px-4 mt-6">
        <livewire:empresa-table />
    </x-container>

@push('scripts')
<script>
    function confirmDeleteEmpresa(empresa_id) {
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
                @this.call('deleteEmpresa', empresa_id);
            }
        });
    }
</script>
@endpush
</div>