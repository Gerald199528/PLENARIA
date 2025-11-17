<header class="text-white shadow-2xl sticky top-0 z-50 gradient-primary" id="navbar">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between py-3 sm:py-4">
            <!-- Logo - Izquierda -->
            <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0 -ml-2 sm:-ml-4" data-aos="fade-right">
                <div class="bg-white bg-opacity-20 p-1.5 sm:p-2 rounded-lg sm:rounded-xl hover:bg-opacity-30 transition-all duration-300">
                    <i class="fas fa-landmark text-xl sm:text-2xl animate-float"></i>
                </div>
                <div>
                    <a href="{{ route('home') }}" class="text-lg sm:text-2xl font-bold tracking-tight block hover:text-blue-100 transition-colors">
                        {{ $empresa->name ?? 'PLENARIA' }}
                    </a>
                    <p class="text-blue-100 text-xs sm:text-sm">
                        {{ $empresa->razon_social ?? 'Tu Concejo Municipal Digital' }}
                    </p>
                </div>
            </div>
        
            <!-- Navegación Desktop - Derecha -->
            <nav class="hidden lg:flex items-center gap-1" data-aos="fade-left" data-aos-delay="200">
                <a href="{{ route('home') }}" class="nav-link font-medium text-sm xl:text-base hover:text-blue-200 transition-all duration-300 flex items-center px-3 xl:px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-10 whitespace-nowrap">
                    <i class="fas fa-home mr-2"></i> Inicio
                </a>

                <a href="{{ route('home') }}#nosotros" class="nav-link font-medium text-sm xl:text-base hover:text-blue-200 transition-all duration-300 flex items-center px-3 xl:px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-10 whitespace-nowrap">
                    <i class="fas fa-info-circle mr-2"></i> Nosotros
                </a>

                <a href="{{ route('home') }}#noticias" class="nav-link font-medium text-sm xl:text-base hover:text-blue-200 transition-all duration-300 flex items-center px-3 xl:px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-10 whitespace-nowrap">
                    <i class="fas fa-newspaper mr-2"></i> Noticias
                </a>

                <a href="{{ route('home') }}#localidad" class="nav-link font-medium text-sm xl:text-base hover:text-blue-200 transition-all duration-300 flex items-center px-3 xl:px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-10 whitespace-nowrap">
                    <i class="fas fa-map-marker-alt mr-2"></i> Localidad
                </a>

                <a href="{{ route('home') }}#participacion" class="nav-link font-medium text-sm xl:text-base hover:text-blue-200 transition-all duration-300 flex items-center px-3 xl:px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-10 whitespace-nowrap">
                    <i class="fas fa-users mr-2"></i> Participación
                </a>

                <a href="{{ route('instrumentos_legales.index') }}" class="nav-link font-medium text-sm xl:text-base hover:text-blue-200 transition-all duration-300 flex items-center px-3 xl:px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-10 whitespace-nowrap">
                    <i class="fas fa-file-alt mr-2"></i> Documentos Legales
                </a>

                <div class="w-px h-6 bg-white bg-opacity-20 mx-1 xl:mx-2"></div>

                <a href="{{ route('login') }}" class="nav-link font-medium text-sm xl:text-base bg-white bg-opacity-20 hover:bg-opacity-30 transition-all duration-300 flex items-center px-3 xl:px-4 py-2 rounded-lg whitespace-nowrap">
                    <i class="fas fa-sign-in-alt mr-2"></i> Login
                </a>
            </nav>
       
            <!-- Botón Mobile Menu -->
            <button class="lg:hidden text-lg sm:text-xl p-2 hover:bg-white hover:bg-opacity-10 rounded-lg transition-all" id="menu-toggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        
        <!-- Menú móvil -->
        <div class="lg:hidden hidden py-3 sm:py-4 border-t border-white border-opacity-20" id="mobile-menu">
            <div class="space-y-1.5 sm:space-y-2">
                <a href="{{ route('home') }}" class="nav-link font-medium text-sm sm:text-base hover:text-blue-200 transition-all duration-300 flex items-center px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg hover:bg-white hover:bg-opacity-10 block">
                    <i class="fas fa-home mr-2"></i> Inicio
                </a>

                <a href="{{ route('home') }}#nosotros" class="nav-link font-medium text-sm sm:text-base hover:text-blue-200 transition-all duration-300 flex items-center px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg hover:bg-white hover:bg-opacity-10 block">
                    <i class="fas fa-info-circle mr-2"></i> Nosotros
                </a>

                <a href="{{ route('home') }}#noticias" class="nav-link font-medium text-sm sm:text-base hover:text-blue-200 transition-all duration-300 flex items-center px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg hover:bg-white hover:bg-opacity-10 block">
                    <i class="fas fa-newspaper mr-2"></i> Noticias
                </a>

                <a href="{{ route('home') }}#localidad" class="nav-link font-medium text-sm sm:text-base hover:text-blue-200 transition-all duration-300 flex items-center px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg hover:bg-white hover:bg-opacity-10 block">
                    <i class="fas fa-map-marker-alt mr-2"></i> Localidad
                </a>

                <a href="{{ route('home') }}#participacion" class="nav-link font-medium text-sm sm:text-base hover:text-blue-200 transition-all duration-300 flex items-center px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg hover:bg-white hover:bg-opacity-10 block">
                    <i class="fas fa-users mr-2"></i> Participación Ciudadana
                </a>

                <a href="{{ route('instrumentos_legales.index') }}" class="nav-link font-medium text-sm sm:text-base hover:text-blue-200 transition-all duration-300 flex items-center px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg hover:bg-white hover:bg-opacity-10 block">
                    <i class="fas fa-file-alt mr-2"></i> Documentos Legales
                </a>

                <div class="border-t border-white border-opacity-20 my-2 sm:my-3"></div>

                <a href="{{ route('login') }}" class="nav-link font-medium text-sm sm:text-base bg-white bg-opacity-20 hover:bg-opacity-30 transition-all duration-300 flex items-center px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg block">
                    <i class="fas fa-sign-in-alt mr-2"></i> Login
                </a>
            </div>
        </div>
    </div>
</header>