@extends('web.layouts.app')
@section('before_content')
<section class="relative w-full min-h-screen overflow-hidden bg-gradient-to-r from-blue-400 via-blue-600 to-indigo-600 animate-gradientBackground py-8 md:py-0">
    <div class="flex flex-col items-center justify-center px-4 sm:px-6 md:px-8 mx-auto min-h-screen lg:py-0 relative z-10">
        <div class="flex flex-col items-center justify-center gap-6 sm:gap-8 w-full max-w-2xl">  
                      
            <!-- Mensaje Indicativo -->
            <div class="text-center w-full px-4 sm:px-6" data-aos="fade-down" data-aos-duration="1000">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-2 sm:mb-3">
                    <i class="fa-solid fa-sign-out-alt me-2"></i>Sesión Cerrada
                </h2>
                <p class="text-sm sm:text-base md:text-lg text-blue-100 mb-6">
                    ¿Desea volver al dashboard? Presione el icono a continuación para acceder.
                </p>
            </div>
            <!-- Botones de Navegación -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-6 w-full px-4 sm:px-0" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                
                @if (Auth::check())
                    <!-- Botón Dashboard -->
                    <a href="{{ route('admin.dashboard') }}"
                        class="group flex flex-col items-center gap-3 p-4 sm:p-6 bg-white/10 backdrop-blur-md rounded-xl hover:bg-white/20 transition-all duration-300 transform hover:scale-105 hover:shadow-lg w-full sm:w-auto">
                        <div class="text-3xl sm:text-4xl text-nevora-green">
                            <i class="fa-solid fa-gauge"></i>
                        </div>
                        <span class="text-white font-semibold text-center text-sm sm:text-base">Dashboard</span>
                        <span class="text-xs text-blue-200 text-center">Volver al Panel Administrativo</span>
                    </a>
                @else
                    <!-- Si no está autenticado -->
                    <a href="{{ route('login') }}"
                        class="flex flex-col items-center gap-3 p-4 sm:p-6 bg-nevora-green hover:bg-green-600 text-white rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg w-full sm:w-auto">
                        <div class="text-3xl sm:text-4xl">
                            <i class="fa-solid fa-right-to-bracket"></i>
                        </div>
                        <span class="font-semibold text-center text-sm sm:text-base">Iniciar Sesión</span>
                    </a>
                @endif
            </div>
            <!-- Ícono decorativo -->
            <div class="mt-8 text-blue-200 opacity-50">
                <i class="fa-solid fa-arrow-up-from-bracket text-4xl sm:text-5xl"></i>
            </div>

        </div>
    </div>
</section>
@endsection