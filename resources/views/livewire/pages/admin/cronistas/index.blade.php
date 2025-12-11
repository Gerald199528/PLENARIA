<?php

use Livewire\Volt\Component;
use App\Models\Cronista;
use App\Models\CategoriaCronica;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;

new class extends Component {

public function deleteCronista($cronistaId)
    {
        try {
            $cronista= \App\Models\Cronista::findOrFail($cronistaId);       
            if ($cronista->imagen_url) {
                $path = $cronista->imagen_url;    
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
                }
            }
            $cronista->delete();       
            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Eliminado',
                'text' => 'El cronista y su imagen se eliminaron correctamente',
            ]);
            $this->dispatch('pg:eventRefresh-cronista-table');
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar el cronista: ' . $e->getMessage(),
            ]);
        }
    }
    public function downloadCronistaPdf($cronistaId)
    {
        try {
            $cronista = Cronista::findOrFail($cronistaId);

            $logoPath = Setting::get('logo_horizontal');
            $logoIcon = null;
            if ($logoPath && Storage::disk('public')->exists($logoPath)) {
                $imageContent = Storage::disk('public')->get($logoPath);
                $mimeType = Storage::disk('public')->mimeType($logoPath);
                $logoIcon = 'data:' . $mimeType . ';base64,' . base64_encode($imageContent);
            }       
            $image = null;
            if ($cronista->imagen_url) {
                $path = storage_path('app/public/' . $cronista->imagen_url);
                if (file_exists($path)) {
                    $imageContent = file_get_contents($path);
                    $mimeType = mime_content_type($path);
                    $image = 'data:' . $mimeType . ';base64,' . base64_encode($imageContent);
                }
            }
            $primaryColor = Setting::get('primary_color', '#0f2440');
            $secondaryColor = Setting::get('secondary_color', '#00d4ff');
            $fields = [
                ['label' => 'Cédula', 'value' => $cronista->cedula],
                ['label' => 'Nombre', 'value' => $cronista->nombre_completo],
                ['label' => 'Apellido', 'value' => $cronista->apellido_completo],
                ['label' => 'Email', 'value' => $cronista->email],
                ['label' => 'Teléfono', 'value' => $cronista->telefono ?? 'N/A'],
                ['label' => 'Cargo', 'value' => $cronista->cargo ?? 'N/A'],
                ['label' => 'Perfil', 'value' => $cronista->perfil ?? 'N/A'],
                ['label' => 'Fecha Ingreso', 'value' => $cronista->fecha_ingreso ? \Carbon\Carbon::parse($cronista->fecha_ingreso)->format('d/m/Y') : 'N/A'],
                ['label' => 'Creado', 'value' => $cronista->created_at->format('d/m/Y H:i')],
            ];
            $tags = ['Cronista'];
            $html = view('livewire.pages.admin.pdf.pdf-layout', [
                'fields' => $fields,
                'title' => $cronista->nombre_completo . ' ' . $cronista->apellido_completo,
                'subtitle' => 'Información del Cronista',
                'logo_icon' => $logoIcon,
                'image' => $image,
                'primaryColor' => $primaryColor,
                'secondaryColor' => $secondaryColor,
                'tags' => $tags,
                'badgeTitle' => 'Clasificación',
                'sectionTitle' => 'Datos del Cronista'
            ])->render();
            $html = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>' . $html;
            $pdf = Pdf::loadHTML($html)
                ->setPaper('a4')
                ->setOption('encoding', 'UTF-8')
                ->setOption('default_font', 'DejaVu Sans');
            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->stream();
            }, "cronista_{$cronista->id}.pdf", [
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
                'name' => 'Listado Cronistas',
            ],
        ]" />
    </x-slot>

        <!-- Botón Nuevo Perfil -->
        @can('create-cronista')
        <x-slot name="action">
            <div class="mt-4">
                <a href="{{ route('admin.cronistas.create') }}"
                wire:navigate
                class="inline-flex items-center gap-2 px-4 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm md:text-base bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400">
                    <i class="fa-solid fa-user-plus animate-bounce"></i>              
                    <span class="hidden sm:inline">Nuevo Cronista</span>
                    <span class="sm:hidden">Nuevo</span>
                </a>
            </div>
        </x-slot>
        @endcan

    <!-- Tabla de Ccronista -->
    <x-container class="w-full px-4 mt-6">
        <livewire:cronista-table />
    </x-container>

    <!-- Scripts eliminar -->
    @push('scripts')  
        <script>    
            function confirmDeleteCronista(cronista_id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'No podrás revertir esto! Se eliminará el cronista y su imagen.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {                 
                        @this.call('deleteCronista', cronista_id);
                    }
                });
            }
        </script>
    @endpush
</div>
