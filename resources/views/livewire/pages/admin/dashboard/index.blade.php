<?php

use Livewire\Volt\Component;
use App\Models\Miembro;
use App\Models\Comision;
use App\Models\SesionMunicipal;
use App\Models\Noticia;
use Livewire\Attributes\Computed;

new class extends Component {
    public $lineData = [];
    public $barData = [];
    public $doughnutData = [];
    public $suggestion = '';
    public $lat = null;
    public $lng = null;
    public $currentTime = null;
    public $lastConnection = null;
    public $totalMiembros = 0;
    public $totalComisiones = 0;

    #[Computed]
    public function sesionesActivas()
    {
        return SesionMunicipal::where('estado', 'proxima')->count();
    }

    #[Computed]
    public function totalNoticias()
    {
        return Noticia::count();
    }

    public function mount()
    {
        $this->lineData = [
            ['time' => '2025-01-01', 'value' => 120],
            ['time' => '2025-02-01', 'value' => 180],
            ['time' => '2025-03-01', 'value' => 150],
            ['time' => '2025-04-01', 'value' => 220],
            ['time' => '2025-05-01', 'value' => 200],
        ];

        $this->barData = [
            'labels' => ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo'],
            'values' => [50, 75, 150, 100, 200],
        ];

        $this->doughnutData = [
            'labels' => ['Comisión de Finanzas', 'Comisión de Obras', 'Comisión de Salud', 'Comisión de Educación', 'Comisión de Deportes'],
            'values' => [12, 19, 8, 15, 10],
        ];

        $this->currentTime = now()->format('Y-m-d H:i:s');
        $this->lastConnection = now()->subMinutes(5)->format('Y-m-d H:i:s');

        $this->totalMiembros = Miembro::count();
        $this->totalComisiones = Comision::count();
    }

    public function refreshData()
    {
        $this->totalMiembros = Miembro::count();
        $this->totalComisiones = Comision::count();
    }

    public function submitSuggestion()
    {
        if ($this->suggestion !== '') {
            session()->flash('message', '¡Gracias por tu sugerencia! Esta información será enviada al soporte técnico.');
            $this->suggestion = '';
        }
    }
};
?>

<div x-data="dashboard()" x-init="init()">
<div class="mt-6">

<!-- Breadcrumbs -->
<x-slot name="breadcrumbs">
    <nav class="flex items-center text-sm font-medium text-gray-600 dark:text-gray-300 space-x-2" aria-label="Breadcrumb">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 flex items-center gap-1">
            <x-icon name="home" class="w-4 h-4" />
            Dashboard
        </a>
    </nav>
</x-slot>

    <x-container class="w-full px-6">

   <!-- Tarjetas Estadísticas Mejoradas -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
    <a href="{{ route('admin.noticias.index') }}" wire:navigate class="block">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 flex items-center justify-between border-l-4 border-blue-500 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 cursor-pointer">
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Total Noticias</p>
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $this->totalNoticias }}</h3>
                <p class="text-blue-500 text-xs mt-2 flex items-center gap-1">
                    <i class="fa-solid fa-newspaper"></i> Publicadas
                </p>
            </div>
            <div class="p-4 bg-blue-100 dark:bg-blue-900/30 rounded-xl">
                <i class="fa-solid fa-newspaper text-blue-500 dark:text-blue-400 text-3xl"></i>
            </div>
        </div>
    </a>

<a href="{{ route('admin.sesion_municipal.index') }}" wire:navigate class="block">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 flex items-center justify-between border-l-4 border-green-500 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 cursor-pointer">
        <div>
            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Sesiones Activas</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $this->sesionesActivas }}</h3>
            <p class="text-green-500 text-xs mt-2 flex items-center gap-1">
                <i class="fa-solid fa-arrow-trend-up"></i> Disponibles ahora
            </p>
        </div>
        <div class="p-4 bg-green-100 dark:bg-green-900/30 rounded-xl">
            <i class="fa-solid fa-users text-green-500 dark:text-green-400 text-3xl"></i>
        </div>
    </div>
</a>
  <a href="{{ route('admin.comisiones.index') }}" wire:navigate class="block">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 flex items-center justify-between border-l-4 border-orange-500 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 cursor-pointer">
        <div>
            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Total Comisiones</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalComisiones }}</h3>
            <p class="text-blue-500 text-xs mt-2">Activas</p>
        </div>
        <div class="p-4 bg-orange-100 dark:bg-orange-900/30 rounded-xl">
            <i class="fa-solid fa-layer-group text-orange-500 dark:text-orange-400 text-3xl"></i>
        </div>
    </div>
</a>
<a href="{{ route('admin.miembros.index') }}" wire:navigate class="block">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 flex items-center justify-between border-l-4 border-purple-500 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 cursor-pointer">
        <div>
            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Miembros Totales</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalMiembros }}</h3>
            <p class="text-purple-500 text-xs mt-2">En comisiones</p>
        </div>
        <div class="p-4 bg-purple-100 dark:bg-purple-900/30 rounded-xl">
            <i class="fa-solid fa-user-group text-purple-500 dark:text-purple-400 text-3xl"></i>
        </div>
    </div>
</a>

        </div>

        <!-- Gráficos en Grid 2x2 -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
            <!-- Gráfica de Línea - Gestión Anual -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 relative hover:shadow-2xl transition-all duration-300 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-chart-line text-indigo-500"></i>
                            Gestión Anual
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Seguimiento mensual de gestiones</p>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="downloadPDF('lineChart')" class="bg-gradient-to-r from-red-500 to-red-600 text-white px-3 py-2 rounded-lg shadow-lg hover:scale-110 transition-all duration-300 text-sm font-semibold">
                            <i class="fa-solid fa-file-pdf"></i> PDF
                        </button>
                        <button onclick="downloadPNG('lineChart')" class="bg-gradient-to-r from-blue-500 to-indigo-500 text-white px-3 py-2 rounded-lg shadow-lg hover:scale-110 transition-all duration-300 text-sm font-semibold">
                            <i class="fa-solid fa-download"></i> PNG
                        </button>
                    </div>
                </div>
                <div class="h-72">
                    <canvas id="lineChart"></canvas>
                </div>
            </div>

            <!-- Gráfica de Barras - Sesiones -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 relative hover:shadow-2xl transition-all duration-300 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-chart-column text-blue-500"></i>
                            Sesiones Realizadas
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Actividad semanal</p>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="downloadPDF('barChart')" class="bg-gradient-to-r from-red-500 to-red-600 text-white px-3 py-2 rounded-lg shadow-lg hover:scale-110 transition-all duration-300 text-sm font-semibold">
                            <i class="fa-solid fa-file-pdf"></i> PDF
                        </button>
                        <button onclick="downloadPNG('barChart')" class="bg-gradient-to-r from-blue-500 to-indigo-500 text-white px-3 py-2 rounded-lg shadow-lg hover:scale-110 transition-all duration-300 text-sm font-semibold">
                            <i class="fa-solid fa-download"></i> PNG
                        </button>
                    </div>
                </div>
                <div class="h-72">
                    <canvas id="barChart"></canvas>
                </div>
            </div>

            <!-- Gráfica de Torta/Donut - Comisiones -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 relative hover:shadow-2xl transition-all duration-300 border border-gray-200 dark:border-gray-700 lg:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-chart-pie text-purple-500"></i>
                            Distribución por Comisiones
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Miembros activos en cada comisión</p>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="downloadPDF('doughnutChart')" class="bg-gradient-to-r from-red-500 to-red-600 text-white px-3 py-2 rounded-lg shadow-lg hover:scale-110 transition-all duration-300 text-sm font-semibold">
                            <i class="fa-solid fa-file-pdf"></i> PDF
                        </button>
                        <button onclick="downloadPNG('doughnutChart')" class="bg-gradient-to-r from-purple-500 to-pink-500 text-white px-3 py-2 rounded-lg shadow-lg hover:scale-110 transition-all duration-300 text-sm font-semibold">
                            <i class="fa-solid fa-download"></i> PNG
                        </button>
                    </div>
                </div>
                <div class="h-96 flex justify-center items-center">
                    <canvas id="doughnutChart" class="max-w-2xl"></canvas>
                </div>
            </div>
        </div>

        <!-- Botón Reset -->
        <div class="mt-6 flex justify-center">
            <button onclick="resetCharts()" class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-2xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2 font-semibold">
                <i class="fa-solid fa-rotate-right"></i>
                Reiniciar Gráficas
            </button>
        </div>
    </x-container>
</div>
</div>

@push('scripts')
@include('livewire.pages.admin.dashboard.js.script')

@endpush