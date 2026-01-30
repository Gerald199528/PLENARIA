@extends('web.layouts.app')

@section('title', 'Estadísticas de Participación Ciudadana - PLENARIA')

@section('before_content')
    @include('web.navegation.header')
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 py-6 sm:py-8 md:py-10 lg:py-12">
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .animate-slide-in-left {
            animation: slideInLeft 0.8s ease-out forwards;
        }

        .animate-scale-in {
            animation: scaleIn 0.6s ease-out forwards;
        }

        .card-delay-1 { animation-delay: 0.1s; }
        .card-delay-2 { animation-delay: 0.2s; }
        .card-delay-3 { animation-delay: 0.3s; }
        .card-delay-4 { animation-delay: 0.4s; }
        .card-delay-5 { animation-delay: 0.5s; }

        .chart-delay-1 { animation-delay: 0.6s; }
        .chart-delay-2 { animation-delay: 0.8s; }
        .chart-delay-3 { animation-delay: 1.0s; }

        .title-delay { animation-delay: 0s; }

        .separator {
            height: 2px;
            background: linear-gradient(to right, transparent, #3b82f6, transparent);
            margin: 1.5rem 0 sm:margin-y-8 md:margin-y-12;
        }
    </style>

    <div class="container mx-auto px-3 sm:px-4 md:px-6 lg:px-8 max-w-6xl">
        <!-- Título -->
        <div class="mb-6 sm:mb-8 md:mb-10 lg:mb-12 animate-fade-in-up title-delay">
            <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-primary mb-2 sm:mb-3 md:mb-4 animate-slide-in-left">
                <i class="fas fa-chart-pie text-blue-500 mr-1.5 sm:mr-2 md:mr-3"></i>
                Estadísticas de Participación Ciudadana
            </h1>
            <p class="text-gray-600 text-xs sm:text-sm md:text-base lg:text-lg animate-fade-in-up px-1" style="animation-delay: 0.2s;">
                Análisis completo de la participación en sesiones municipales
            </p>
        </div>

        <!-- Grid de Estadísticas Generales -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4 md:gap-6 mb-6 sm:mb-8 md:mb-10 lg:mb-12">
            <!-- Card Ciudadanos -->
            <div class="bg-white rounded-lg sm:rounded-xl md:rounded-2xl p-3 sm:p-4 md:p-6 shadow-lg border-l-4 border-blue-500 animate-fade-in-up animate-scale-in card-delay-1 hover:shadow-2xl transition-all duration-300 cursor-pointer transform hover:scale-105">
                <div class="flex items-center justify-between gap-2 sm:gap-3">
                    <div class="min-w-0">
                        <p class="text-gray-600 text-xs sm:text-sm mb-0.5 sm:mb-1">Ciudadanos Participando</p>
                        <p class="text-2xl sm:text-2xl md:text-3xl font-bold text-primary truncate">{{ $estadisticas['ciudadanos'] ?? 0 }}</p>
                    </div>
                    <div class="bg-blue-100 p-2 sm:p-2.5 md:p-4 rounded-full flex-shrink-0">
                        <i class="fas fa-users text-blue-500 text-lg sm:text-lg md:text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Card Solicitudes -->
            <div class="bg-white rounded-lg sm:rounded-xl md:rounded-2xl p-3 sm:p-4 md:p-6 shadow-lg border-l-4 border-green-500 animate-fade-in-up animate-scale-in card-delay-2 hover:shadow-2xl transition-all duration-300 cursor-pointer transform hover:scale-105">
                <div class="flex items-center justify-between gap-2 sm:gap-3">
                    <div class="min-w-0">
                        <p class="text-gray-600 text-xs sm:text-sm mb-0.5 sm:mb-1">Solicitudes Recibidas</p>
                        <p class="text-2xl sm:text-2xl md:text-3xl font-bold text-green-600 truncate">{{ $estadisticas['solicitudes'] ?? 0 }}</p>
                    </div>
                    <div class="bg-green-100 p-2 sm:p-2.5 md:p-4 rounded-full flex-shrink-0">
                        <i class="fas fa-inbox text-green-500 text-lg sm:text-lg md:text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Card Aprobadas -->
            <div class="bg-white rounded-lg sm:rounded-xl md:rounded-2xl p-3 sm:p-4 md:p-6 shadow-lg border-l-4 border-purple-500 animate-fade-in-up animate-scale-in card-delay-3 hover:shadow-2xl transition-all duration-300 cursor-pointer transform hover:scale-105">
                <div class="flex items-center justify-between gap-2 sm:gap-3">
                    <div class="min-w-0">
                        <p class="text-gray-600 text-xs sm:text-sm mb-0.5 sm:mb-1">Solicitudes Aprobadas</p>
                        <p class="text-2xl sm:text-2xl md:text-3xl font-bold text-purple-600 truncate">{{ $estadisticas['aprobadas'] ?? 0 }}</p>
                    </div>
                    <div class="bg-purple-100 p-2 sm:p-2.5 md:p-4 rounded-full flex-shrink-0">
                        <i class="fas fa-check-circle text-purple-500 text-lg sm:text-lg md:text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Card Tasa -->
            <div class="bg-white rounded-lg sm:rounded-xl md:rounded-2xl p-3 sm:p-4 md:p-6 shadow-lg border-l-4 border-orange-500 animate-fade-in-up animate-scale-in card-delay-4 hover:shadow-2xl transition-all duration-300 cursor-pointer transform hover:scale-105">
                <div class="flex items-center justify-between gap-2 sm:gap-3">
                    <div class="min-w-0">
                        <p class="text-gray-600 text-xs sm:text-sm mb-0.5 sm:mb-1">Tasa de Aprobación</p>
                        <p class="text-2xl sm:text-2xl md:text-3xl font-bold text-orange-600 truncate">{{ $estadisticas['tasa'] ?? 0 }}%</p>
                    </div>
                    <div class="bg-orange-100 p-2 sm:p-2.5 md:p-4 rounded-full flex-shrink-0">
                        <i class="fas fa-percent text-orange-500 text-lg sm:text-lg md:text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Card Atenciones Realizadas -->
            <div class="bg-white rounded-lg sm:rounded-xl md:rounded-2xl p-3 sm:p-4 md:p-6 shadow-lg border-l-4 border-red-500 animate-fade-in-up animate-scale-in card-delay-5 hover:shadow-2xl transition-all duration-300 cursor-pointer transform hover:scale-105">
                <div class="flex items-center justify-between gap-2 sm:gap-3">
                    <div class="min-w-0">
                        <p class="text-gray-600 text-xs sm:text-sm mb-0.5 sm:mb-1">Atenciones Realizadas</p>
                        <p class="text-2xl sm:text-2xl md:text-3xl font-bold text-red-600 truncate">{{ $atencionesRealizadas->count() ?? 0 }}</p>
                    </div>
                    <div class="bg-red-100 p-2 sm:p-2.5 md:p-4 rounded-full flex-shrink-0">
                        <i class="fas fa-tasks text-red-500 text-lg sm:text-lg md:text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficas -->
        @if(($estadisticas['solicitudes'] ?? 0) > 0)
            <div class="grid grid-cols-1 gap-4 sm:gap-6 md:gap-8">
                <!-- Fila 1: Dos Gráficas de Torta lado a lado -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 md:gap-8">
                    <!-- Gráfica de Torta - Solicitudes por Estado -->
                    <div class="bg-white rounded-lg sm:rounded-xl md:rounded-2xl p-4 sm:p-6 md:p-8 shadow-lg animate-fade-in-up chart-delay-1 hover:shadow-2xl transition-all duration-300">
                        <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-primary mb-3 sm:mb-4 md:mb-6 animate-slide-in-left">
                            <i class="fas fa-chart-pie mr-1.5 sm:mr-2"></i>
                            Solicitudes por Estado
                        </h3>
                        <div style="position: relative; height: 250px;" class="sm:h-72 md:h-96 animate-scale-in" style="animation-delay: 0.6s;">
                            <canvas id="chartSolicitudes"></canvas>
                        </div>
                    </div>

                    <!-- Gráfica de Torta - Participación -->
                    <div class="bg-white rounded-lg sm:rounded-xl md:rounded-2xl p-4 sm:p-6 md:p-8 shadow-lg animate-fade-in-up chart-delay-2 hover:shadow-2xl transition-all duration-300">
                        <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-primary mb-3 sm:mb-4 md:mb-6 animate-slide-in-left" style="animation-delay: 0.2s;">
                            <i class="fas fa-chart-pie mr-1.5 sm:mr-2"></i>
                            Resumen de Participación
                        </h3>
                        <div style="position: relative; height: 250px;" class="sm:h-72 md:h-96 animate-scale-in" style="animation-delay: 0.8s;">
                            <canvas id="chartParticipacion"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Separador -->
                <div class="separator"></div>

                <!-- Gráfica de Barras Vertical - Atención Ciudadana -->
                <div class="bg-white rounded-lg sm:rounded-xl md:rounded-2xl p-4 sm:p-6 md:p-8 shadow-lg animate-fade-in-up chart-delay-3 hover:shadow-2xl transition-all duration-300">
                    <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-primary mb-3 sm:mb-4 md:mb-6 animate-slide-in-left" style="animation-delay: 0.4s;">
                        <i class="fas fa-chart-bar mr-1.5 sm:mr-2"></i>
                        Estado de Atención Ciudadana
                    </h3>
                    <div style="position: relative; height: 300px; max-width: 900px; margin: 0 auto;" class="sm:h-96 md:h-[450px] animate-scale-in" style="animation-delay: 1.0s;">
                        <canvas id="chartAtencion"></canvas>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg sm:rounded-xl md:rounded-2xl p-6 sm:p-8 md:p-12 shadow-lg animate-fade-in-up chart-delay-1 text-center">
                <i class="fas fa-inbox text-gray-300 text-4xl sm:text-5xl md:text-6xl mb-3 sm:mb-4 block"></i>
                <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-600 mb-2 sm:mb-3 px-2">No hay registros en este momento</h3>
                <p class="text-gray-500 text-xs sm:text-sm md:text-lg px-2">Aún no hay solicitudes de participación ciudadana. Las gráficas aparecerán cuando haya datos disponibles.</p>
            </div>
        @endif

        <!-- Botón Volver -->
        <div class="mt-6 sm:mt-8 md:mt-10 lg:mt-12 text-center animate-fade-in-up px-2 sm:px-0" style="animation-delay: 1.2s;">
            <a href="{{ route('home') }}#participacion" class="inline-flex items-center justify-center gap-1 sm:gap-1.5 md:gap-2 bg-primary hover:bg-blue-700 text-white px-3 sm:px-4 md:px-6 lg:px-8 py-2 sm:py-2.5 md:py-3 rounded-lg font-semibold text-xs sm:text-sm md:text-base transition-all duration-300 transform hover:scale-105 md:hover:scale-110 hover:shadow-lg w-full sm:w-auto">
                <i class="fas fa-arrow-left text-xs sm:text-sm"></i>
                <span>Volver a Participación Ciudadana</span>
            </a>
        </div>
    </div>
</div>

<!-- Scripts de Chart.js desde CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
    // Gráfica 1: Solicitudes por Estado
    const ctx1 = document.getElementById('chartSolicitudes').getContext('2d');
    const aprobadas = {{ $estadisticas['aprobadas'] ?? 0 }};
    const rechazadas = Math.max(0, {{ $estadisticas['solicitudes'] ?? 0 }} - aprobadas);

    new Chart(ctx1, {
        type: 'doughnut',
        data: {
            labels: ['Aprobadas', 'Pendientes/Rechazadas'],
            datasets: [{
                label: 'Solicitudes',
                data: [aprobadas, rechazadas],
                backgroundColor: [
                    '#22c55e',
                    '#ef4444'
                ],
                borderColor: [
                    '#16a34a',
                    '#dc2626'
                ],
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: {
                            size: 16,
                            weight: 'bold',
                            family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
                        },
                        padding: 20,
                        color: '#1f2937'
                    }
                },
                tooltip: {
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 12
                    },
                    padding: 10
                }
            }
        }
    });

    // Gráfica 2: Resumen de Participación
    const ctx2 = document.getElementById('chartParticipacion').getContext('2d');
    const ciudadanos = {{ $estadisticas['ciudadanos'] ?? 0 }};
    const solicitudes = {{ $estadisticas['solicitudes'] ?? 0 }};

    new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: ['Ciudadanos', 'Solicitudes'],
            datasets: [{
                label: 'Participación',
                data: [ciudadanos, solicitudes],
                backgroundColor: [
                    '#3b82f6',
                    '#a855f7'
                ],
                borderColor: [
                    '#1d4ed8',
                    '#7c3aed'
                ],
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: {
                            size: 16,
                            weight: 'bold',
                            family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
                        },
                        padding: 20,
                        color: '#1f2937'
                    }
                },
                tooltip: {
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 12
                    },
                    padding: 12
                }
            }
        }
    });

    // Gráfica 3: Atención Ciudadana (Barras Vertical - Colores Profesionales)
    const ctx3 = document.getElementById('chartAtencion').getContext('2d');
    const acAprobadas = {{ $estadisticas['atencionCiudadana']['aprobadas'] ?? 0 }};
    const acPendientes = {{ $estadisticas['atencionCiudadana']['pendientes'] ?? 0 }};
    const acRechazadas = {{ $estadisticas['atencionCiudadana']['rechazadas'] ?? 0 }};

    new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: ['Aprobadas', 'Pendientes', 'Rechazadas'],
            datasets: [{
                label: 'Cantidad de Solicitudes',
                data: [acAprobadas, acPendientes, acRechazadas],
                backgroundColor: [
                    '#10b981',
                    '#3b82f6',
                    '#ef4444'
                ],
                borderColor: [
                    '#059669',
                    '#1d4ed8',
                    '#dc2626'
                ],
                borderWidth: 2,
                borderRadius: 8,
                hoverBackgroundColor: [
                    '#059669',
                    '#1d4ed8',
                    '#dc2626'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: {
                            size: 14,
                            weight: 'bold'
                        },
                        padding: 20,
                        color: '#1f2937'
                    }
                },
                tooltip: {
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 12
                    },
                    padding: 12
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: {
                            size: 12
                        },
                        color: '#6b7280'
                    },
                    grid: {
                        color: '#e5e7eb'
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 13,
                            weight: 'bold'
                        },
                        color: '#1f2937'
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>
@endsection

@section('backToTop')
    <button id="backToTop" class="fixed bottom-3 sm:bottom-4 md:bottom-5 lg:bottom-6 right-3 sm:right-4 md:right-5 lg:right-6 bg-primary text-white p-2 sm:p-2.5 md:p-3 rounded-full shadow-lg hover:bg-blue-800 transition-all duration-300 transform scale-0 z-50 w-10 h-10 sm:w-11 sm:h-11 md:w-12 md:h-12 flex items-center justify-center">
        <i class="fas fa-chevron-up text-xs sm:text-sm md:text-base"></i>
    </button>
@endsection
