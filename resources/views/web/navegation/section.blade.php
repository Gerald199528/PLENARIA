

   <style>
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-8px);
            }
        }

        a[href*="atencion-ciudadana"]:hover {
            animation: none !important;
        }
    </style>
<!-- Hero Section Profesional -->
<section id="inicio" class="relative py-16 sm:py-20 md:py-24 flex items-center justify-center overflow-hidden">
    <!-- Background dinámico -->
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); opacity: 0.95;"></div>
        <img src="{{ asset('storage/' . \App\Models\Setting::get('logo_background_solid')) ?? 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQLDIm0J1k4oKvzZ0OP_7PPkrW1zHRBowUflA&s' }}"
            alt="Concejo Municipal"
            class="w-full h-full object-cover opacity-20">
    </div>

    <!-- Contenido principal -->
    <div class="relative z-20 text-center text-white px-3 sm:px-4 md:px-6 lg:px-8 max-w-5xl mx-auto w-full py-12 sm:py-16 md:py-20">

        <!-- Logo/Nombre principal -->
        <div data-aos="fade-down" data-aos-duration="800">
            <div class="flex items-center justify-center gap-2 sm:gap-3 mb-4 sm:mb-6">
                <div class="h-0.5 w-8 sm:w-12 rounded-full" style="background: rgba(255, 255, 255, 0.4);"></div>
                <span class="text-xs sm:text-sm font-semibold tracking-widest opacity-80 uppercase">Plataforma Municipal</span>
                <div class="h-0.5 w-8 sm:w-12 rounded-full" style="background: rgba(255, 255, 255, 0.4);"></div>
            </div>
        </div>

        <!-- Título principal -->
        <div data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-2 sm:mb-4 leading-tight">
                <span class="block">{{ $empresa->name ?? 'PLENARIA' }}</span>
            </h1>
            <div class="h-0.5 w-16 sm:w-24 mx-auto mb-4 sm:mb-6 rounded-full" style="background: linear-gradient(90deg, transparent, rgba(255,255,255,0.8), transparent);"></div>
        </div>

        <!-- Subtítulo y descripción -->
        <div data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
            <p class="text-base sm:text-lg md:text-xl font-light text-white mb-2 sm:mb-4">
                {{ $empresa->razon_social ?? 'Tu Concejo Municipal Digital' }}
            </p>
            <p class="text-xs sm:text-sm md:text-base text-blue-100 max-w-xl mx-auto leading-relaxed mb-6 sm:mb-8">
                {{ $empresa->description ?? 'Solución Digital para la Gestión de Concejos Municipales' }}
            </p>
        </div>

        <!-- CTA Buttons -->
        <div data-aos="fade-up" data-aos-duration="800" data-aos-delay="300" class="flex flex-col sm:flex-row gap-2 sm:gap-3 justify-center items-center mb-10 sm:mb-12">
            <a href="{{ route('instrumentos_legales.index') }}" class="px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg font-semibold text-xs sm:text-sm md:text-base flex items-center justify-center gap-2 transition-all duration-300 transform hover:scale-105 hover:shadow-2xl text-white w-full sm:w-auto"
                style="background: var(--button-color);">
                <i class="fas fa-file-alt text-sm"></i>
                <span>Documentos</span>
                <i class="fas fa-arrow-right text-xs"></i>
            </a>
        <br>


        <a href="{{ route('home') }}#participacion" class="px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg font-semibold text-xs sm:text-sm md:text-base flex items-center justify-center gap-2 transition-all duration-300 transform hover:scale-105 hover:shadow-2xl border-2 border-white text-white hover:bg-white hover:text-blue-600 w-full sm:w-auto group"
            style="animation: float 3s ease-in-out infinite;">
            <i class="fas fa-headset text-sm transition-transform duration-300 group-hover:scale-110"></i>
            <span>Atención Ciudadana</span>
        </a>



        </div>

        <!-- Estadísticas en grid profesional -->
        <div data-aos="fade-up" data-aos-duration="800" data-aos-delay="400">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4 md:gap-6">
                <!-- Ordenanzas -->
                <div class="text-center">
                    <div class="mb-2 flex justify-center">
                        <i class="fas fa-file-contract text-2xl sm:text-3xl md:text-4xl opacity-80"></i>
                    </div>
                    <div class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold mb-1" id="counter-laws">{{ $totalOrdenanzas ?? 0 }}</div>
                    <p class="text-blue-100 text-xs font-medium uppercase tracking-wider">Ordenanzas</p>
                </div>

                <!-- Gacetas/Resoluciones -->
                <div class="text-center">
                    <div class="mb-2 flex justify-center">
                        <i class="fas fa-newspaper text-2xl sm:text-3xl md:text-4xl opacity-80"></i>
                    </div>
                    <div class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold mb-1" id="counter-sessions">{{ $totalGacetas ?? 0 }}</div>
                    <p class="text-blue-100 text-xs font-medium uppercase tracking-wider">Gacetas</p>
                </div>

                <!-- Acuerdos -->
                <div class="text-center">
                    <div class="mb-2 flex justify-center">
                        <i class="fas fa-handshake text-2xl sm:text-3xl md:text-4xl opacity-80"></i>
                    </div>
                    <div class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold mb-1" id="counter-citizens">{{ $totalAcuerdos ?? 0 }}</div>
                    <p class="text-blue-100 text-xs font-medium uppercase tracking-wider">Acuerdos</p>
                </div>

                <!-- Comisiones -->
                <div class="text-center">
                    <div class="mb-2 flex justify-center">
                        <i class="fas fa-users text-2xl sm:text-3xl md:text-4xl opacity-80"></i>
                    </div>
                    <div class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold mb-1" id="suggestions-count">{{ $totalComisiones ?? 0 }}</div>
                    <p class="text-blue-100 text-xs font-medium uppercase tracking-wider">Comisiones</p>
                </div>

                <!-- Concejales -->
                <div class="text-center">
                    <div class="mb-2 flex justify-center">
                        <i class="fas fa-user-tie text-2xl sm:text-3xl md:text-4xl opacity-80"></i>
                    </div>
                    <div class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold mb-1" id="implemented-count">{{ $totalConcejales ?? 0 }}</div>
                    <p class="text-blue-100 text-xs font-medium uppercase tracking-wider">Concejales</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll indicator -->
    <a href="{{ route('home') }}#noticias"  class="absolute bottom-6 left-1/2 transform -translate-x-1/2 z-30 cursor-pointer group transition-all duration-300">
        <div class="flex flex-col items-center gap-2">
            <span class="text-white text-xs font-semibold uppercase tracking-widest group-hover:opacity-100 opacity-70 transition-opacity">Desplázate</span>
            <div class="group-hover:-translate-y-0 transition-transform duration-300">
                <i class="fas fa-chevron-down text-white text-2xl opacity-100 animate-bounce" style="filter: drop-shadow(0 0 8px rgba(255,255,255,0.5));"></i>
            </div>
        </div>
    </a>
</section>
