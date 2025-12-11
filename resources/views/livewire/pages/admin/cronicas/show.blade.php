<?php

use Livewire\Volt\Component;
use App\Models\Cronica;
use App\Models\Cronista;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;
use Carbon\Carbon;
use Livewire\Attributes\On;

new class extends Component {
    public $cronista;
    public $filterDate;
    public $filterType = 'año';
    public $chartLabels = [];
    public $chartValues = [];
    public $chartReady = false;
    public $debugInfo = '';

    public function mount()
    {
        $this->cronista = Cronista::first() ?? new Cronista();
        $this->filterDate = '';
    }

    public function loadChartData()
    {
        $this->debugInfo = '';

        if (!$this->cronista || !$this->cronista->id) {
            $this->debugInfo = 'No hay cronista';
            $this->chartLabels = [];
            $this->chartValues = [];
            $this->chartReady = false;

            $this->dispatch('showAlert', [
                'title' => 'Sin cronista',
                'text' => 'No se encontró ningún cronista registrado.',
                'icon' => 'warning'
            ]);
            return;
        }

        if (!$this->filterDate) {
            $this->debugInfo = 'No hay fecha seleccionada';
            $this->chartLabels = [];
            $this->chartValues = [];
            $this->chartReady = false;

            $this->dispatch('showAlert', [
                'title' => 'Fecha requerida',
                'text' => 'Por favor selecciona una fecha antes de generar el gráfico.',
                'icon' => 'info'
            ]);
            return;
        }

        $date = Carbon::parse($this->filterDate);
        $cronicas = collect();

        switch ($this->filterType) {
            case 'semana':
                $startDate = $date->copy()->startOfWeek(Carbon::MONDAY);
                $endDate = $date->copy()->endOfWeek(Carbon::SUNDAY);
                
                $this->chartLabels = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
                $this->chartValues = array_fill(0, 7, 0);

                $cronicas = Cronica::where('cronista_id', $this->cronista->id)
                    ->whereBetween('fecha_publicacion', [
                        $startDate->format('Y-m-d 00:00:00'),
                        $endDate->format('Y-m-d 23:59:59')
                    ])
                    ->get();

                foreach ($cronicas as $cronica) {
                    $dayIndex = Carbon::parse($cronica->fecha_publicacion)->dayOfWeekIso - 1;
                    if ($dayIndex >= 0 && $dayIndex < 7) {
                        $this->chartValues[$dayIndex]++;
                    }
                }
                break;

            case 'mes':
                $daysInMonth = $date->daysInMonth;
                $this->chartLabels = range(1, $daysInMonth);
                $this->chartValues = array_fill(0, $daysInMonth, 0);

                $cronicas = Cronica::where('cronista_id', $this->cronista->id)
                    ->whereYear('fecha_publicacion', $date->year)
                    ->whereMonth('fecha_publicacion', $date->month)
                    ->get();

                foreach ($cronicas as $cronica) {
                    $dayIndex = Carbon::parse($cronica->fecha_publicacion)->day - 1;
                    if ($dayIndex >= 0 && $dayIndex < $daysInMonth) {
                        $this->chartValues[$dayIndex]++;
                    }
                }
                break;

            default: // Año
                $this->chartLabels = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                $this->chartValues = array_fill(0, 12, 0);

                $cronicas = Cronica::where('cronista_id', $this->cronista->id)
                    ->whereYear('fecha_publicacion', $date->year)
                    ->get();

                foreach ($cronicas as $cronica) {
                    $monthIndex = Carbon::parse($cronica->fecha_publicacion)->month - 1;
                    if ($monthIndex >= 0 && $monthIndex < 12) {
                        $this->chartValues[$monthIndex]++;
                    }
                }
                break;
        }

        if ($cronicas->isEmpty()) {
            $this->dispatch('showAlert', [
                'title' => 'Sin resultados',
                'text' => 'No se encontraron crónicas registradas para este período.',
                'icon' => 'warning'
            ]);

            $this->chartReady = false;
            return;
        }

        $this->chartReady = true;

        \Log::info('Chart Debug: ' . json_encode([
            'labels' => $this->chartLabels,
            'values' => $this->chartValues,
            'info' => $this->debugInfo,
            'ready' => $this->chartReady
        ]));

        $this->dispatch('chartDataLoaded');
    }

    #[On('generatePdfWithChart')]
    public function generatePdfWithChart($chartImage)
    {
        try {
            \Log::info('Iniciando generación de PDF con gráfica');
            
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
                ['label' => 'Cronista ID', 'value' => $this->cronista?->id ?? 'N/A'],
                ['label' => 'Cronista', 'value' => $this->cronista ? $this->cronista->nombre_completo . ' ' . $this->cronista->apellido_completo : 'N/A'],
                ['label' => 'Fecha Filtro', 'value' => $this->filterDate ?: 'No seleccionada'],
                ['label' => 'Tipo Filtro', 'value' => ucfirst($this->filterType)],
                ['label' => 'Total Registros', 'value' => array_sum($this->chartValues)],
                ['label' => 'Generado', 'value' => now()->format('d/m/Y H:i')],
            ];

            \Log::info('Largo de chartImage: ' . strlen($chartImage));
            \Log::info('Primeros 100 caracteres: ' . substr($chartImage, 0, 100));

            $html = view('livewire.pages.admin.pdf.pdf-layout', [
                'fields' => $fields,
                'title' => 'Gráfica de Crónicas',
                'subtitle' => 'Reporte Estadístico',
                'logo_icon' => $logoIcon,
                'primaryColor' => $primaryColor,
                'secondaryColor' => $secondaryColor,
                'tags' => ['Gráfica', 'Crónicas', 'Estadísticas', ucfirst($this->filterType)],
                'badgeTitle' => 'Información del Reporte',
                'sectionTitle' => 'Datos de la Gráfica',
                'chartImage' => $chartImage
            ])->render();

            $html = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>' . $html;

            $pdf = Pdf::loadHTML($html)
                ->setPaper('a4')
                ->setOption('encoding', 'UTF-8')
                ->setOption('default_font', 'DejaVu Sans')
                ->setOption('isHtml5ParserEnabled', true)
                ->setOption('enable_remote', true);

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, "grafica_cronicas_" . now()->format('d-m-Y_H-i') . ".pdf", [
                'Content-Type' => 'application/pdf',
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en generatePdfWithChart: ' . $e->getMessage());
            \Log::error('Stack: ' . $e->getTraceAsString());
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
                'name' => 'Gráfico Estadístico',
            ],
        ]" />
    </x-slot>

    <div class="w-full max-w-6xl mx-auto">
        <!-- Filtros -->
        <div class="mb-8 p-8 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                <i class="fa-solid fa-filter text-blue-500"></i> Filtros
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Selecciona Fecha:</label>
                    <input type="date" wire:model="filterDate" class="w-full px-4 py-2.5 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:border-blue-500 dark:focus:border-blue-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-base">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Filtrar por:</label>
                    <select wire:model="filterType" class="w-full px-4 py-2.5 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:border-blue-500 dark:focus:border-blue-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-base cursor-pointer">
                        <option value="semana">Semana</option>
                        <option value="mes">Mes</option>
                        <option value="año">Año</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button wire:click="loadChartData" class="flex-1 px-6 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-blue-700 shadow-md">Generar</button>
                    <button onclick="downloadChartPDFWithInfo()" class="flex-1 px-6 py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white font-semibold rounded-lg hover:from-red-600 hover:to-red-700 shadow-md flex items-center justify-center gap-2">
                        <i class="fas fa-download"></i> PDF
                    </button>
                    
                </div>
            </div>   
        </div>
        @include('livewire.pages.admin.cronicas.form.chatjs')
    </div>
</div>