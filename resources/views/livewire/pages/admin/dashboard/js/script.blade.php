
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
function dashboard() {
    return {
        lat: null,
        lng: null,
        currentTimeVZ: null,
        lastConnectionVZ: null,

        init() {
            const options = { timeZone: 'America/Caracas', hour12: false, year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit', second: '2-digit' };
            this.currentTimeVZ = new Intl.DateTimeFormat('es-VE', options).format(new Date());
            this.lastConnectionVZ = new Intl.DateTimeFormat('es-VE', options).format(new Date(new Date().getTime() - 5*60000));

            if(navigator.geolocation){
                navigator.geolocation.getCurrentPosition((position)=>{
                    this.lat = position.coords.latitude;
                    this.lng = position.coords.longitude;

                    const map = L.map('map').setView([this.lat, this.lng], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
                    L.marker([this.lat, this.lng]).addTo(map).bindPopup("Tu ubicación actual").openPopup();
                });
            } else {
                alert("Geolocalización no soportada en este navegador.");
            }
        }
    }
}

// Gráfica de Línea - Mejorada
const lineCtx = document.getElementById('lineChart').getContext('2d');
const gradientLine = lineCtx.createLinearGradient(0,0,0,400);
gradientLine.addColorStop(0, 'rgba(99, 102, 241, 0.5)');
gradientLine.addColorStop(1, 'rgba(99, 102, 241, 0.0)');

const lineChart = new Chart(lineCtx, {
    type: 'line',
    data: { 
        labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto'], 
        datasets: [{ 
            label: 'Gestiones Completadas', 
            data: [120, 180, 150, 220, 200, 280, 260, 310], 
            borderColor: '#6366f1', 
            backgroundColor: gradientLine, 
            fill: true, 
            tension: 0.4, 
            pointRadius: 6, 
            pointHoverRadius: 10, 
            pointBackgroundColor: '#6366f1',
            pointBorderColor: '#fff',
            pointBorderWidth: 3,
            pointHoverBackgroundColor: '#4f46e5',
            pointHoverBorderColor: '#fff',
            pointHoverBorderWidth: 4,
        }] 
    },
    options: { 
        responsive: true, 
        maintainAspectRatio: false,
        animation: { duration: 2000, easing: 'easeInOutQuart' }, 
        plugins: { 
            legend: { 
                display: true, 
                position: 'top',
                labels: {
                    color: '#6b7280',
                    font: { size: 13, weight: '600' },
                    padding: 15,
                    usePointStyle: true,
                }
            }, 
            tooltip: { 
                enabled: true, 
                mode: 'index', 
                intersect: false,
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: '#fff',
                bodyColor: '#fff',
                padding: 12,
                cornerRadius: 8,
            } 
        }, 
        scales: { 
            y: { 
                beginAtZero: true,
                grid: { color: 'rgba(107, 114, 128, 0.1)' },
                ticks: { color: '#6b7280', stepSize: 50 } 
            },
            x: {
                grid: { display: false },
                ticks: { color: '#6b7280' }
            }
        } 
    }
});

// Gráfica de Barras - Mejorada
const barCtx = document.getElementById('barChart').getContext('2d');
const barChart = new Chart(barCtx, {
    type: 'bar',
    data: { 
        labels: ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'], 
        datasets: [{ 
            label: 'Sesiones Realizadas', 
            data: [45, 72, 58, 90, 65], 
            backgroundColor: [
                'rgba(59, 130, 246, 0.8)',
                'rgba(16, 185, 129, 0.8)',
                'rgba(251, 146, 60, 0.8)',
                'rgba(139, 92, 246, 0.8)',
                'rgba(236, 72, 153, 0.8)',
            ],
            borderColor: [
                'rgb(59, 130, 246)',
                'rgb(16, 185, 129)',
                'rgb(251, 146, 60)',
                'rgb(139, 92, 246)',
                'rgb(236, 72, 153)',
            ],
            borderWidth: 2,
            borderRadius: 12,
            hoverBackgroundColor: [
                'rgba(59, 130, 246, 1)',
                'rgba(16, 185, 129, 1)',
                'rgba(251, 146, 60, 1)',
                'rgba(139, 92, 246, 1)',
                'rgba(236, 72, 153, 1)',
            ],
        }] 
    },
    options: { 
        responsive: true, 
        maintainAspectRatio: false,
        animation: { duration: 1800, easing: 'easeInOutQuart' }, 
        plugins: { 
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                cornerRadius: 8,
            }
        }, 
        scales: { 
            y: { 
                beginAtZero: true,
                grid: { color: 'rgba(107, 114, 128, 0.1)' },
                ticks: { color: '#6b7280', stepSize: 20 }
            },
            x: { 
                grid: { display: false },
                ticks: { color: '#6b7280' }
            } 
        } 
    }
});

// Gráfica de Torta/Donut - NUEVA
const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
const doughnutChart = new Chart(doughnutCtx, {
    type: 'doughnut',
    data: {
        labels: ['Comisión de Finanzas', 'Comisión de Obras', 'Comisión de Salud', 'Comisión de Educación', 'Comisión de Deportes'],
        datasets: [{
            label: 'Miembros',
            data: [12, 19, 8, 15, 10],
            backgroundColor: [
                'rgba(239, 68, 68, 0.8)',
                'rgba(59, 130, 246, 0.8)',
                'rgba(16, 185, 129, 0.8)',
                'rgba(251, 146, 60, 0.8)',
                'rgba(139, 92, 246, 0.8)',
            ],
            borderColor: [
                'rgb(239, 68, 68)',
                'rgb(59, 130, 246)',
                'rgb(16, 185, 129)',
                'rgb(251, 146, 60)',
                'rgb(139, 92, 246)',
            ],
            borderWidth: 3,
            hoverOffset: 15,
            hoverBorderWidth: 4,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: { 
            duration: 2000, 
            easing: 'easeInOutQuart',
            animateRotate: true,
            animateScale: true,
        },
        plugins: {
            legend: {
                display: true,
                position: 'right',
                labels: {
                    color: '#6b7280',
                    font: { size: 13, weight: '500' },
                    padding: 15,
                    usePointStyle: true,
                    pointStyle: 'circle',
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                cornerRadius: 8,
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.parsed || 0;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(1);
                        return `${label}: ${value} (${percentage}%)`;
                    }
                }
            }
        },
        cutout: '65%',
    }
});

// Funciones de descarga
function downloadPNG(chartId){ 
    const chartCanvas = document.getElementById(chartId); 
    const url = chartCanvas.toDataURL("image/png"); 
    const a = document.createElement("a"); 
    a.href = url; 
    a.download = chartId+".png"; 
    a.click(); 
}

function downloadPDF(chartId){ 
    const chartCanvas = document.getElementById(chartId); 
    const pdf = new jsPDF('landscape'); 
    pdf.text("Reporte - "+chartId, 10, 10); 
    pdf.addImage(chartCanvas.toDataURL("image/png"), 'PNG', 15, 20, 260, 120); 
    pdf.save(chartId+".pdf"); 
}

function resetCharts(){ 
    lineChart.reset(); 
    barChart.reset(); 
    doughnutChart.reset();
    setTimeout(() => {
        lineChart.update();
        barChart.update();
        doughnutChart.update();
    }, 100);
}

document.addEventListener('alpine:init', () => { Alpine.data('dashboard', dashboard) });
</script>