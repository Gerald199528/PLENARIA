@extends('web.layouts.app')

@section('title', 'Participación Ciudadana - PLENARIA')

@section('before_content')
    @include('web.navegation.header')
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 py-8 md:py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
        <!-- Título -->
        <div class="mb-8 md:mb-12">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-primary mb-3 md:mb-4 flex items-center gap-2 md:gap-3">
                <i class="fas fa-calendar-check text-green-500 text-2xl md:text-3xl"></i>
                <span>Todas las Sesiones Municipales</span>
            </h1>
            <p class="text-gray-600 text-base md:text-lg">
                Consulta el calendario completo de sesiones municipales programadas
            </p>
        </div>

        <!-- Sesiones Grid - Responsive -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-8">
            @forelse($sesiones as $sesion)
                <div class="bg-white rounded-xl sm:rounded-2xl p-6 sm:p-8 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 flex flex-col h-full">
                    <!-- Badge de estado -->
                    <div class="mb-4">
                        @php
                            $estadoBadge = match($sesion->estado) {
                                'proxima' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Próxima'],
                                'abierta' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Abierta'],
                                'cerrada' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Cerrada'],
                                default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($sesion->estado)]
                            };
                        @endphp
                        <span class="text-xs sm:text-sm font-semibold {{ $estadoBadge['bg'] }} {{ $estadoBadge['text'] }} px-3 py-1 rounded-full inline-block">
                            {{ $estadoBadge['label'] }}
                        </span>
                    </div>

                    <!-- Encabezado -->
                    <h3 class="text-xl sm:text-2xl font-bold text-primary mb-3 sm:mb-4 line-clamp-2">
                        {{ $sesion->titulo }}
                    </h3>
                    
                    @if($sesion->categoria)
                        <span class="text-xs sm:text-sm bg-blue-50 text-blue-700 px-2 sm:px-3 py-1 rounded mb-3 inline-block w-fit">
                            <i class="fas fa-tag mr-1"></i>{{ $sesion->categoria->nombre }}
                        </span>
                    @endif

                    <!-- Descripción -->
                    <p class="text-gray-600 mb-6 leading-relaxed flex-grow text-base sm:text-lg line-clamp-3">
                        {{ $sesion->descripcion }}
                    </p>

                    <!-- Información de fecha y hora -->
                    <div class="space-y-3 sm:space-y-4 pt-6 border-t border-gray-200">
                        <div class="flex items-center text-gray-700 gap-2 sm:gap-3">
                            <i class="fas fa-calendar-alt text-primary text-lg sm:text-xl flex-shrink-0"></i>
                            <span class="text-sm sm:text-base font-semibold">
                                {{ $sesion->fecha_hora->timezone('America/Caracas')->format('d \d\e F \d\e Y') }}
                            </span>
                        </div>

                        <div class="flex items-center text-gray-700 gap-2 sm:gap-3">
                            <i class="fas fa-clock text-primary text-lg sm:text-xl flex-shrink-0"></i>
                            <span class="text-sm sm:text-base font-semibold">
                                {{ $sesion->fecha_hora->timezone('America/Caracas')->format('H:i A') }}
                            </span>
                        </div>
                    </div>

                    <!-- Botón de acción -->
                    <div class="mt-6">
                        @if($sesion->estado === 'proxima' || $sesion->estado === 'abierta')
                            <a href="{{ route('home') }}#participacion" class="w-full inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg font-semibold transition-colors duration-300 text-sm sm:text-base gap-2" onclick="localStorage.setItem('sesion_id', {{ $sesion->id }})">
                                <i class="fas fa-microphone"></i>
                                <span>Participar Ahora</span>
                            </a>
                        @else
                            <button disabled class="w-full inline-flex items-center justify-center bg-gray-400 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg font-semibold cursor-not-allowed text-sm sm:text-base opacity-60 gap-2">
                                <i class="fas fa-lock"></i>
                                <span>Sesión {{ ucfirst($sesion->estado) }}</span>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <!-- Estado vacío -->
                <div class="bg-white rounded-xl sm:rounded-2xl p-8 sm:p-12 shadow-lg border border-gray-100 text-center col-span-1 sm:col-span-2">
                    <i class="fas fa-calendar-times text-gray-300 text-5xl sm:text-6xl mb-4 sm:mb-6 block"></i>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-600 mb-2 sm:mb-3">
                        No hay sesiones disponibles
                    </h3>
                    <p class="text-gray-500 text-base sm:text-lg">
                        Actualmente no hay sesiones municipales programadas. Por favor, intenta más tarde.
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('backToTop')
    <button id="backToTop" class="fixed bottom-4 sm:bottom-6 right-4 sm:right-6 bg-primary text-white p-2.5 sm:p-3 rounded-full shadow-lg hover:bg-blue-800 transition-all duration-300 transform scale-0 z-50 text-lg sm:text-xl">
        <i class="fas fa-chevron-up"></i>
    </button>
@endsection