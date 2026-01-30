<header class="text-white shadow-2xl sticky top-0 z-50 gradient-primary" id="navbar">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between py-3 sm:py-4">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 sm:gap-3 flex-shrink-0 group transition-all duration-300 hover:opacity-80" data-aos="fade-right">
                <div class="bg-white bg-opacity-20 p-1.5 sm:p-2 rounded-lg sm:rounded-xl hover:bg-opacity-30 transition-all duration-300 flex-shrink-0">
                    <i class="fas fa-landmark text-lg sm:text-xl animate-float"></i>
                </div>
                <div class="min-w-0">
                    <h1 class="text-sm sm:text-lg font-bold tracking-tight leading-tight block truncate hover:text-blue-100 transition-colors">
                        {{ $empresa->name ?? 'PLENARIA' }}
                    </h1>
                    <p class="text-blue-100 text-xs font-medium truncate opacity-90">
                        {{ $empresa->razon_social ?? 'Concejo Municipal' }}
                    </p>
                </div>
            </a>

  <!-- Navegación Desktop -->
            <nav class="hidden lg:flex items-center gap-1 ml-auto mr-8" data-aos="fade-left" data-aos-delay="200">
                <a href="{{ route('home') }}" class="nav-link font-medium text-sm xl:text-base hover:text-blue-200 transition-all duration-300 flex items-center px-3 xl:px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-10 whitespace-nowrap">
                    <i class="fas fa-home mr-2"></i> Inicio
                </a>
                <a href="{{ route('nosotros.index') }}" class="nav-link font-medium text-sm xl:text-base hover:text-blue-200 transition-all duration-300 flex items-center px-3 xl:px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-10 whitespace-nowrap">
                    <i class="fas fa-info-circle mr-2"></i> Nosotros
                </a>
                <a href="{{ route('home') }}#noticias" class="nav-link font-medium text-sm xl:text-base hover:text-blue-200 transition-all duration-300 flex items-center px-3 xl:px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-10 whitespace-nowrap">
                    <i class="fas fa-newspaper mr-2"></i> Noticias
                </a>
                <a href="{{ route('home') }}#participacion" class="nav-link font-medium text-sm xl:text-base hover:text-blue-200 transition-all duration-300 flex items-center px-3 xl:px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-10 whitespace-nowrap">
                    <i class="fas fa-headset mr-2"></i> Atención Ciudadana
                </a>

                <a href="{{ route('instrumentos_legales.index') }}" class="nav-link font-medium text-sm xl:text-base hover:text-blue-200 transition-all duration-300 flex items-center px-3 xl:px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-10 whitespace-nowrap">
                    <i class="fas fa-file-alt mr-2"></i> Documentos
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

        <!-- Menú Móvil -->
        <div class="lg:hidden hidden py-3 sm:py-4 border-t border-white border-opacity-20" id="mobile-menu">
            <div class="space-y-1.5 sm:space-y-2">
                <a href="{{ route('home') }}" class="nav-link font-medium text-sm sm:text-base hover:text-blue-200 transition-all duration-300 flex items-center px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg hover:bg-white hover:bg-opacity-10 block">
                    <i class="fas fa-home mr-2"></i> Inicio
                </a>
                <a href="{{ route('nosotros.index') }}" class="nav-link font-medium text-sm sm:text-base hover:text-blue-200 transition-all duration-300 flex items-center px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg hover:bg-white hover:bg-opacity-10 block">
                    <i class="fas fa-info-circle mr-2"></i> Nosotros
                </a>
                <a href="{{ route('home') }}#noticias" class="nav-link font-medium text-sm sm:text-base hover:text-blue-200 transition-all duration-300 flex items-center px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg hover:bg-white hover:bg-opacity-10 block">
                    <i class="fas fa-newspaper mr-2"></i> Noticias
                </a>
                <a href="{{ route('home') }}#participacion" class="nav-link font-medium text-sm sm:text-base hover:text-blue-200 transition-all duration-300 flex items-center px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg hover:bg-white hover:bg-opacity-10 block">
                    <i class="fas fa-headset mr-2"></i> Atención Ciudadana
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

