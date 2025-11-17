<!-- Sección de Participación Ciudadana -->
<section id="participacion" class="py-12 md:py-16 lg:py-20 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8 md:mb-12 lg:mb-16" data-aos="fade-up">
            <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-primary mb-4">Participación Ciudadana</h2>
            <div class="w-20 sm:w-24 h-1 bg-accent mx-auto mb-4 md:mb-6"></div>
            <p class="text-gray-600 text-base sm:text-lg max-w-3xl mx-auto px-2">
                Su voz importa. Participe activamente en la construcción de nuestro municipio
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8 lg:gap-12">
            <!-- Formulario de Derecho de Palabra -->
            <div data-aos="fade-right">
                <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-xl sm:rounded-2xl p-6 sm:p-8 border border-blue-100">
                    <h3 class="text-xl sm:text-2xl font-bold text-primary mb-6 flex items-center gap-2 sm:gap-3">
                        <i class="fas fa-microphone text-red-500 text-lg sm:text-xl"></i>
                        <span>Derecho de Palabra</span>
                    </h3> 
                    @include('web.page.participacion_ciudadana.form.form')
                </div>
            </div>

            <!-- Consultas Activas y Estadísticas -->
            <div data-aos="fade-left" data-aos-delay="200">
                <div class="space-y-6 md:space-y-8">
                    <!-- Sesiones Próximas -->
                    <div class="bg-white rounded-xl sm:rounded-2xl p-6 sm:p-8 shadow-lg border border-gray-100">
                        <h3 class="text-xl sm:text-2xl font-bold text-primary mb-6 flex items-center gap-2 sm:gap-3">
                            <i class="fas fa-calendar-check text-green-500 text-lg sm:text-xl"></i>
                            <span>Sesiones Próximas</span>
                        </h3>
                        <div class="space-y-3 sm:space-y-4">
                            @forelse($sesionesProximas as $sesion)
                                <div class="border border-gray-200 rounded-lg p-3 sm:p-4 hover:border-primary transition-colors">
                                    <h4 class="font-semibold text-primary mb-2 text-sm sm:text-base">{{ $sesion['titulo'] }}</h4>
                                    <p class="text-gray-600 text-xs sm:text-sm mb-3">{{ $sesion['descripcion'] }}</p>
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 sm:gap-3">
                                        <div class="text-xs sm:text-sm text-gray-500">
                                            <i class="fas fa-calendar mr-1"></i>{{ $sesion['fecha_hora'] }}
                                        </div>
                                        <span class="text-xs {{ $sesion['estado_badge']['bg'] }} {{ $sesion['estado_badge']['text'] }} px-3 py-1 rounded-full whitespace-nowrap">
                                            {{ $sesion['estado_badge']['label'] }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <i class="fas fa-calendar-times text-gray-300 text-4xl sm:text-5xl mb-4 block"></i>
                                    <h4 class="font-semibold text-gray-600 mb-2 text-base sm:text-lg">No hay sesiones próximas</h4>
                                    <p class="text-gray-500 text-xs sm:text-sm">Actualmente no hay sesiones municipales programadas.</p>
                                </div>
                            @endforelse

                            @if($sesionesProximas && count($sesionesProximas) > 0)
                                <a href="{{ route('web.page.participacion_ciudadana.index', ['tipo' => 'noticia']) }}" 
                                    class="text-white px-6 sm:px-8 py-2.5 sm:py-4 rounded-lg sm:rounded-xl font-semibold text-sm sm:text-lg flex items-center justify-center gap-2 mx-auto transition-all duration-300 transform hover:scale-105 hover:shadow-lg group w-full sm:w-auto" 
                                    style="background: var(--button-color, #4f46e5);">
                                    <span>Ver más</span>
                                    <i class="fas fa-arrow-right transform group-hover:translate-x-1 transition-transform duration-300"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Estadísticas de Participación -->
                    @if(!empty($estadisticas) && isset($estadisticas['ciudadanos']) && $estadisticas['ciudadanos'] > 0)
                        <!-- Estadísticas Reales (Con registros) -->
                        <div class="bg-gradient-to-br from-primary to-secondary rounded-xl sm:rounded-2xl p-6 sm:p-8 text-white">
                            <h3 class="text-xl sm:text-2xl font-bold mb-6 flex items-center gap-2 sm:gap-3">
                                <i class="fas fa-chart-bar text-lg sm:text-xl"></i>
                                <span>Participación Ciudadana</span>
                            </h3>
                            <div class="grid grid-cols-2 gap-4 sm:gap-6">
                                <div class="text-center">
                                    <div class="text-2xl sm:text-3xl font-bold mb-1 sm:mb-2">{{ $estadisticas['ciudadanos'] ?? 0 }}</div>
                                    <div class="text-blue-100 text-xs sm:text-sm">Ciudadanos Participando</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl sm:text-3xl font-bold mb-1 sm:mb-2">{{ $estadisticas['solicitudes'] ?? 0 }}</div>
                                    <div class="text-blue-100 text-xs sm:text-sm">Solicitudes Recibidas</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl sm:text-3xl font-bold mb-1 sm:mb-2">{{ $estadisticas['aprobadas'] ?? 0 }}</div>
                                    <div class="text-blue-100 text-xs sm:text-sm">Solicitudes Aprobadas</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl sm:text-3xl font-bold mb-1 sm:mb-2">{{ $estadisticas['tasa'] ?? 0 }}%</div>
                                    <div class="text-blue-100 text-xs sm:text-sm">Tasa de Aprobación</div>
                                </div>
                            </div>
                            
                            <!-- Botón Ver Más Estadísticas -->
                            <div class="mt-6 sm:mt-8 pt-6 sm:pt-8 border-t border-white/20">
                                <a href="{{ route('web.page.participacion_ciudadana.show') }}" class="inline-flex items-center justify-center w-full px-6 py-2.5 sm:py-3 bg-white text-primary font-semibold text-sm sm:text-base rounded-lg sm:rounded-xl hover:bg-gray-100 transition-all duration-300 hover:shadow-lg">
                                    <i class="fas fa-chart-line mr-2"></i>
                                    Ver Estadísticas Completas
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- Sin registros -->
                        <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl sm:rounded-2xl p-8 sm:p-12 text-center">
                            <i class="fas fa-inbox text-gray-400 text-5xl sm:text-6xl mb-4 block"></i>
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-600 mb-2">No hay registros</h3>
                            <p class="text-gray-500 text-sm sm:text-base">Aún no hay solicitudes de participación ciudadana. ¡Sé el primero en participar!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>