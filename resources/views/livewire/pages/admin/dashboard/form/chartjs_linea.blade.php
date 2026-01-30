<!-- Gráfica de Línea - Atención al Público -->
<div class="bg-gradient-to-br from-white via-blue-50 to-indigo-50 dark:from-gray-800 dark:via-gray-800 dark:to-gray-900 rounded-3xl shadow-2xl p-4 sm:p-6 md:p-8 relative hover:shadow-3xl transition-all duration-500 border border-gray-200 dark:border-gray-700/50 group overflow-hidden">
    
    <!-- Fondo decorativo animado -->
    <div class="absolute inset-0 opacity-0 group-hover:opacity-10 transition-opacity duration-500">
        <div class="absolute top-0 right-0 w-40 h-40 bg-indigo-500 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-blue-500 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative z-10">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 sm:gap-0 mb-6 sm:mb-8">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2.5 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg">
                        <i class="fa-solid fa-chart-line text-white text-lg"></i>
                    </div>
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                        Participación Ciudadana
                    </h2>
                </div>
                <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400 font-medium ml-11 flex items-center gap-2">
                    <i class="fa-solid fa-chart-line text-indigo-500"></i>
                    Análisis mensual de solicitudes de participación ciudadana
                </p>
            </div>

            <!-- Botones mejorados -->
            <div class="flex gap-2 w-full sm:w-auto">
                <button onclick="downloadLineChartPDF()" class="group/btn bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg shadow-lg hover:shadow-2xl hover:scale-110 transition-all duration-300 text-xs sm:text-sm font-semibold flex-1 sm:flex-none flex items-center justify-center gap-2">
                    <i class="fa-solid fa-file-pdf"></i> 
                    <span class="hidden sm:inline">PDF</span>
                </button>
                <button onclick="downloadLinePNG('lineChart')" class="group/btn bg-gradient-to-r from-blue-400 via-cyan-500 to-indigo-600 hover:from-blue-500 hover:via-cyan-600 hover:to-indigo-700 text-white px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg shadow-lg hover:shadow-2xl hover:scale-110 transition-all duration-300 text-xs sm:text-sm font-semibold flex-1 sm:flex-none flex items-center justify-center gap-2">
                    <i class="fa-solid fa-download"></i> 
                    <span class="hidden sm:inline">PNG</span>
                </button>
            </div>
        </div>
        <!-- Stats bar -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6 sm:mb-8">
            <div class="bg-white dark:bg-gray-700/50 rounded-xl p-3 border border-gray-200 dark:border-gray-600 hover:shadow-lg transition-all duration-300">
                <p class="text-xs text-gray-600 dark:text-gray-400 font-semibold">Total Solicitudes</p>
                <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white mt-1" id="totalSolicitudes">0</p>
            </div>
            <div class="bg-white dark:bg-gray-700/50 rounded-xl p-3 border border-gray-200 dark:border-gray-600 hover:shadow-lg transition-all duration-300">
                <p class="text-xs text-gray-600 dark:text-gray-400 font-semibold">Aprobadas</p>
                <p class="text-lg sm:text-2xl font-bold text-indigo-600 dark:text-indigo-400 mt-1" id="totalAprobadas">0</p>
            </div>
            <div class="bg-white dark:bg-gray-700/50 rounded-xl p-3 border border-gray-200 dark:border-gray-600 hover:shadow-lg transition-all duration-300">
                <p class="text-xs text-gray-600 dark:text-gray-400 font-semibold">Pendientes</p>
                <p class="text-lg sm:text-2xl font-bold text-amber-600 dark:text-amber-400 mt-1" id="totalPendientes">0</p>
            </div>
            <div class="bg-white dark:bg-gray-700/50 rounded-xl p-3 border border-gray-200 dark:border-gray-600 hover:shadow-lg transition-all duration-300">
                <p class="text-xs text-gray-600 dark:text-gray-400 font-semibold">Rechazadas</p>
                <p class="text-lg sm:text-2xl font-bold text-red-600 dark:text-red-400 mt-1" id="totalRechazadas">0</p>
            </div>
        </div>

        <!-- Gráfica -->
        <div class="w-full h-72 sm:h-80 md:h-96 flex justify-center items-center bg-white dark:bg-gray-700/30 rounded-2xl backdrop-blur-sm border border-gray-100 dark:border-gray-600/50 hover:border-indigo-300 dark:hover:border-indigo-500/50 transition-all duration-300 p-4">
            <canvas id="lineChart" class="max-w-full"></canvas>
        </div>


    </div>
</div>

<style>
    @media (max-width: 768px) {
        .responsive-card {
            padding: 1rem;
        }
        
        .responsive-chart-container {
            height: 250px !important;
        }
    }
    
    @media (max-width: 640px) {
        .responsive-card {
            padding: 0.875rem;
            border-radius: 1rem;
        }
        
        .responsive-chart-container {
            height: 220px !important;
        }
    }
    
    @media (max-width: 480px) {
        .responsive-card {
            padding: 0.75rem;
        }
        
        .responsive-chart-container {
            height: 200px !important;
        }
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
window.lineChartInstance = null;

function downloadLinePNG(chartId){ 
    const chartCanvas = document.getElementById(chartId);
    if(!chartCanvas) return;

    const tempCanvas = document.createElement('canvas');
    tempCanvas.width = 1200;
    tempCanvas.height = 900;
    const ctx = tempCanvas.getContext('2d');

    // Fondo blanco
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, tempCanvas.width, tempCanvas.height);

    // Título
    ctx.fillStyle = '#1f2937';
    ctx.font = 'bold 36px Arial';
    ctx.fillText('Reporte - Participación Ciudadana', 40, 50);

    // Subtítulo
    ctx.fillStyle = '#6b7280';
    ctx.font = '16px Arial';
    ctx.fillText('Análisis mensual de solicitudes de participación ciudadana', 40, 80);

    // Línea separadora
    ctx.strokeStyle = '#e5e7eb';
    ctx.lineWidth = 2;
    ctx.beginPath();
    ctx.moveTo(40, 95);
    ctx.lineTo(1160, 95);
    ctx.stroke();

    // Estadísticas
    const total = document.getElementById('totalSolicitudes').textContent;
    const aprobadas = document.getElementById('totalAprobadas').textContent;
    const pendientes = document.getElementById('totalPendientes').textContent;
    const rechazadas = document.getElementById('totalRechazadas').textContent;

    const stats = [
        { label: 'Total', value: total, color: '#3b82f6' },
        { label: 'Aprobadas', value: aprobadas, color: '#6366f1' },
        { label: 'Pendientes', value: pendientes, color: '#f59e0b' },
        { label: 'Rechazadas', value: rechazadas, color: '#ef4444' }
    ];

    let xPos = 40;
    const statHeight = 60;
    stats.forEach((stat, idx) => {
        ctx.fillStyle = stat.color + '15';
        ctx.fillRect(xPos, 120, 260, statHeight);
        
        ctx.strokeStyle = stat.color;
        ctx.lineWidth = 2;
        ctx.strokeRect(xPos, 120, 260, statHeight);

        ctx.fillStyle = '#6b7280';
        ctx.font = '12px Arial';
        ctx.fillText(stat.label, xPos + 15, 140);

        ctx.fillStyle = stat.color;
        ctx.font = 'bold 28px Arial';
        ctx.fillText(stat.value, xPos + 15, 170);

        xPos += 280;
    });

    // Gráfica
    const originalImage = chartCanvas.toDataURL('image/png');
    const img = new Image();
    img.onload = function() {
        ctx.drawImage(img, 50, 220, 1100, 550);

        // Fecha
        ctx.fillStyle = '#9ca3af';
        ctx.font = '12px Arial';
        ctx.fillText('Generado: ' + new Date().toLocaleDateString('es-ES') + ' ' + new Date().toLocaleTimeString('es-ES'), 40, 880);

        // Descargar
        tempCanvas.toBlob(blob => {
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'participacion_ciudadana_' + new Date().getTime() + '.png';
            a.click();
            URL.revokeObjectURL(url);
        });
    };
    img.src = originalImage;
}

function actualizarEstadisticasLinea(aprobadas, pendientes, rechazadas) {
    const totalAprobadas = aprobadas.reduce((a, b) => a + b, 0);
    const totalPendientes = pendientes.reduce((a, b) => a + b, 0);
    const totalRechazadas = rechazadas.reduce((a, b) => a + b, 0);
    const totalGeneral = totalAprobadas + totalPendientes + totalRechazadas;

    document.getElementById('totalSolicitudes').textContent = totalGeneral;
    document.getElementById('totalAprobadas').textContent = totalAprobadas;
    document.getElementById('totalPendientes').textContent = totalPendientes;
    document.getElementById('totalRechazadas').textContent = totalRechazadas;
}

function initLineChart(meses = [], aprobadas = [], pendientes = [], rechazadas = []) {
    const lineCtx = document.getElementById('lineChart');
    if (!lineCtx) return; 
    
    if (window.lineChartInstance) {
        window.lineChartInstance.destroy();
    }    
    
    const ctx = lineCtx.getContext('2d');
    
    const gradientAprobadas = ctx.createLinearGradient(0, 0, 0, 400);
    gradientAprobadas.addColorStop(0, 'rgba(99, 102, 241, 0.3)');
    gradientAprobadas.addColorStop(1, 'rgba(99, 102, 241, 0.0)');

    const etiquetas = meses && meses.length === 12 ? meses : ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    const datosAprobadas = aprobadas && aprobadas.length > 0 ? aprobadas : [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
    const datosPendientes = pendientes && pendientes.length > 0 ? pendientes : [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
    const datosRechazadas = rechazadas && rechazadas.length > 0 ? rechazadas : [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

    const isMobile = window.innerWidth < 768;
    const pointRadius = isMobile ? 4 : 6;
    const pointHoverRadius = isMobile ? 7 : 10;
    const fontSize = isMobile ? 10 : 13;

    window.lineChartInstance = new Chart(ctx, {
        type: 'line',
        data: { 
            labels: etiquetas, 
            datasets: [
                { 
                    label: 'Solicitudes Aprobadas', 
                    data: datosAprobadas, 
                    borderColor: '#6366f1', 
                    backgroundColor: gradientAprobadas, 
                    fill: true, 
                    tension: 0.4, 
                    pointRadius: pointRadius, 
                    pointHoverRadius: pointHoverRadius, 
                    pointBackgroundColor: '#6366f1', 
                    pointBorderColor: '#fff', 
                    pointBorderWidth: 3,
                    pointHoverBackgroundColor: '#4f46e5', 
                    pointHoverBorderColor: '#fff', 
                    pointHoverBorderWidth: 4,
                    borderWidth: 3,
                },
                { 
                    label: 'Solicitudes Pendientes', 
                    data: datosPendientes, 
                    borderColor: '#f59e0b', 
                    backgroundColor: 'rgba(245, 158, 11, 0.05)', 
                    fill: false, 
                    tension: 0.4, 
                    pointRadius: pointRadius, 
                    pointHoverRadius: pointHoverRadius, 
                    pointBackgroundColor: '#f59e0b', 
                    pointBorderColor: '#fff', 
                    pointBorderWidth: 3,
                    pointHoverBackgroundColor: '#d97706', 
                    pointHoverBorderColor: '#fff', 
                    pointHoverBorderWidth: 4,
                    borderWidth: 3,
                },
                { 
                    label: 'Solicitudes Rechazadas', 
                    data: datosRechazadas, 
                    borderColor: '#ef4444', 
                    backgroundColor: 'rgba(239, 68, 68, 0.05)', 
                    fill: false, 
                    tension: 0.4, 
                    pointRadius: pointRadius, 
                    pointHoverRadius: pointHoverRadius, 
                    pointBackgroundColor: '#ef4444', 
                    pointBorderColor: '#fff', 
                    pointBorderWidth: 3,
                    pointHoverBackgroundColor: '#dc2626', 
                    pointHoverBorderColor: '#fff', 
                    pointHoverBorderWidth: 4,
                    borderWidth: 3,
                },
            ] 
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            animation: { 
                duration: 2500, 
                easing: 'easeInOutQuart',
                delay: (ctx) => {
                    let delay = 0;
                    if (ctx.type === 'data') {
                        delay = ctx.dataIndex * 50;
                    }
                    return delay;
                },
            },
            plugins: { 
                legend: { 
                    display: true, 
                    position: 'top',
                    labels: { 
                        color: '#6b7280', 
                        font: { size: fontSize, weight: '600' }, 
                        padding: isMobile ? 10 : 20, 
                        usePointStyle: true,
                        pointStyle: 'circle',
                    }
                }, 
                tooltip: { 
                    enabled: true, 
                    mode: 'index', 
                    intersect: false,
                    backgroundColor: 'rgba(0, 0, 0, 0.9)',
                    titleColor: '#fff', 
                    bodyColor: '#fff', 
                    padding: 14, 
                    cornerRadius: 10,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    callbacks: {
                        label: function(context) {
                            const label = context.dataset.label || '';
                            const value = context.parsed.y;
                            return label + ': ' + value + ' solicitudes';
                        }
                    }
                } 
            }, 
            scales: { 
                y: { 
                    beginAtZero: true,
                    grid: { color: 'rgba(107, 114, 128, 0.1)', drawBorder: false },
                    ticks: { 
                        color: '#6b7280', 
                        stepSize: 5,
                        font: { 
                            size: fontSize, 
                            weight: '600' 
                        } 
                    } 
                },
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: { 
                        color: '#6b7280',
                        font: { 
                            size: fontSize, 
                            weight: '600' 
                        }
                    }
                }
            } 
        }
    });

    actualizarEstadisticasLinea(datosAprobadas, datosPendientes, datosRechazadas);
}

// Variables globales para los datos
window.mesesGlobal = @json($meses ?? []);
window.datosAprobadasGlobal = @json($datosAprobadas ?? []);
window.datosPendientesGlobal = @json($datosPendientes ?? []);
window.datosRechazadasGlobal = @json($datosRechazadas ?? []);

// Inicializar
document.addEventListener('DOMContentLoaded', function() {
    initLineChart(window.mesesGlobal, window.datosAprobadasGlobal, window.datosPendientesGlobal, window.datosRechazadasGlobal);
});

document.addEventListener('livewire:navigated', function() {
    setTimeout(() => {
        initLineChart(window.mesesGlobal, window.datosAprobadasGlobal, window.datosPendientesGlobal, window.datosRechazadasGlobal);
    }, 100);
});

document.addEventListener('livewire:updated', function() {
    initLineChart(window.mesesGlobal, window.datosAprobadasGlobal, window.datosPendientesGlobal, window.datosRechazadasGlobal);
});

// Redimensionar gráfico
window.addEventListener('resize', () => {
    if (window.lineChartInstance) {
        window.lineChartInstance.resize();
    }
});
</script>