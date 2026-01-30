<!-- Gráfica de Barras - Sesiones Completadas Premium -->
<div class="bg-gradient-to-br from-white via-blue-50 to-indigo-50 dark:from-gray-800 dark:via-gray-800 dark:to-gray-900 rounded-3xl shadow-2xl p-4 sm:p-6 md:p-8 relative hover:shadow-3xl transition-all duration-500 border border-gray-200 dark:border-gray-700/50 responsive-card group overflow-hidden">
    
    <!-- Fondo decorativo animado -->
    <div class="absolute inset-0 opacity-0 group-hover:opacity-10 transition-opacity duration-500">
        <div class="absolute top-0 right-0 w-40 h-40 bg-blue-500 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-indigo-500 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative z-10">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 sm:gap-0 mb-6 sm:mb-8">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2.5 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl shadow-lg">
                        <i class="fa-solid fa-chart-column text-white text-lg"></i>
                    </div>
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                        Sesiones Completadas
                    </h2>
                </div>
                <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400 font-medium ml-11 flex items-center gap-2">
                    <i class="fa-solid fa-calendar-check text-blue-500"></i>
                    Análisis mensual de sesiones completadas
                </p>
            </div>

            <!-- Botones mejorados -->
            <div class="flex gap-2 w-full sm:w-auto">
                <button onclick="downloadBarPDF('barChart')" class="group/btn bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg shadow-lg hover:shadow-2xl hover:scale-110 transition-all duration-300 text-xs sm:text-sm font-semibold flex-1 sm:flex-none flex items-center justify-center gap-2">
                    <i class="fa-solid fa-file-pdf"></i> 
                    <span class="hidden sm:inline">PDF</span>
                </button>
                <button onclick="downloadBarPNG('barChart')" class="group/btn bg-gradient-to-r from-blue-400 via-cyan-500 to-indigo-600 hover:from-blue-500 hover:via-cyan-600 hover:to-indigo-700 text-white px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg shadow-lg hover:shadow-2xl hover:scale-110 transition-all duration-300 text-xs sm:text-sm font-semibold flex-1 sm:flex-none flex items-center justify-center gap-2">
                    <i class="fa-solid fa-download"></i> 
                    <span class="hidden sm:inline">PNG</span>
                </button>
            </div>
        </div>

        <!-- Stats bar -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6 sm:mb-8">
            <div class="bg-white dark:bg-gray-700/50 rounded-xl p-3 border border-gray-200 dark:border-gray-600 hover:shadow-lg transition-all duration-300">
                <p class="text-xs text-gray-600 dark:text-gray-400 font-semibold">Total Sesiones</p>
                <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white mt-1" id="totalSesiones">0</p>
            </div>
            <div class="bg-white dark:bg-gray-700/50 rounded-xl p-3 border border-gray-200 dark:border-gray-600 hover:shadow-lg transition-all duration-300">
                <p class="text-xs text-gray-600 dark:text-gray-400 font-semibold">Promedio Mensual</p>
                <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white mt-1" id="promedioSesiones">0</p>
            </div>
            <div class="bg-white dark:bg-gray-700/50 rounded-xl p-3 border border-gray-200 dark:border-gray-600 hover:shadow-lg transition-all duration-300">
                <p class="text-xs text-gray-600 dark:text-gray-400 font-semibold">Mayor Mes</p>
                <p class="text-lg sm:text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1" id="mayorMesSesiones">0</p>
            </div>
            <div class="bg-white dark:bg-gray-700/50 rounded-xl p-3 border border-gray-200 dark:border-gray-600 hover:shadow-lg transition-all duration-300">
                <p class="text-xs text-gray-600 dark:text-gray-400 font-semibold">Menor Mes</p>
                <p class="text-lg sm:text-2xl font-bold text-indigo-600 dark:text-indigo-400 mt-1" id="menorMesSesiones">0</p>
            </div>
        </div>

        <!-- Gráfica -->
        <div class="w-full h-72 sm:h-80 md:h-96 flex justify-center items-center bg-white dark:bg-gray-700/30 rounded-2xl backdrop-blur-sm border border-gray-100 dark:border-gray-600/50 hover:border-blue-300 dark:hover:border-blue-500/50 transition-all duration-300 p-4 responsive-chart-container">
            <canvas id="barChart" class="max-w-full"></canvas>
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


<script>
window.barChartInstance = null;

function downloadBarPNG(chartId) { 
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
    ctx.fillText('Reporte - Sesiones Completadas por Mes', 40, 50);

    // Subtítulo
    ctx.fillStyle = '#6b7280';
    ctx.font = '16px Arial';
    ctx.fillText('Análisis mensual de sesiones completadas', 40, 80);

    // Línea separadora
    ctx.strokeStyle = '#e5e7eb';
    ctx.lineWidth = 2;
    ctx.beginPath();
    ctx.moveTo(40, 95);
    ctx.lineTo(1160, 95);
    ctx.stroke();

    // Estadísticas
    const total = document.getElementById('totalSesiones').textContent;
    const promedio = document.getElementById('promedioSesiones').textContent;
    const mayor = document.getElementById('mayorMesSesiones').textContent;
    const menor = document.getElementById('menorMesSesiones').textContent;

    const stats = [
        { label: 'Total', value: total, color: '#3b82f6' },
        { label: 'Promedio', value: promedio, color: '#8b5cf6' },
        { label: 'Mayor', value: mayor, color: '#06b6d4' },
        { label: 'Menor', value: menor, color: '#f59e0b' }
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
            a.download = 'sesiones_completadas_' + new Date().getTime() + '.png';
            a.click();
            URL.revokeObjectURL(url);
        });
    };
    img.src = originalImage;
}

function actualizarEstadisticasBarras(datos, etiquetas) {
    const total = datos.reduce((a, b) => a + b, 0);
    const promedio = (total / datos.length).toFixed(1);
    const mayor = Math.max(...datos);
    const menor = Math.min(...datos);
    const indexMayor = datos.indexOf(mayor);

    const safeSet = (id, value) => {
        const el = document.getElementById(id);
        if (el) el.textContent = value;
    };

    safeSet('totalSesiones', total);
    safeSet('promedioSesiones', promedio);
    safeSet('mayorMesSesiones', mayor);
    safeSet('menorMesSesiones', menor);

    // Estos dos solo se aplicarán si los elementos existen
    safeSet('mesDestacado', etiquetas[indexMayor]);
    safeSet('sesionesDestacado', mayor);
}

function initBarChart(datosSesiones = [], mesesSesiones = []) {
    const barCtx = document.getElementById('barChart');
    if (!barCtx) return;
    if (window.barChartInstance) {
        window.barChartInstance.destroy();  
    }
    const ctx = barCtx.getContext('2d');
    
    const datos = datosSesiones && datosSesiones.length > 0 ? datosSesiones : [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
    const etiquetas = mesesSesiones && mesesSesiones.length === 12 ? mesesSesiones : ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

    const isMobile = window.innerWidth < 768;
    const fontSize = isMobile ? 11 : 14;
    const barPercentage = isMobile ? 0.7 : 0.85;
    const categoryPercentage = isMobile ? 0.6 : 0.8;

    window.barChartInstance = new Chart(ctx, { 
        type: 'bar',
        data: { 
            labels: etiquetas, 
            datasets: [{ 
                label: 'Sesiones Completadas', 
                data: datos,       
                barPercentage: barPercentage,     
                categoryPercentage: categoryPercentage, 
                backgroundColor: [
                    'rgba(59, 130, 246, 0.85)', 'rgba(6, 182, 212, 0.85)', 'rgba(34, 197, 94, 0.85)',
                    'rgba(139, 92, 246, 0.85)', 'rgba(236, 72, 153, 0.85)', 'rgba(59, 130, 246, 0.85)',
                    'rgba(6, 182, 212, 0.85)', 'rgba(34, 197, 94, 0.85)', 'rgba(139, 92, 246, 0.85)',
                    'rgba(236, 72, 153, 0.85)', 'rgba(59, 130, 246, 0.85)', 'rgba(6, 182, 212, 0.85)',
                ],
                borderColor: [
                    'rgb(59, 130, 246)', 'rgb(6, 182, 212)', 'rgb(34, 197, 94)',
                    'rgb(139, 92, 246)', 'rgb(236, 72, 153)', 'rgb(59, 130, 246)',
                    'rgb(6, 182, 212)', 'rgb(34, 197, 94)', 'rgb(139, 92, 246)',
                    'rgb(236, 72, 153)', 'rgb(59, 130, 246)', 'rgb(6, 182, 212)',
                ],
                borderWidth: 2,
                borderRadius: 12,
                hoverBackgroundColor: [
                    'rgba(59, 130, 246, 1)', 'rgba(6, 182, 212, 1)', 'rgba(34, 197, 94, 1)',
                    'rgba(139, 92, 246, 1)', 'rgba(236, 72, 153, 1)', 'rgba(59, 130, 246, 1)',
                    'rgba(6, 182, 212, 1)', 'rgba(34, 197, 94, 1)', 'rgba(139, 92, 246, 1)',
                    'rgba(236, 72, 153, 1)', 'rgba(59, 130, 246, 1)', 'rgba(6, 182, 212, 1)',
                ],
                hoverBorderWidth: 3,
            }] 
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            animation: { 
                duration: 2000, 
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
                    labels: {
                        color: '#6b7280',
                        font: { size: fontSize, weight: 'bold' },
                        padding: 15,
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.9)',
                    padding: 14,
                    cornerRadius: 10,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    callbacks: {
                        label: function(context) {
                            return '📊 Sesiones: ' + context.parsed.y;
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

    actualizarEstadisticasBarras(datos, etiquetas);
}

window.datosSesionesGlobal = @json($datosSesiones ?? []);
window.mesesSesionesGlobal = @json($mesesSesiones ?? []);

document.addEventListener('DOMContentLoaded', function() {
    initBarChart(window.datosSesionesGlobal, window.mesesSesionesGlobal);
});

document.addEventListener('livewire:navigated', function() {
    setTimeout(() => {
        initBarChart(window.datosSesionesGlobal, window.mesesSesionesGlobal);
    }, 100);
});

document.addEventListener('livewire:updated', function() {
    initBarChart(window.datosSesionesGlobal, window.mesesSesionesGlobal);
});

window.addEventListener('resize', () => {
    if (window.barChartInstance) {
        window.barChartInstance.resize();
    }
});
</script>