<section id="documentos" class="py-12 md:py-16 lg:py-20 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Encabezado -->
        <div class="text-center mb-12 md:mb-16" data-aos="fade-up">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-primary mb-4">Documentos Legales</h2>
            <div class="w-20 sm:w-24 h-1 bg-accent mx-auto mb-4 md:mb-6"></div>
            <p class="text-gray-600 text-base sm:text-lg max-w-3xl mx-auto">
                Acceda a la documentación oficial del municipio con nuestro sistema de búsqueda avanzada
            </p>
        </div>      
        @include('web.page.instrumentos_legales.form.form')
        <!-- Contador de resultados -->
        @if(request()->hasAny(['tipo', 'anio', 'search']))
        <div class="max-w-4xl mx-auto mb-6 md:mb-8" data-aos="fade-up">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 sm:p-4">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4">
                    <div class="flex items-start sm:items-center gap-2 text-xs sm:text-sm">
                        <i class="fas fa-info-circle text-blue-600 text-lg flex-shrink-0 mt-0.5 sm:mt-0"></i>
                        <span class="text-blue-800 font-medium">
                            <strong>{{ $documentos->count() }} documento(s) encontrado(s)</strong>
                            @if(request('tipo'))
                                de tipo "<strong>{{ collect($tipos)->firstWhere('value', request('tipo'))['label'] ?? 'Desconocido' }}</strong>"
                            @endif
                            @if(request('anio'))
                                del año <strong>{{ request('anio') }}</strong>
                            @endif
                            @if(request('search'))
                                con búsqueda "<strong>{{ request('search') }}</strong>"
                            @endif
                        </span>
                    </div>
                    <a href="{{ route('instrumentos_legales.index') }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1 text-xs sm:text-sm whitespace-nowrap">
                        <i class="fas fa-times"></i>
                        <span>Limpiar</span>
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Grid de Documentos -->
        @if($documentos->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
            @foreach($documentos as $doc)
            <div class="document-card p-4 sm:p-6 rounded-xl sm:rounded-2xl bg-white shadow-lg hover:shadow-xl transition-all" data-aos="fade-up">
                
                <!-- Badges de tipo y categoría -->
                <div class="flex flex-col sm:flex-row items-start justify-between gap-2 mb-4">
                    <div class="
                        @if($doc->color_badge === 'blue') bg-blue-100 text-blue-600
                        @elseif($doc->color_badge === 'purple') bg-purple-100 text-purple-600
                        @elseif($doc->color_badge === 'orange') bg-orange-100 text-orange-600
                        @elseif($doc->color_badge === 'green') bg-green-100 text-green-600
                        @else bg-gray-100 text-gray-600
                        @endif
                        px-2.5 sm:px-3 py-1 rounded-full text-xs font-bold">
                        {{ $doc->tipo_documento }}
                    </div>
                    
                    <div class="bg-green-100 text-green-600 px-2.5 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium">
                        {{ $doc->categoria->nombre ?? 'Sin categoría' }}
                    </div>
                </div>

                <!-- Fecha de aprobación -->
                <div class="text-gray-500 text-xs sm:text-sm mb-3">
                    <i class="fas fa-calendar mr-1"></i> 
                    {{ $doc->fecha_aprobacion ? $doc->fecha_aprobacion->format('d M Y') : 'Sin fecha' }}
                </div>

                <!-- Título del documento -->
                <h3 class="text-lg sm:text-xl font-bold text-primary mb-3 line-clamp-2">{{ $doc->nombre }}</h3>
                
                <!-- Observación/Descripción -->
                <p class="text-gray-600 mb-4 leading-relaxed line-clamp-3 text-sm sm:text-base">
                    {{ $doc->observacion ?? 'Sin descripción disponible' }}
                </p>

                <!-- Información del archivo -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4 text-xs sm:text-sm text-gray-500">
                    <div>
                        <i class="fas fa-file-pdf text-red-500 mr-1"></i> 
                        PDF
                        @php
                            try {
                                if(Storage::disk('public')->exists($doc->ruta)) {
                                    $size = Storage::disk('public')->size($doc->ruta);
                                    echo ' - ' . round($size/1024/1024, 2) . ' MB';
                                }
                            } catch (\Exception $e) {
                                // Archivo no encontrado
                            }
                        @endphp
                    </div>
                    <div>
                        <i class="fas fa-download mr-1"></i> {{ $doc->descargas ?? 0 }} descargas
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex flex-col sm:flex-row gap-2">
                    <a href="{{ Storage::url($doc->ruta) }}" target="_blank" 
                    class="flex-1 bg-primary text-white py-2 px-3 sm:px-4 rounded-lg text-center font-medium text-sm sm:text-base hover:bg-blue-800 transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-eye"></i><span>Ver</span>
                    </a>
                    <a href="{{ Storage::url($doc->ruta) }}" download 
                    class="flex-1 bg-gray-100 text-gray-700 py-2 px-3 sm:px-4 rounded-lg text-center font-medium text-sm sm:text-base hover:bg-gray-200 transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-download"></i><span>Descargar</span>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <!-- Mensaje cuando no hay resultados -->
        <div class="text-center py-12 md:py-16" data-aos="fade-up">
            <div class="inline-block p-6 sm:p-8 bg-white rounded-xl sm:rounded-2xl shadow-lg">
                <i class="fas fa-search text-gray-300 text-5xl sm:text-6xl mb-4"></i>
                <p class="text-gray-500 text-lg sm:text-xl font-medium mb-2">No se encontraron documentos</p>
                <p class="text-gray-400 text-sm sm:text-base mb-4">Intenta ajustar los filtros de búsqueda</p>
                <a href="{{ route('instrumentos_legales.index') }}" 
                class="inline-block btn-primary px-6 sm:px-8 py-2 sm:py-3 rounded-lg text-sm sm:text-base">
                    Ver todos los documentos
                </a>
            </div>
        </div>
        @endif

        <!-- Botón Ver Todos -->
        @if(request()->has('tipo'))
        <div class="text-center mt-10 md:mt-12" data-aos="fade-up">
            <a href="{{ route('instrumentos_legales.index', ['tipo' => request('tipo')]) }}" 
            class="btn-primary px-6 sm:px-8 py-3 sm:py-4 rounded-xl font-semibold text-base sm:text-lg inline-flex items-center gap-2">
                <span>Ver todos los {{ ucfirst(request('tipo')) }}s</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        @endif
    </div>
</section>