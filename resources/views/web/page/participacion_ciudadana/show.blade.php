@extends('web.layouts.app')

@section('title', 'Estadísticas de Participación Ciudadana - PLENARIA')

@section('before_content')
    @include('web.navegation.header')
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
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

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
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

        .animate-bounce-card {
            animation: bounce 2s infinite;
        }

        .card-delay-1 { animation-delay: 0.1s; }
        .card-delay-2 { animation-delay: 0.2s; }
        .card-delay-3 { animation-delay: 0.3s; }
        .card-delay-4 { animation-delay: 0.4s; }

        .chart-delay-1 { animation-delay: 0.6s; }
        .chart-delay-2 { animation-delay: 0.8s; }

        .title-delay { animation-delay: 0s; }
    </style>

    <div class="container mx-auto px-4 max-w-6xl">
        <!-- Título -->
        <div class="mb-12 animate-fade-in-up title-delay">
            <h1 class="text-4xl lg:text-5xl font-bold text-primary mb-4 animate-slide-in-left">
                <i class="fas fa-chart-pie text-blue-500 mr-3"></i>
                Estadísticas de Participación Ciudadana
            </h1>
            <p class="text-gray-600 text-lg animate-fade-in-up" style="animation-delay: 0.2s;">
                Análisis completo de la participación en sesiones municipales
            </p>
        </div>

        <!-- Grid de Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <!-- Card Ciudadanos -->
            <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-blue-500 animate-fade-in-up animate-scale-in card-delay-1 hover:shadow-2xl transition-all duration-300 cursor-pointer transform hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Ciudadanos Participando</p>
                        <p class="text-3xl font-bold text-primary">{{ $estadisticas['ciudadanos'] ?? 0 }}</p>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-full">
                        <i class="fas fa-users text-blue-500 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Card Solicitudes -->
            <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-green-500 animate-fade-in-up animate-scale-in card-delay-2 hover:shadow-2xl transition-all duration-300 cursor-pointer transform hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Solicitudes Recibidas</p>
                        <p class="text-3xl font-bold text-green-600">{{ $estadisticas['solicitudes'] ?? 0 }}</p>
                    </div>
                    <div class="bg-green-100 p-4 rounded-full">
                        <i class="fas fa-inbox text-green-500 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Card Aprobadas -->
            <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-purple-500 animate-fade-in-up animate-scale-in card-delay-3 hover:shadow-2xl transition-all duration-300 cursor-pointer transform hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Solicitudes Aprobadas</p>
                        <p class="text-3xl font-bold text-purple-600">{{ $estadisticas['aprobadas'] ?? 0 }}</p>
                    </div>
                    <div class="bg-purple-100 p-4 rounded-full">
                        <i class="fas fa-check-circle text-purple-500 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Card Tasa -->
            <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-orange-500 animate-fade-in-up animate-scale-in card-delay-4 hover:shadow-2xl transition-all duration-300 cursor-pointer transform hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Tasa de Aprobación</p>
                        <p class="text-3xl font-bold text-orange-600">{{ $estadisticas['tasa'] ?? 0 }}%</p>
                    </div>
                    <div class="bg-orange-100 p-4 rounded-full">
                        <i class="fas fa-percent text-orange-500 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficas -->
        @if(($estadisticas['solicitudes'] ?? 0) > 0)
            <div class="grid grid-cols-1 gap-8">
                <!-- Gráfica de Torta - Solicitudes por Estado -->
                <div class="bg-white rounded-2xl p-8 shadow-lg animate-fade-in-up chart-delay-1 hover:shadow-2xl transition-all duration-300">
                    <h3 class="text-2xl font-bold text-primary mb-6 animate-slide-in-left">
                        <i class="fas fa-chart-pie mr-2"></i>
                        Solicitudes por Estado
                    </h3>
                    <div style="position: relative; height: 500px; max-width: 600px; margin: 0 auto;" class="animate-scale-in" style="animation-delay: 0.6s;">
                        <canvas id="chartSolicitudes"></canvas>
                    </div>
                </div>

                <!-- Gráfica de Torta - Participación -->
                <div class="bg-white rounded-2xl p-8 shadow-lg animate-fade-in-up chart-delay-2 hover:shadow-2xl transition-all duration-300">
                    <h3 class="text-2xl font-bold text-primary mb-6 animate-slide-in-left" style="animation-delay: 0.2s;">
                        <i class="fas fa-chart-pie mr-2"></i>
                        Resumen de Participación
                    </h3>
                    <div style="position: relative; height: 500px; max-width: 600px; margin: 0 auto;" class="animate-scale-in" style="animation-delay: 0.8s;">
                        <canvas id="chartParticipacion"></canvas>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl p-12 shadow-lg animate-fade-in-up chart-delay-1 text-center">
                <i class="fas fa-inbox text-gray-300 text-6xl mb-4 block"></i>
                <h3 class="text-2xl font-bold text-gray-600 mb-3">No hay registros en este momento</h3>
                <p class="text-gray-500 text-lg">Aún no hay solicitudes de participación ciudadana. Las gráficas aparecerán cuando haya datos disponibles.</p>
            </div>
        @endif

    <!-- Botón Volver -->
        <div class="mt-8 sm:mt-10 md:mt-12 text-center animate-fade-in-up" style="animation-delay: 1s;">
            <a href="{{ route('home') }}#participacion" class="inline-flex items-center justify-center gap-1.5 sm:gap-2 bg-primary hover:bg-blue-700 text-white px-4 sm:px-6 md:px-8 py-2 sm:py-2.5 md:py-3 rounded-lg sm:rounded-lg md:rounded-lg font-semibold text-xs sm:text-sm md:text-base transition-all duration-300 transform hover:scale-105 sm:hover:scale-110 hover:shadow-lg w-full sm:w-auto">
                <i class="fas fa-arrow-left"></i>
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
                            size: 20,
                            weight: 'bold',
                            family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
                        },
                        padding: 25,
                        color: '#1f2937'
                    }
                },
                tooltip: {
                    titleFont: {
                        size: 16,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 14
                    },
                    padding: 12
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
                            size: 20,
                            weight: 'bold',
                            family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
                        },
                        padding: 25,
                        color: '#1f2937'
                    }
                },
                tooltip: {
                    titleFont: {
                        size: 16,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 14
                    },
                    padding: 12
                }
            }
        }
    });
</script>
@endsection

@section('backToTop')
    <button id="backToTop" class="fixed bottom-6 right-6 bg-primary text-white p-3 rounded-full shadow-lg hover:bg-blue-800 transition-all duration-300 transform scale-0 z-50">
        <i class="fas fa-chevron-up"></i>
    </button>
@endsection