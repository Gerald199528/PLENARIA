<?php

use Livewire\Volt\Component;
use App\Models\Cronica;
use App\Models\Cronista;
use Carbon\Carbon;

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

        // 🚨 Emitir evento de alerta con SweetAlert
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

    // 🚨 Si no hay crónicas, lanzar alerta
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

    // Emitir evento cuando haya datos
    $this->dispatch('chartDataLoaded');
}

};
?>
<div >
            <!-- Breadcrumbs -->
        <x-slot name="breadcrumbs">
            <nav class="flex items-center text-sm font-medium text-gray-600 dark:text-gray-300 space-x-2" aria-label="Breadcrumb">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 flex items-center gap-1">
                    <x-icon name="home" class="w-4 h-4" />
                    Dashboard
                </a>
                <span class="text-gray-400 dark:text-gray-500">/</span>
                <span class="text-gray-700 dark:text-gray-200 flex items-center gap-1">
                <x-icon name="chart-bar" class="w-4 h-4" />
                Grafico Estadistico
                </span>
            </nav>
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
                <button onclick="exportToPDF()" class="flex-1 px-6 py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white font-semibold rounded-lg hover:from-red-600 hover:to-red-700 shadow-md">PDF</button>
            </div>
        </div>   
    </div>



@include('livewire.pages.admin.cronicas.form.chatjs')


</div>