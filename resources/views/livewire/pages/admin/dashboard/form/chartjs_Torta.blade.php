<!-- Gráfica de Torta/Donut - Comisiones Premium -->
<div class="bg-gradient-to-br from-white via-blue-50 to-indigo-50 dark:from-gray-800 dark:via-gray-800 dark:to-gray-900 rounded-3xl shadow-2xl p-4 sm:p-6 md:p-8 relative hover:shadow-3xl transition-all duration-500 border border-gray-200 dark:border-gray-700/50 lg:col-span-2 group overflow-hidden">
    
    <!-- Fondo decorativo animado -->
    <div class="absolute inset-0 opacity-0 group-hover:opacity-10 transition-opacity duration-500">
        <div class="absolute top-0 right-0 w-40 h-40 bg-purple-500 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-blue-500 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative z-10">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 sm:gap-0 mb-6 sm:mb-8">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2.5 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl shadow-lg">
                        <i class="fa-solid fa-chart-pie text-white text-lg"></i>
                    </div>
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                        Distribución por Comisiones
                    </h2>
                </div>
                <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400 font-medium ml-11 flex items-center gap-2">
                    <i class="fa-solid fa-users text-purple-500"></i>
                    Concejales activos registrados
                </p>
            </div>
<!-- Botones mejorados - Azul -->
            <div class="flex gap-2 w-full sm:w-auto">
                <button onclick="downloadDoughnutPDF()" class="group/btn bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg shadow-lg hover:shadow-2xl hover:scale-110 transition-all duration-300 text-xs sm:text-sm font-semibold flex-1 sm:flex-none flex items-center justify-center gap-2">
                    <i class="fa-solid fa-file-pdf"></i> 
                    <span class="hidden sm:inline">PDF</span>
                </button>
                <button onclick="downloadPNG('doughnutChart')" class="group/btn bg-gradient-to-r from-blue-400 via-cyan-500 to-indigo-600 hover:from-blue-500 hover:via-cyan-600 hover:to-indigo-700 text-white px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg shadow-lg hover:shadow-2xl hover:scale-110 transition-all duration-300 text-xs sm:text-sm font-semibold flex-1 sm:flex-none flex items-center justify-center gap-2">
                    <i class="fa-solid fa-download"></i> 
                    <span class="hidden sm:inline">PNG</span>
                </button>
            </div>
        </div>

        <!-- Stats bar -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6 sm:mb-8">
            <div class="bg-white dark:bg-gray-700/50 rounded-xl p-3 border border-gray-200 dark:border-gray-600 hover:shadow-lg transition-all duration-300">
                <p class="text-xs text-gray-600 dark:text-gray-400 font-semibold">Total</p>
                <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white mt-1" id="totalConcejales">0</p>
            </div>
            <div class="bg-white dark:bg-gray-700/50 rounded-xl p-3 border border-gray-200 dark:border-gray-600 hover:shadow-lg transition-all duration-300">
                <p class="text-xs text-gray-600 dark:text-gray-400 font-semibold">Comisiones</p>
                <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white mt-1" id="totalComisiones">0</p>
            </div>
            <div class="bg-white dark:bg-gray-700/50 rounded-xl p-3 border border-gray-200 dark:border-gray-600 hover:shadow-lg transition-all duration-300">
                <p class="text-xs text-gray-600 dark:text-gray-400 font-semibold">Mayor</p>
                <p class="text-lg sm:text-2xl font-bold text-purple-600 dark:text-purple-400 mt-1" id="mayorCantidad">0</p>
            </div>
            <div class="bg-white dark:bg-gray-700/50 rounded-xl p-3 border border-gray-200 dark:border-gray-600 hover:shadow-lg transition-all duration-300">
                <p class="text-xs text-gray-600 dark:text-gray-400 font-semibold">Promedio</p>
                <p class="text-lg sm:text-2xl font-bold text-indigo-600 dark:text-indigo-400 mt-1" id="promedioCantidad">0</p>
            </div>
        </div>

        <!-- Gráfica -->
        <div class="w-full h-72 sm:h-80 md:h-96 flex justify-center items-center bg-white dark:bg-gray-700/30 rounded-2xl backdrop-blur-sm border border-gray-100 dark:border-gray-600/50 hover:border-purple-300 dark:hover:border-purple-500/50 transition-all duration-300 p-4">
            <canvas id="doughnutChart" class="max-w-full"></canvas>
        </div>

        <!-- Legend personalizada - Con "Ver más" -->
        <div id="legendSection" class="mt-8 sm:mt-10">
            <h3 class="text-lg sm:text-xl font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                <i class="fa-solid fa-list text-purple-500"></i> Detalle por Comisión
            </h3>
            <div id="legendContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"></div>
            <button id="verMasBtn" onclick="toggleVerMas()" class="group/btn mt-6 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-4 sm:px-6 py-2 sm:py-2.5 rounded-lg shadow-lg hover:shadow-2xl hover:scale-110 transition-all duration-300 text-xs sm:text-sm font-semibold flex items-center justify-center gap-2 mx-auto">
                <i class="fa-solid fa-chevron-down" id="chevronIcon" style="transition: transform 0.3s ease;"></i>
                <span id="verMasText">Ver más comisiones</span>
            </button>
        </div>

        <!-- Mensaje sin datos -->
        <div id="noDataSection" class="mt-8 sm:mt-10 hidden">
            <div class="bg-gradient-to-r from-amber-50 to-orange-50 dark:from-gray-700/50 dark:to-gray-600/50 border-2 border-amber-200 dark:border-amber-700/50 rounded-2xl p-6 sm:p-8 flex items-center gap-4">
                <div class="flex-shrink-0">
                    <i class="fa-solid fa-inbox text-4xl text-amber-500"></i>
                </div>
                <div>
                    <h3 class="text-lg sm:text-xl font-bold text-amber-900 dark:text-amber-100">Sin datos registrados</h3>
                    <p class="text-sm sm:text-base text-amber-700 dark:text-amber-200 mt-1">No hay comisiones con concejales asignados en este momento.</p>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
window.doughnutChartInstance = null;
window.mostrandoTodos = false;
window.totalComisiones = 0;

function downloadPNG(chartId){ 
    const chartCanvas = document.getElementById(chartId);
    if(!chartCanvas) return;

    const tempCanvas = document.createElement('canvas');
    tempCanvas.width = 1200;
    tempCanvas.height = 900;
    const ctx = tempCanvas.getContext('2d');

    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, tempCanvas.width, tempCanvas.height);

    ctx.fillStyle = '#1f2937';
    ctx.font = 'bold 36px Arial';
    ctx.fillText('Reporte - Distribución por Comisiones', 40, 50);

    ctx.fillStyle = '#6b7280';
    ctx.font = '16px Arial';
    ctx.fillText('Concejales Activos Registrados por Comisión', 40, 80);

    ctx.strokeStyle = '#e5e7eb';
    ctx.lineWidth = 2;
    ctx.beginPath();
    ctx.moveTo(40, 95);
    ctx.lineTo(1160, 95);
    ctx.stroke();

    const total = document.getElementById('totalConcejales').textContent;
    const comisiones = document.getElementById('totalComisiones').textContent;
    const mayor = document.getElementById('mayorCantidad').textContent;
    const promedio = document.getElementById('promedioCantidad').textContent;

    const stats = [
        { label: 'Total Concejales', value: total, color: '#3b82f6' },
        { label: 'Total Comisiones', value: comisiones, color: '#8b5cf6' },
        { label: 'Mayor Cantidad', value: mayor, color: '#ec4899' },
        { label: 'Promedio', value: promedio, color: '#f59e0b' }
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

    const originalImage = chartCanvas.toDataURL('image/png');
    const img = new Image();
    img.onload = function() {
        ctx.drawImage(img, 50, 220, 1100, 550);

        ctx.fillStyle = '#9ca3af';
        ctx.font = '12px Arial';
        ctx.fillText('Generado: ' + new Date().toLocaleDateString('es-ES') + ' ' + new Date().toLocaleTimeString('es-ES'), 40, 880);

        tempCanvas.toBlob(blob => {
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'distribucion_comisiones_' + new Date().getTime() + '.png';
            a.click();
            URL.revokeObjectURL(url);
        });
    };
    img.src = originalImage;
}

function actualizarEstadisticas(datos, labels) {
    // Validar si son datos reales o placeholders
    const sonDatosReales = labels && labels.length > 0 && 
                          !(labels.length === 1 && (labels[0] === 'Sin datos' || labels[0] === 'Sin datos disponibles'));
    
    if (!sonDatosReales || !datos || datos.length === 0) {
        // Si no hay datos reales, mostrar ceros
        document.getElementById('totalConcejales').textContent = '0';
        document.getElementById('totalComisiones').textContent = '0';
        document.getElementById('mayorCantidad').textContent = '0';
        document.getElementById('promedioCantidad').textContent = '0';
    } else {
        // Si hay datos reales, calcular correctamente
        const total = datos.reduce((a, b) => a + b, 0);
        const mayor = Math.max(...datos);
        const promedio = (total / datos.length).toFixed(1);
        const comisiones = datos.length;

        document.getElementById('totalConcejales').textContent = total;
        document.getElementById('totalComisiones').textContent = comisiones;
        document.getElementById('mayorCantidad').textContent = mayor;
        document.getElementById('promedioCantidad').textContent = promedio;
    }
}

function generarLegendPersonalizada(labels, datos, colores) {
    const container = document.getElementById('legendContainer');
    container.innerHTML = '';
    
    window.totalComisiones = labels.length;
    window.mostrandoTodos = false;

    labels.forEach((label, index) => {
        const item = document.createElement('div');
        item.className = 'bg-gradient-to-r from-gray-50 to-blue-50 dark:from-gray-700/50 dark:to-gray-600/50 p-4 rounded-xl border-2 hover:border-purple-400 dark:hover:border-purple-500 transition-all duration-300 cursor-pointer group/item hover:shadow-lg transform hover:scale-105 legend-item';
        item.style.borderColor = colores[index].replace('0.8', '0.5');
        item.setAttribute('data-index', index);
        
        // Ocultar items después del 3ro si hay más de 3
        if (labels.length > 3 && index >= 3) {
            item.style.display = 'none';
            item.classList.add('hidden-item');
        }
        
        item.innerHTML = `
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 pt-1">
                    <div class="w-5 h-5 rounded-full flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, ${colores[index]}, ${colores[index].replace('0.8', '1')})"></div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm sm:text-base lg:text-lg font-bold text-gray-900 dark:text-white truncate hover:text-clip">${label}</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                        <i class="fa-solid fa-users text-purple-500 mr-1"></i>
                        <span class="font-bold text-purple-600 dark:text-purple-400 text-base">${datos[index]}</span>
                        <span class="ml-2">concejal${datos[index] !== 1 ? 'es' : ''}</span>
                    </p>
                </div>
            </div>
        `;
        container.appendChild(item);
    });
    
    // Mostrar/ocultar botón "Ver más"
    const verMasBtn = document.getElementById('verMasBtn');
    if (labels.length > 3) {
        verMasBtn.style.display = 'flex';
    } else {
        verMasBtn.style.display = 'none';
    }
}

function toggleVerMas() {
    window.mostrandoTodos = !window.mostrandoTodos;
    const items = document.querySelectorAll('.legend-item.hidden-item');
    const btn = document.getElementById('verMasBtn');
    const text = document.getElementById('verMasText');
    const chevron = document.getElementById('chevronIcon');
    
    items.forEach(item => {
        item.style.display = window.mostrandoTodos ? 'block' : 'none';
    });
    
    if (window.mostrandoTodos) {
        text.textContent = 'Ver menos comisiones';
        chevron.style.transform = 'rotate(180deg)';
    } else {
        text.textContent = 'Ver más comisiones';
        chevron.style.transform = 'rotate(0deg)';
    }
}

function mostrarOcultarSecciones(labels) {
    const legendSection = document.getElementById('legendSection');
    const noDataSection = document.getElementById('noDataSection');
    const statsBar = document.querySelector('.grid.grid-cols-2');
    const chartContainer = document.querySelector('.w-full.h-72');

    if (labels && labels.length > 0) {
        // Mostrar todo si hay datos
        statsBar.classList.remove('hidden');
        chartContainer.classList.remove('hidden');
        legendSection.classList.remove('hidden');
        noDataSection.classList.add('hidden');
    } else {
        // Ocultar todo si no hay datos
        statsBar.classList.add('hidden');
        chartContainer.classList.add('hidden');
        legendSection.classList.add('hidden');
        noDataSection.classList.remove('hidden');
    }
}

function initDoughnutChart(labels = [], datos = [], colores = []) {
    const doughnutCtx = document.getElementById('doughnutChart');
    if (!doughnutCtx) return;

    // Mostrar u ocultar secciones
    mostrarOcultarSecciones(labels);

    if (window.doughnutChartInstance) {
        window.doughnutChartInstance.destroy();  
    }

    const ctx = doughnutCtx.getContext('2d');
    
    const etiquetas = labels && labels.length > 0 ? labels : ['Sin datos'];
    const valores = datos && datos.length > 0 ? datos : [1];
    const coloresGrafica = colores && colores.length > 0 ? colores : ['rgba(229, 231, 235, 0.8)'];

    const isMobile = window.innerWidth < 768;
    const fontSize = isMobile ? 10 : 13;

    window.doughnutChartInstance = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: etiquetas,
            datasets: [{
                label: 'Concejales',
                data: valores,
                backgroundColor: coloresGrafica,
                borderColor: coloresGrafica.map(color => color.replace('0.8', '1')),
                borderWidth: 3,
                hoverOffset: 10,
                hoverBorderWidth: 4,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { 
                duration: 2500, 
                easing: 'easeInOutQuart',
                animateRotate: true,
                animateScale: true,
                delay: (ctx) => {
                    let delay = 0;
                    if (ctx.type === 'data') {
                        delay = ctx.dataIndex * 50 + ctx.datasetIndex * 100;
                    }
                    return delay;
                },
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.9)',
                    padding: 14,
                    cornerRadius: 10,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    callbacks: {
                        title: function(context) {
                            return context[0].label;
                        },
                        label: function(context) {
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `👤 Concejales: ${value} (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '60%',
        }
    });

    // Actualizar estadísticas y leyenda
    actualizarEstadisticas(valores, etiquetas);
    generarLegendPersonalizada(etiquetas, valores, coloresGrafica);
}

// Pasar los arrays de PHP a variables de JS
window.comisionesLabelsGlobal = @json($comisionesLabels ?? []);
window.comisionesDataGlobal = @json($comisionesData ?? []);
window.comisionesColoresGlobal = @json($comisionesColores ?? []);

// Inicializar
document.addEventListener('DOMContentLoaded', function() {
    initDoughnutChart(window.comisionesLabelsGlobal, window.comisionesDataGlobal, window.comisionesColoresGlobal);
});

document.addEventListener('livewire:navigated', function() {
    setTimeout(() => {
        initDoughnutChart(window.comisionesLabelsGlobal, window.comisionesDataGlobal, window.comisionesColoresGlobal);
    }, 100);
});

document.addEventListener('livewire:updated', function() {
    initDoughnutChart(window.comisionesLabelsGlobal, window.comisionesDataGlobal, window.comisionesColoresGlobal);
});

// Redimensionar gráfico al cambiar ventana
window.addEventListener('resize', () => {
    if (window.doughnutChartInstance) {
        window.doughnutChartInstance.resize();
    }
});
</script>