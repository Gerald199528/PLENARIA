<?php

use Livewire\Volt\Component;
use App\Models\Miembro;
use App\Models\Comision;
use App\Models\SesionMunicipal;
use App\Models\Noticia;
use App\Models\DerechoDePalabra;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Computed;

new class extends Component {
    // Variables de ejemplo
    public $lineData = [];
    public $barData = [];
    public $doughnutData = [];
    public $suggestion = '';
    public $lat = null;
    public $lng = null;
    public $currentTime = null;
    public $lastConnection = null;
    
    // Variables para Tarjetas
    public $totalMiembros = 0;
    public $totalComisiones = 0;
    
    // 📊 Propiedades Públicas para Derecho de Palabra
    public $meses = []; 
    public $datosAprobadas = [];
    public $datosPendientes = [];
    public $datosRechazadas = [];
    
    // 📊 Propiedades Públicas para Sesiones Completadas
    public $mesesSesiones = []; 
    public $datosSesiones = [];

    // 📊 Propiedades Públicas para Gráfica de Torta (Concejales por Comisión)
    public $comisionesLabels = [];
    public $comisionesData = [];
    public $comisionesColores = [];

    // --- Propiedades Calculadas ---
    
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

    // --- Ciclo de Vida ---
    
    public function mount()
    {
        $this->totalMiembros = Miembro::count();
        $this->totalComisiones = Comision::count();
        $this->currentTime = now()->format('Y-m-d H:i:s');
        $this->lastConnection = now()->subMinutes(5)->format('Y-m-d H:i:s');

        $this->cargarDatosGraficaDerecho();
        $this->cargarDatosGraficaSesiones();
        $this->cargarDatosGraficaTorta();
        
        // Datos de prueba originales
        $this->lineData = [['time' => '2025-01-01', 'value' => 120]]; // resumido
        $this->barData = ['labels' => ['Enero'], 'values' => [50]]; // resumido
        $this->doughnutData = ['labels' => ['A'], 'values' => [10]]; // resumido
    }

    // --- Lógica de la Gráfica de Sesiones ---
    
    public function cargarDatosGraficaSesiones()
    {
        $this->mesesSesiones = [];
        $this->datosSesiones = [];

        for ($i = 11; $i >= 0; $i--) {
            $fecha = Carbon::now()->subMonths($i);
            
            $this->mesesSesiones[] = ucfirst($fecha->translatedFormat('M')); 
            
            $sesiones = SesionMunicipal::where(function($query) {
                    $query->where('estado', 'Completada')
                          ->orWhere('estado', 'completada');
                })
                ->whereMonth('fecha_hora', $fecha->month) 
                ->whereYear('fecha_hora', $fecha->year)
                ->count();

            $this->datosSesiones[] = $sesiones;
        }
    }
    
    // --- Lógica de Derecho de Palabra ---
    public function cargarDatosGraficaDerecho()
    {
        $this->meses = [];
        $this->datosAprobadas = [];
        $this->datosPendientes = [];
        $this->datosRechazadas = [];

        for ($i = 11; $i >= 0; $i--) {
            $fecha = Carbon::now()->subMonths($i);
            
            $this->meses[] = ucfirst($fecha->translatedFormat('M')); 
            
            $this->datosAprobadas[] = DerechoDePalabra::where('estado', 'aprobada')
                ->whereMonth('created_at', $fecha->month)->whereYear('created_at', $fecha->year)->count();
            
            $this->datosPendientes[] = DerechoDePalabra::where('estado', 'pendiente')
                ->whereMonth('created_at', $fecha->month)->whereYear('created_at', $fecha->year)->count();
            
            $this->datosRechazadas[] = DerechoDePalabra::where('estado', 'rechazada')
                ->whereMonth('created_at', $fecha->month)->whereYear('created_at', $fecha->year)->count();
        }
    }
// --- Lógica de Gráfica de Torta (Concejales por Comisión) ---
public function cargarDatosGraficaTorta()
{
    $this->comisionesLabels = [];
    $this->comisionesData = [];
    $this->comisionesColores = [];

    $colores = [
        'rgba(59, 130, 246, 0.8)',      // Azul
        'rgba(16, 185, 129, 0.8)',      // Verde
        'rgba(251, 146, 60, 0.8)',      // Naranja
        'rgba(139, 92, 246, 0.8)',      // Púrpura
        'rgba(236, 72, 153, 0.8)',      // Rosa
        'rgba(245, 158, 11, 0.8)',      // Ámbar
        'rgba(34, 197, 94, 0.8)',       // Verde Lima
        'rgba(168, 85, 247, 0.8)',      // Violeta
        'rgba(14, 165, 233, 0.8)',      // Cielo
    ];

    // Query: Concejales Y Comisiones
    $datos = \DB::table('comision_concejal')
        ->join('comisions', 'comision_concejal.comision_id', '=', 'comisions.id')
        ->join('concejal', 'comision_concejal.concejal_id', '=', 'concejal.id')
        ->join('miembros', 'comision_concejal.miembro_id', '=', 'miembros.id')
        ->select(
            'comisions.nombre as comision',
            \DB::raw("CONCAT(concejal.nombre, ' ', concejal.apellido) as nombre_concejal"),
            'miembros.estado',
            \DB::raw('COUNT(*) as cantidad')
        )
        ->where('miembros.estado', 'Activo')
        ->groupBy('comisions.nombre', 'concejal.nombre', 'concejal.apellido', 'miembros.estado')
        ->orderBy('comisions.nombre')
        ->get();

    $colorIndex = 0;
    $etiquetasUnicas = [];
    
    foreach ($datos as $registro) {
        // Formato: "Comisión - Concejal (Miembro Activo)"
        $etiqueta = $registro->comision . ' - ' . $registro->nombre_concejal;
        
        if (!in_array($etiqueta, $etiquetasUnicas)) {
            $this->comisionesLabels[] = $etiqueta;
            $this->comisionesData[] = $registro->cantidad;
            $this->comisionesColores[] = $colores[$colorIndex % count($colores)];
            $etiquetasUnicas[] = $etiqueta;
            $colorIndex++;
        }
    }

    // Si no hay datos, añadir placeholder
    if (empty($this->comisionesLabels)) {
        $this->comisionesLabels = ['Sin datos disponibles'];
        $this->comisionesData = [1];
        $this->comisionesColores = ['rgba(229, 231, 235, 0.8)'];
    }
}
    // --- Métodos Públicos ---
    
    public function refreshData()
    {
        $this->totalMiembros = Miembro::count();
        $this->totalComisiones = Comision::count();
        $this->cargarDatosGraficaDerecho();
        $this->cargarDatosGraficaSesiones();
        $this->cargarDatosGraficaTorta();
    }
    
    public function submitSuggestion()
    {
        if ($this->suggestion !== '') {
            session()->flash('message', 'Gracias.');
            $this->suggestion = '';
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
                'name' => 'Dashboard',
            ],
        ]" />
    </x-slot>

    <x-container class="w-full px-6">
        <!-- Tarjetas Estadísticas Mejoradas -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 mt-6 px-2 sm:px-0">
            
            <!-- Tarjeta Noticias -->
            <a href="{{ route('admin.noticias.index') }}" wire:navigate class="block">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 sm:p-6 flex items-center justify-between border-l-4 border-blue-500 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm font-medium mb-1">Total Noticias</p>
                        <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ $this->totalNoticias }}</h3>
                        <p class="text-blue-500 text-xs mt-1 sm:mt-2 flex items-center gap-1">
                            <i class="fa-solid fa-newspaper"></i> Publicadas
                        </p>
                    </div>
                    <div class="p-3 sm:p-4 bg-blue-100 dark:bg-blue-900/30 rounded-xl">
                        <i class="fa-solid fa-newspaper text-2xl sm:text-3xl text-blue-500 dark:text-blue-400"></i>
                    </div>
                </div>
            </a>

            <!-- Tarjeta Sesiones -->
            <a href="{{ route('admin.sesion_municipal.index') }}" wire:navigate class="block">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 sm:p-6 flex items-center justify-between border-l-4 border-green-500 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm font-medium mb-1">Sesiones Activas</p>
                        <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ $this->sesionesActivas }}</h3>
                        <p class="text-green-500 text-xs mt-1 sm:mt-2 flex items-center gap-1">
                            <i class="fa-solid fa-arrow-trend-up"></i> Disponibles ahora
                        </p>
                    </div>
                    <div class="p-3 sm:p-4 bg-green-100 dark:bg-green-900/30 rounded-xl">
                        <i class="fa-solid fa-users text-2xl sm:text-3xl text-green-500 dark:text-green-400"></i>
                    </div>
                </div>
            </a>

            <!-- Tarjeta Comisiones -->
            <a href="{{ route('admin.comisiones.index') }}" wire:navigate class="block">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 sm:p-6 flex items-center justify-between border-l-4 border-orange-500 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm font-medium mb-1">Total Comisiones</p>
                        <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ $totalComisiones }}</h3>
                        <p class="text-blue-500 text-xs mt-1 sm:mt-2">Activas</p>
                    </div>
                    <div class="p-3 sm:p-4 bg-orange-100 dark:bg-orange-900/30 rounded-xl">
                        <i class="fa-solid fa-layer-group text-2xl sm:text-3xl text-orange-500 dark:text-orange-400"></i>
                    </div>
                </div>
            </a>

            <!-- Tarjeta Miembros -->
            <a href="{{ route('admin.miembros.index') }}" wire:navigate class="block">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 sm:p-6 flex items-center justify-between border-l-4 border-purple-500 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm font-medium mb-1">Miembros Totales</p>
                        <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ $totalMiembros }}</h3>
                        <p class="text-purple-500 text-xs mt-1 sm:mt-2">En comisiones</p>
                    </div>
                    <div class="p-3 sm:p-4 bg-purple-100 dark:bg-purple-900/30 rounded-xl">
                        <i class="fa-solid fa-user-group text-2xl sm:text-3xl text-purple-500 dark:text-purple-400"></i>
                    </div>
                </div>
            </a>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">  
            @include('livewire.pages.admin.dashboard.form.chartjs_linea') 
            @include('livewire.pages.admin.dashboard.form.chartjs_barras')
            @include('livewire.pages.admin.dashboard.form.chartjs_Torta')
        </div>
    </x-container>
</div>

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
@endpush