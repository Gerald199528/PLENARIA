<!-- Sección de Accesos Rápidos -->
<section class="py-8 sm:py-12 md:py-16 lg:py-20 bg-white relative overflow-hidden">
    <!-- Elementos decorativos sutiles -->
    <div class="absolute top-0 left-0 w-32 h-32 sm:w-48 sm:h-48 md:w-64 md:h-64 rounded-full -translate-x-16 sm:-translate-x-24 md:-translate-x-32 -translate-y-16 sm:-translate-y-24 md:-translate-y-32" style="background: linear-gradient(135deg, rgba(29, 78, 216, 0.05), rgba(59, 130, 246, 0.05));"></div>
    <div class="absolute bottom-0 right-0 w-40 h-40 sm:w-64 sm:h-64 md:w-96 md:h-96 rounded-full translate-x-20 sm:translate-x-32 md:translate-x-48 translate-y-20 sm:translate-y-32 md:translate-y-48" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), rgba(139, 92, 246, 0.05));"></div>

    <div class="container mx-auto px-3 sm:px-4 md:px-6 lg:px-8 relative z-10">
        <!-- Encabezado -->
        <div class="text-center mb-8 sm:mb-12 md:mb-16" data-aos="fade-up">
            <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-primary mb-2 sm:mb-3 md:mb-4 px-2">Accesos Rápidos</h2>
            <div class="w-12 sm:w-16 md:w-20 h-1 rounded-full mx-auto mb-3 sm:mb-4 md:mb-6" style="background: var(--button-color);"></div>
            <p class="text-gray-600 text-xs sm:text-sm md:text-base lg:text-lg max-w-2xl mx-auto leading-relaxed px-2">
                Acceda fácilmente a la información legislativa, documentación oficial y canales de participación ciudadana
            </p>
        </div>

        <!-- Grid de accesos -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 md:gap-6 lg:gap-8">

            <!-- Información Institucional -->
            <div class="card-hover bg-white border-2 border-gray-100 p-4 sm:p-5 md:p-6 lg:p-8 rounded-xl sm:rounded-2xl transition-all duration-300 hover:border-primary hover:shadow-xl" data-aos="fade-up" data-aos-delay="100">
                <div class="text-center mb-4 sm:mb-6">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 rounded-xl sm:rounded-2xl flex items-center justify-center mx-auto mb-3 sm:mb-4" style="background: linear-gradient(135deg, #3b82f6, #06b6d4);">
                        <i class="fas fa-info-circle text-white text-lg sm:text-xl md:text-2xl"></i>
                    </div>
                </div>
                <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-primary mb-2 sm:mb-3 text-center">Información Institucional</h3>
                <p class="text-gray-600 text-xs sm:text-sm md:text-base mb-4 sm:mb-6 text-center leading-relaxed">
                    Conozca la estructura, funciones y datos relevantes de nuestro concejo municipal y sus dependencias
                </p>
                <div class="space-y-2 sm:space-y-3 mb-4 sm:mb-6">
                    <div class="flex items-center text-xs sm:text-sm text-gray-500 justify-center">
                        <i class="fas fa-building text-indigo-500 mr-2 flex-shrink-0"></i>
                        <span class="truncate">Sobre Nosotros</span>
                    </div>
                    <div class="flex items-center text-xs sm:text-sm text-gray-500 justify-center">
                        <i class="fas fa-sitemap text-cyan-500 mr-2 flex-shrink-0"></i>
                        <span class="truncate">Estructura Organizacional</span>
                    </div>
                    <div class="flex items-center text-xs sm:text-sm text-gray-500 justify-center">
                        <i class="fas fa-users-tie text-sky-500 mr-2 flex-shrink-0"></i>
                        <span class="truncate">Concejales y Comisiones</span>
                    </div>
                </div>
                <a href="{{ route('nosotros.index') }}" class="w-full text-center py-2.5 sm:py-3 rounded-lg font-semibold text-xs sm:text-sm transition-all duration-300 text-white flex items-center justify-center gap-2"
                    style="background: var(--button-color);">
                    <span>Conocer Más</span>
                    <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>

            <!-- Documentación Legal -->
            <div class="card-hover bg-white border-2 border-gray-100 p-4 sm:p-5 md:p-6 lg:p-8 rounded-xl sm:rounded-2xl transition-all duration-300 hover:border-primary hover:shadow-xl" data-aos="fade-up" data-aos-delay="200">
                <div class="text-center mb-4 sm:mb-6">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 rounded-xl sm:rounded-2xl flex items-center justify-center mx-auto mb-3 sm:mb-4" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
                        <i class="fas fa-file-alt text-white text-lg sm:text-xl md:text-2xl"></i>
                    </div>
                </div>
                <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-primary mb-2 sm:mb-3 text-center">Documentación Legal</h3>
                <p class="text-gray-600 text-xs sm:text-sm md:text-base mb-4 sm:mb-6 text-center leading-relaxed">
                    Consulte ordenanzas municipales, gacetas oficiales, resoluciones y toda la documentación legislativa vigente del concejo
                </p>
                <div class="space-y-2 sm:space-y-3 mb-4 sm:mb-6">
                    <div class="flex items-center text-xs sm:text-sm text-gray-500 justify-center">
                        <i class="fas fa-check-circle text-green-500 mr-2 flex-shrink-0"></i>
                        <span class="truncate">Ordenanzas Vigentes</span>
                    </div>
                    <div class="flex items-center text-xs sm:text-sm text-gray-500 justify-center">
                        <i class="fas fa-newspaper text-blue-500 mr-2 flex-shrink-0"></i>
                        <span class="truncate">Gacetas Oficiales</span>
                    </div>
                    <div class="flex items-center text-xs sm:text-sm text-gray-500 justify-center">
                        <i class="fas fa-file-signature text-purple-500 mr-2 flex-shrink-0"></i>
                        <span class="truncate">Resoluciones</span>
                    </div>
                </div>
                <a href="{{ route('instrumentos_legales.index') }}" class="w-full text-center py-2.5 sm:py-3 rounded-lg font-semibold text-xs sm:text-sm transition-all duration-300 text-white flex items-center justify-center gap-2"
                    style="background: var(--button-color);">
                    <span>Ver Documentos</span>
                    <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>

            <!-- Atención Ciudadana -->
            <div class="card-hover bg-white border-2 border-gray-100 p-4 sm:p-5 md:p-6 lg:p-8 rounded-xl sm:rounded-2xl transition-all duration-300 hover:border-primary hover:shadow-xl" data-aos="fade-up" data-aos-delay="300">
                <div class="text-center mb-4 sm:mb-6">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 rounded-xl sm:rounded-2xl flex items-center justify-center mx-auto mb-3 sm:mb-4" style="background: linear-gradient(135deg, var(--secondary-color), #a78bfa);">
                        <i class="fas fa-users text-white text-lg sm:text-xl md:text-2xl"></i>
                    </div>
                </div>
                <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-primary mb-2 sm:mb-3 text-center">Atención Ciudadana</h3>
                <p class="text-gray-600 text-xs sm:text-sm md:text-base mb-4 sm:mb-6 text-center leading-relaxed">
                    Solicita tu derecho de palabra en sesiones municipales o reporta situaciones que requieran atención inmediata
                </p>
                <div class="space-y-2 sm:space-y-3 mb-4 sm:mb-6">
                    <div class="flex items-center text-xs sm:text-sm text-gray-500 justify-center">
                        <i class="fas fa-calendar-alt text-orange-500 mr-2 flex-shrink-0"></i>
                        <span class="truncate">Agenda de Sesiones</span>
                    </div>
                    <div class="flex items-center text-xs sm:text-sm text-gray-500 justify-center">
                        <i class="fas fa-microphone text-red-500 mr-2 flex-shrink-0"></i>
                        <span class="truncate">Derecho de Palabra</span>
                    </div>
                    <div class="flex items-center text-xs sm:text-sm text-gray-500 justify-center">
                        <i class="fas fa-comments text-blue-500 mr-2 flex-shrink-0"></i>
                        <span class="truncate">Atención Inmediata</span>
                    </div>
                </div>
                <a href="{{ url('/#participacion') }}" class="w-full text-center py-2.5 sm:py-3 rounded-lg font-semibold text-xs sm:text-sm transition-all duration-300 text-white flex items-center justify-center gap-2"
                    style="background: var(--button-color);">
                    <span>Participar</span>
                    <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>

        </div>
    </div>
</section>
