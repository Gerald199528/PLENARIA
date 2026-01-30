@extends('web.layouts.app')

@section('title', 'Participación Ciudadana - PLENARIA')

@section('before_content')
    @include('web.navegation.header')
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 py-6 md:py-10 lg:py-12">
    <div class="container mx-auto px-3 xs:px-4 sm:px-6 lg:px-8 max-w-7xl">
        <!-- Título -->
        <div class="mb-6 md:mb-8 lg:mb-10 xl:mb-12">
            <h1 class="text-2xl xs:text-3xl sm:text-4xl lg:text-5xl font-bold text-primary mb-2 xs:mb-3 md:mb-4 flex items-center gap-1.5 xs:gap-2 md:gap-3 flex-wrap">
                <i class="fas fa-calendar-check text-green-500 text-xl xs:text-2xl md:text-3xl flex-shrink-0"></i>
                <span>Todas las Sesiones Municipales</span>
            </h1>
            <p class="text-gray-600 text-sm xs:text-base sm:text-lg md:text-lg pr-2">
                Consulta el calendario completo de sesiones municipales programadas
            </p>
        </div>

        <!-- Sesiones Grid - Responsive -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 xs:gap-4 sm:gap-5 md:gap-6 lg:gap-7 xl:gap-8">
            @forelse($sesiones as $sesion)
                <div class="bg-white rounded-lg xs:rounded-xl sm:rounded-xl md:rounded-2xl p-4 xs:p-5 sm:p-6 md:p-7 lg:p-8 shadow-md hover:shadow-lg transition-all duration-300 flex flex-col h-full border border-gray-100">
                    <!-- Badge de estado -->
                    <div class="mb-3 xs:mb-4">
                        @php
                            $estadoBadge = match($sesion->estado) {
                                'proxima' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Próxima'],
                                'abierta' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Abierta'],
                                'cerrada' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Cerrada'],
                                default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($sesion->estado)]
                            };
                        @endphp
                        <span class="text-xs xs:text-xs sm:text-sm font-semibold {{ $estadoBadge['bg'] }} {{ $estadoBadge['text'] }} px-2.5 xs:px-3 py-1 rounded-full inline-block">
                            {{ $estadoBadge['label'] }}
                        </span>
                    </div>

                    <!-- Encabezado -->
                    <h3 class="text-lg xs:text-xl sm:text-2xl font-bold text-primary mb-2.5 xs:mb-3 sm:mb-4 line-clamp-2">
                        {{ $sesion->titulo }}
                    </h3>

                    @if($sesion->categoria)
                        <span class="text-xs xs:text-xs sm:text-sm bg-blue-50 text-blue-700 px-2 xs:px-2.5 py-1 rounded mb-3 xs:mb-3 inline-block w-fit">
                            <i class="fas fa-tag mr-1"></i>{{ $sesion->categoria->nombre }}
                        </span>
                    @endif

                    <!-- Descripción -->
                    <p class="text-gray-600 mb-4 xs:mb-5 sm:mb-6 leading-relaxed flex-grow text-sm xs:text-base sm:text-base md:text-base line-clamp-3">
                        {{ $sesion->descripcion }}
                    </p>

                    <!-- Información de fecha y hora -->
                    <div class="space-y-2.5 xs:space-y-3 sm:space-y-3.5 md:space-y-4 pt-4 xs:pt-5 sm:pt-6 border-t border-gray-200">
                        <div class="flex items-center text-gray-700 gap-2 xs:gap-2.5 sm:gap-3">
                            <i class="fas fa-calendar-alt text-primary text-base xs:text-lg sm:text-lg md:text-xl flex-shrink-0"></i>
                            <span class="text-xs xs:text-sm sm:text-base font-semibold truncate xs:truncate-none">
                                {{ $sesion->fecha_hora->timezone('America/Caracas')->translatedFormat('d \d\e F \d\e Y') }}
                            </span>
                        </div>

                        <div class="flex items-center text-gray-700 gap-2 xs:gap-2.5 sm:gap-3">
                            <i class="fas fa-clock text-primary text-base xs:text-lg sm:text-lg md:text-xl flex-shrink-0"></i>
                            <span class="text-xs xs:text-sm sm:text-base font-semibold">
                                {{ $sesion->fecha_hora->timezone('America/Caracas')->format('h:i A') }}
                            </span>
                        </div>
                    </div>

                    <!-- Botón de acción -->
                    <div class="mt-4 xs:mt-5 sm:mt-6">
                        @if($sesion->estado === 'proxima' || $sesion->estado === 'abierta')
                            <a href="{{ route('home') }}#participacion" class="w-full inline-flex items-center justify-center
                             bg-green-600 hover:bg-green-700 text-white px-3 xs:px-4 sm:px-5 md:px-6 py-2 xs:py-2.5 sm:py-2.5 md:py-3
                             rounded-lg font-semibold transition-colors duration-300 text-xs xs:text-sm sm:text-base gap-1.5 xs:gap-2">
                                <i class="fas fa-microphone"></i>
                                <span>Participar Ahora</span>
                            </a>
                        @else
                            <button disabled class="w-full inline-flex items-center justify-center bg-gray-400 text-white px-3 xs:px-4 sm:px-5 md:px-6 py-2 xs:py-2.5 sm:py-2.5 md:py-3 rounded-lg font-semibold cursor-not-allowed text-xs xs:text-sm sm:text-base opacity-60 gap-1.5 xs:gap-2">
                                <i class="fas fa-lock"></i>
                                <span>Sesión {{ ucfirst($sesion->estado) }}</span>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <!-- Estado vacío -->
                <div class="bg-white rounded-lg xs:rounded-xl sm:rounded-xl md:rounded-2xl p-6 xs:p-8 sm:p-10 md:p-12 shadow-md border border-gray-100 text-center col-span-1 md:col-span-2">
                    <i class="fas fa-calendar-times text-gray-300 text-4xl xs:text-5xl sm:text-5xl md:text-6xl mb-3 xs:mb-4 sm:mb-5 md:mb-6 block"></i>
                    <h3 class="text-lg xs:text-xl sm:text-2xl font-bold text-gray-600 mb-2 xs:mb-2 sm:mb-3">
                        No hay sesiones disponibles
                    </h3>
                    <p class="text-gray-500 text-sm xs:text-base sm:text-lg px-2">
                        Actualmente no hay sesiones municipales programadas. Por favor, intenta más tarde.
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('backToTop')
    <button id="backToTop" class="fixed bottom-3 xs:bottom-4 sm:bottom-5 md:bottom-6 right-3 xs:right-4 sm:right-5 md:right-6 bg-primary text-white p-2 xs:p-2.5 sm:p-3 rounded-full shadow-lg hover:bg-blue-800 transition-all duration-300 transform scale-0 z-50 text-base xs:text-lg sm:text-lg md:text-xl">
        <i class="fas fa-chevron-up"></i>
    </button>
@endsection
