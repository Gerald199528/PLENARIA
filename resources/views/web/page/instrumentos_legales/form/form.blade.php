  <!-- Buscador -->
        <form method="GET" action="{{ route('instrumentos_legales.index') }}" id="formularioBusqueda">
            <div class="max-w-4xl mx-auto mb-8 md:mb-12" data-aos="fade-up" data-aos-delay="200">
                <div class="bg-white rounded-xl sm:rounded-2xl p-6 sm:p-8 shadow-lg border border-gray-100">
                    <h3 class="text-xl sm:text-2xl font-bold text-primary mb-6 text-center">Buscador de Documentos</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4">
                        <!-- SELECT TIPO DE DOCUMENTO -->
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Tipo de Documento</label>
                            <select name="tipo" id="selectTipo" required
                                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Seleccione un tipo</option>
                                @foreach($tipos as $tipo)
                                    <option value="{{ $tipo['value'] }}" {{ request('tipo') == $tipo['value'] ? 'selected' : '' }}>
                                        {{ $tipo['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- SELECT AÑO -->
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Año</label>
                            <select name="anio" id="selectAnio" required
                                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Seleccione un año</option>
                                @for ($i = date('Y'); $i >= 2000; $i--)
                                    <option value="{{ $i }}" {{ request('anio') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <!-- BOTONES -->
                        <div class="flex items-end gap-2">
                            <button type="submit" class="btn-primary flex-1 py-2.5 sm:py-3 rounded-lg font-medium flex items-center justify-center gap-2 text-xs sm:text-base" id="btnBuscar">
                                <i class="fas fa-search"></i>
                                <span>Buscar</span>
                            </button>
                            <button type="button" id="btnLimpiar" class="flex-1 py-2.5 sm:py-3 rounded-lg font-medium flex items-center justify-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-700 transition text-xs sm:text-base">
                                <i class="fas fa-undo"></i>
                                <span>Limpiar</span>
                            </button>
                        </div>
                    </div>

                    <!-- BÚSQUEDA POR PALABRAS CLAVE -->
                    <div class="mt-4 sm:mt-6">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Palabras Clave</label>
                        <input type="text" name="search" id="inputSearch" placeholder="Buscar por palabras clave..." value="{{ request('search') }}" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                </div>
            </div>
        </form>
