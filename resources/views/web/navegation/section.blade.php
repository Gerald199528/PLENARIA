<!-- Hero Section Mejorado -->
<section id="inicio" class="relative min-h-screen sm:h-screen flex items-center justify-center overflow-hidden py-12 sm:py-0">
    <!-- Background con parallax -->
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-br from-primary via-secondary to-accent opacity-90"></div>
        <img src="{{ asset('storage/' . \App\Models\Setting::get('logo_background_solid')) ?? 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQLDIm0J1k4oKvzZ0OP_7PPkrW1zHRBowUflA&s' }}" 
            alt="Concejo Municipal" 
            class="w-full h-full object-cover parallax">
    </div>
    
    <!-- Partículas animadas de fondo -->
    <div class="absolute inset-0 z-10">
        <div class="particle absolute top-20 left-10 w-2 h-2 bg-white bg-opacity-30 rounded-full animate-float"></div>
        <div class="particle absolute top-40 right-20 w-3 h-3 bg-blue-200 bg-opacity-40 rounded-full animate-bounce-slow"></div>
        <div class="particle absolute bottom-32 left-1/4 w-1 h-1 bg-white bg-opacity-50 rounded-full animate-pulse-slow"></div>
    </div>
    
    <!-- Contenido principal -->
    <div class="relative z-20 text-center text-white px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto w-full">
        <div data-aos="fade-up" data-aos-duration="1000">
            <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold mb-4 sm:mb-6 leading-tight">
                <span class="block text-4xl sm:text-5xl md:text-6xl lg:text-8xl font-extrabold tracking-wider uppercase drop-shadow-lg">
                    {{ $empresa->name ?? 'PLENARIA' }}
                </span>
                <span class="block text-xl sm:text-2xl md:text-3xl lg:text-4xl font-semibold text-blue-100 mt-2 sm:mt-3">
                    {{ $empresa->description ?? 'Plataforma Digital para Concejos Municipales' }}
                </span>
            </h1>
        </div>
        
        <div data-aos="fade-up" data-aos-delay="300" data-aos-duration="1000">
            <p class="text-base sm:text-lg md:text-xl lg:text-2xl text-blue-100 mb-6 sm:mb-8 max-w-3xl mx-auto leading-relaxed px-2">
                {{ $empresa->description ?? 'Solución Digital para la Gestión de Concejos Municipales' }}
            </p>
        </div>

        <div data-aos="fade-up" data-aos-delay="600" data-aos-duration="1000" class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center items-center px-2">
            <a href="{{ route('instrumentos_legales.index') }}" class="btn-primary px-6 sm:px-8 py-3 sm:py-4 rounded-xl font-semibold text-base sm:text-lg flex items-center justify-center gap-2 shadow-lg animate-bounce-slow w-full sm:w-auto">
                <i class="fas fa-file-alt"></i>
                <span>Ver Documentos</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <!-- Estadísticas destacadas -->
        <div data-aos="fade-up" data-aos-delay="900" data-aos-duration="1000" class="mt-10 sm:mt-14 md:mt-16 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 sm:gap-6 lg:gap-8">
            <div class="text-center">
                <div class="text-2xl sm:text-3xl md:text-4xl font-bold mb-1 sm:mb-2" id="counter-laws">{{ $totalOrdenanzas ?? 0 }}</div>
                <div class="text-blue-200 text-xs sm:text-sm lg:text-base">Ordenanzas Vigentes</div>
            </div>
            <div class="text-center">
                <div class="text-2xl sm:text-3xl md:text-4xl font-bold mb-1 sm:mb-2" id="counter-sessions">{{ $totalGacetas ?? 0 }}</div>
                <div class="text-blue-200 text-xs sm:text-sm lg:text-base">Resoluciones</div>
            </div>
            <div class="text-center">
                <div class="text-2xl sm:text-3xl md:text-4xl font-bold mb-1 sm:mb-2" id="counter-citizens">{{ $totalAcuerdos ?? 0 }}</div>
                <div class="text-blue-200 text-xs sm:text-sm lg:text-base">Acuerdos Aprobados</div>
            </div>
            <div class="text-center">
                <div class="text-2xl sm:text-3xl md:text-4xl font-bold mb-1 sm:mb-2" id="suggestions-count">{{ $totalComisiones ?? 0 }}</div>
                <div class="text-blue-200 text-xs sm:text-sm lg:text-base">Comisiones</div>
            </div>
            <div class="text-center">
                <div class="text-2xl sm:text-3xl md:text-4xl font-bold mb-1 sm:mb-2" id="implemented-count">{{ $totalConcejales ?? 0 }}</div>
                <div class="text-blue-200 text-xs sm:text-sm lg:text-base">Concejales</div>
            </div>
        </div>
    </div>
    
    <!-- Indicador de scroll -->
    <div class="absolute bottom-4 sm:bottom-8 left-1/2 transform -translate-x-1/2 text-white animate-bounce hidden sm:block">
        <i class="fas fa-chevron-down text-xl sm:text-2xl"></i>
    </div>
</section>