<!-- Gráfica -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 sm:p-6 md:p-8 border border-gray-200 dark:border-gray-700 mb-6 sm:mb-8" 
     id="chartWrapper"
     data-chart-ready="{{ $this->chartReady ? 'true' : 'false' }}" 
     data-chart-sum="{{ array_sum($this->chartValues) }}"
     data-chart-labels="{{ json_encode($this->chartLabels) }}"
     data-chart-values="{{ json_encode($this->chartValues) }}">
    <h2 class="text-lg sm:text-xl font-bold text-gray-800 dark:text-white mb-2 flex items-center gap-2">
        <i class="fa-solid fa-chart-column text-blue-500 text-xl sm:text-2xl"></i> Crónicas del Cronista
    </h2>
    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-4">Visualización de publicaciones por período</p>
    <div class="relative h-64 sm:h-80 md:h-96 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-lg p-3 sm:p-4 border border-gray-200 dark:border-gray-700">
        <canvas id="cronicasBarChart" style="display: block; width: 100% !important; height: 100% !important;"></canvas>
        <div id="emptyState" class="absolute inset-0 flex items-center justify-center text-gray-400 dark:text-gray-500 rounded-lg bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 p-4">
            <div class="text-center">
                <i class="fa-solid fa-chart-line text-4xl sm:text-5xl mb-2 sm:mb-3 opacity-50"></i>
                <p class="text-xs sm:text-sm">{{ $this->chartReady ? 'No hay datos disponibles para este período' : 'Selecciona una fecha y haz click en Generar' }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Información del Filtro -->
<div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 rounded-xl p-4 sm:p-6 md:p-8 border border-gray-200 dark:border-gray-700 shadow-md">
    <h3 class="text-base sm:text-lg font-bold text-gray-800 dark:text-white mb-3 sm:mb-4 flex items-center gap-2">
        <i class="fa-solid fa-info-circle text-blue-500"></i> Información del Filtro
    </h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 sm:gap-4">
        <div class="bg-white dark:bg-gray-800 p-3 sm:p-5 rounded-lg border border-gray-200 dark:border-gray-700">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Cronista ID</p>
            <p class="text-base sm:text-lg font-bold text-gray-900 dark:text-white mt-1 sm:mt-2">{{ $this->cronista?->id ?? 'N/A' }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-3 sm:p-5 rounded-lg border border-gray-200 dark:border-gray-700">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Fecha</p>
            <p class="text-base sm:text-lg font-bold text-gray-900 dark:text-white mt-1 sm:mt-2 truncate">{{ $this->filterDate ?: 'No seleccionada' }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-3 sm:p-5 rounded-lg border border-gray-200 dark:border-gray-700">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Filtro</p>
            <p class="text-base sm:text-lg font-bold text-blue-600 dark:text-blue-400 mt-1 sm:mt-2 capitalize">{{ $this->filterType }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-3 sm:p-5 rounded-lg border border-gray-200 dark:border-gray-700">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Total Registros</p>
            <p class="text-base sm:text-lg font-bold text-green-600 dark:text-green-400 mt-1 sm:mt-2">{{ array_sum($this->chartValues) }}</p>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
    let cronicasBarChart = null;
    let lastChartSum = 0;

    function getChartColors() {
        // Detectar modo oscuro con múltiples métodos
        const hasClass = document.documentElement.classList.contains('dark');
        const hasAttr = document.documentElement.getAttribute('data-theme') === 'dark';
        const prefersScheme = window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        const isDarkMode = hasClass || hasAttr || prefersScheme;
        
        console.log(' Detección modo:', { hasClass, hasAttr, prefersScheme, isDarkMode });
        
        return {
            textColor: isDarkMode ? '#ffffff' : '#1f2937',
            gridColor: isDarkMode ? 'rgba(107, 114, 128, 0.3)' : 'rgba(107, 114, 128, 0.2)',
            backgroundColor: isDarkMode ? 'rgba(59, 130, 246, 0.9)' : 'rgba(59, 130, 246, 0.8)',
            borderColor: 'rgb(59, 130, 246)',
            bgTooltip: isDarkMode ? 'rgba(31, 41, 55, 0.95)' : 'rgba(55, 65, 81, 0.95)'
        };
    }

    function initChart() {
        const canvas = document.getElementById('cronicasBarChart');
        const emptyState = document.getElementById('emptyState');
        const wrapper = document.getElementById('chartWrapper');

        if (!canvas || !wrapper) {
            console.error(' Canvas o wrapper no encontrado');
            return;
        }

        // Leer datos directamente del atributo del DOM (lo que Livewire actualiza)
        const chartReadyStr = wrapper.getAttribute('data-chart-ready');
        const chartSumStr = wrapper.getAttribute('data-chart-sum');
        const labelsStr = wrapper.getAttribute('data-chart-labels');
        const valuesStr = wrapper.getAttribute('data-chart-values');

        let isReady = chartReadyStr === 'true';
        let chartSum = parseInt(chartSumStr) || 0;
        let labels = [];
        let values = [];

        try {
            if (labelsStr) labels = JSON.parse(labelsStr);
            if (valuesStr) values = JSON.parse(valuesStr);
        } catch (e) {
            console.error('Error al parsear JSON:', e);
            return;
        }

        console.log(' Datos cargados - Sum:', chartSum, 'Ready:', isReady);

        // Destruir gráfica anterior
        if (cronicasBarChart) {
            cronicasBarChart.destroy();
            cronicasBarChart = null;
        }

        // Decidir si mostrar gráfica o empty state
        const hasData = isReady && labels && Array.isArray(labels) && labels.length > 0 && chartSum > 0;

        if (!hasData) {
            canvas.style.display = 'none';
            if (emptyState) emptyState.style.display = 'flex';
            console.log('➜ Mostrando empty state');
            return;
        }

        // Mostrar canvas
        canvas.style.display = 'block';
        if (emptyState) emptyState.style.display = 'none';

        const colors = getChartColors();
        const ctx = canvas.getContext('2d');

        console.log('✓ Creando gráfica con colores:', colors);

        try {
            cronicasBarChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Cantidad de Crónicas',
                        data: values,
                        backgroundColor: colors.backgroundColor,
                        borderColor: colors.borderColor,
                        borderWidth: 2.5,
                        borderRadius: 8,
                        borderSkipped: false,
                        barThickness: 'flex',
                        maxBarThickness: 80
                    }]
                },
                options: {
                    indexAxis: 'x',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            labels: { 
                                color: colors.textColor, 
                                font: { size: 14, weight: 'bold' }
                            }
                        },               
                        datalabels: {
                            display: true,
                            color: colors.textColor,                     
                            offset: 4,
                            font: { weight: 'bold', size: 13 },
                            formatter: function(value) {
                                return value > 0 ? value : '';
                            }
                        }
                    },
                    scales: {
                        y: {                   
                            max: values.length > 0 ? Math.max(...values) + 2 : 5,
                            ticks: {                  
                                stepSize: 1,
                                font: { weight: 'bold', size: 12 }
                            },                      
                        },                   
                    }
                },
                plugins: [ChartDataLabels]
            });

            console.log(' Gráfica creada exitosamente');
        } catch (error) {
            console.error(' Error al crear gráfica:', error);
        }
    }

    // Polling para detectar cambios en los atributos del DOM
    setInterval(() => {
        const wrapper = document.getElementById('chartWrapper');
        if (!wrapper) return;

        const currentSum = parseInt(wrapper.getAttribute('data-chart-sum')) || 0;
        
        // Si el sum cambió, reinitializar
        if (currentSum !== lastChartSum) {
            console.log(`Cambio detectado: ${lastChartSum} -> ${currentSum}`);
            lastChartSum = currentSum;
            setTimeout(initChart, 50);
        }
    }, 500);

    // Observer para cambios de tema EN TIEMPO REAL
    const observer = new MutationObserver((mutations) => {
        for (let mutation of mutations) {
            if (mutation.type === 'attributes' && (mutation.attributeName === 'class' || mutation.attributeName === 'data-theme')) {
                console.log('Cambio de tema detectado - actualizando colores');
                if (cronicasBarChart) {
                    const colors = getChartColors();
                    
                    // Actualizar TODOS los colores del gráfico
                
                    cronicasBarChart.options.plugins.legend.labels.color = colors.textColor;
                    cronicasBarChart.options.plugins.tooltip.backgroundColor = colors.bgTooltip;
                    cronicasBarChart.options.plugins.datalabels.color = colors.textColor;
                    cronicasBarChart.options.scales.y.grid.color = colors.gridColor;
                    
                    // Forzar actualización del canvas
                    cronicasBarChart.update('none');
                } else {
                    // Si el gráfico no existe pero se cambió el tema, reinicializar
                    initChart();
                }
            }
        }
    });

    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class', 'data-theme']
    });

    // Inicializar cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', () => {
        console.log('DOM Ready - inicializando gráfica');
        initChart();
    });

    // Escuchar eventos de Livewire
    if (window.Livewire) {
        // Para Livewire 3
        Livewire.hook('morph.updated', () => {
            console.log('Livewire morph.updated - reinicializando');
            setTimeout(initChart, 100);
        });
    }

    // También con el evento genérico
    document.addEventListener('livewire:updated', () => {
        console.log('Livewire updated event');
        setTimeout(initChart, 100);
    });
    </script>

    <script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('showAlert', data => {
            Swal.fire({
                title: data.title || 'Aviso',
                text: data.text || 'Ocurrió un evento inesperado.',
                icon: data.icon || 'info',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#3085d6'
            });
        });
    });
    </script>

@endpush