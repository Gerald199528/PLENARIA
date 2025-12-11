<div class="w-full">
    <div class="w-full min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900 p-4 py-6 sm:py-8">
        <div class="w-full max-w-7xl bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-500">

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-700 to-indigo-900 text-white gap-4">
                <div class="flex items-center gap-2 sm:gap-3">
                    <i class="fa-solid fa-newspaper text-2xl sm:text-3xl text-white animate-bounce"></i>
                    <h2 class="text-lg sm:text-2xl md:text-3xl lg:text-4xl font-extrabold tracking-wide">
                        @if($mode === 'edit')
                            Editar Noticia
                        @else
                            Crear Nueva Noticia
                        @endif
                    </h2>
                </div>
                <x-button slate wire:click="cancel" spinner="cancel" label="Volver" icon="arrow-left"
                    class="inline-flex items-center gap-1 sm:gap-2 px-3 sm:px-4 md:px-6 py-1.5 sm:py-2 md:py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-lg sm:rounded-xl shadow-lg transition-all duration-300 hover:scale-105 text-xs sm:text-sm md:text-base w-full sm:w-auto justify-center" />    
            </div>

            {{-- Información --}}
            <div class="p-3 sm:p-4 bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 dark:border-blue-400 rounded-b-3xl">
                <p class="text-blue-700 dark:text-blue-200 text-xs sm:text-sm md:text-base flex items-start gap-2">
                    <i class="fa-solid fa-circle-info text-blue-500 dark:text-blue-300 flex-shrink-0 mt-0.5"></i>
                    <span>Completa la información de la noticia. Los campos con * son obligatorios.</span>
                </p>
            </div>

            {{-- Formulario --}}
            <form wire:submit.prevent="save" enctype="multipart/form-data" class="p-4 sm:p-6 md:p-8 space-y-6 sm:space-y-8">

                {{-- Fila 1: Título --}}
                <div class="grid grid-cols-1 gap-4 sm:gap-6">
                    <div class="group">
                        <label class="font-semibold text-sm sm:text-base text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-heading w-4 h-4"></i> Título *
                        </label>
                        <input type="text" wire:model.defer="titulo" placeholder="Ej: Noticia importante de la empresa"
                            class="block w-full p-2 sm:p-4 text-sm sm:text-base rounded-2xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg focus:ring-2 focus:ring-blue-500" />
                    </div>
                </div>

                {{-- Fila 2: Imagen y Tipo --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    {{-- Imagen --}}
                    <div class="relative group">
                        <label class="block text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-image w-4 h-4"></i> Imagen destacada *
                        </label>
                        <div class="flex flex-col gap-3">
                            <input type="file" wire:model="imagen" accept="image/*"
                                class="w-full p-2 sm:p-4 text-xs sm:text-sm rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 cursor-pointer hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all duration-300 file:mr-2 sm:file:mr-4 file:py-1 sm:file:py-2 file:px-2 sm:file:px-4 file:rounded-lg sm:file:rounded-xl file:border-0 file:text-xs sm:file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700" />
                        </div>

                        {{-- Indicador de carga --}}
                        <div wire:loading wire:target="imagen" class="flex items-center justify-center gap-2 sm:gap-3 mt-3 text-blue-600 text-sm sm:text-base">
                            <div class="flex gap-1.5">
                                <div class="w-2 h-2 sm:w-3 sm:h-3 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0s;"></div>
                                <div class="w-2 h-2 sm:w-3 sm:h-3 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
                                <div class="w-2 h-2 sm:w-3 sm:h-3 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.4s;"></div>
                            </div>
                            <span class="text-xs sm:text-sm font-medium">Subiendo imagen...</span>
                        </div>

                        {{-- Confirmación de archivo cargado --}}
                        @if ($imagen)
                            <div class="mt-4 p-3 sm:p-5 bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-800 dark:to-emerald-800 border-2 border-green-400 dark:border-green-600 rounded-2xl shadow-md animate-pulse">
                                <div class="flex items-center gap-2 sm:gap-4">
                                    <img src="{{ $imagen->temporaryUrl() }}" class="w-8 h-8 sm:w-12 sm:h-12 object-cover rounded-lg flex-shrink-0">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-green-900 dark:text-green-100 font-bold text-xs sm:text-sm">✓ Imagen cargada</p>
                                        <p class="text-green-800 dark:text-green-200 text-xs truncate">{{ $imagen->getClientOriginalName() }}</p>
                                    </div>
                                    <button type="button" wire:click="$set('imagen', null)" class="text-green-700 dark:text-green-300 hover:text-red-600 dark:hover:text-red-400 transition-colors flex-shrink-0">
                                        <i class="fa-solid fa-xmark w-4 h-4 sm:w-6 sm:h-6"></i>
                                    </button>
                                </div>
                            </div>
                        {{-- Imagen Actual --}}
                        @elseif($imagen_actual)
                            <div class="mt-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2 p-3 sm:p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                                    <img src="{{ asset('storage/' . $imagen_actual) }}" class="w-8 h-8 sm:w-12 sm:h-12 object-cover rounded-lg flex-shrink-0 shadow-sm">
                                    <div class="min-w-0">
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Imagen actual</p>
                                        <p class="text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-200 truncate">Imagen cargada</p>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $imagen_actual) }}" target="_blank" 
                                    class="inline-flex items-center justify-center gap-1 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-xs sm:text-sm font-semibold transition-colors whitespace-nowrap">
                                    <i class="fa-solid fa-eye"></i> <span class="hidden sm:inline">Ver</span>
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Tipo --}}
                    <div class="group">
                        <label class="font-semibold text-sm sm:text-base text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-list w-4 h-4"></i> Tipo de publicación *
                        </label>
                        <select wire:model.live="tipo"
                            class="block w-full p-2 sm:p-4 text-sm sm:text-base rounded-2xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccione un tipo</option>
                            <option value="noticia">Noticia</option>
                            <option value="flyer">Flyer</option>
                            <option value="video">Video</option>
                            <option value="cronica">Crónica</option>
                        </select>
                    </div>
                </div>

                {{-- Fila 3: Fecha de Publicación y Destacada --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <div class="group">
                        <label class="font-semibold text-sm sm:text-base text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-calendar w-4 h-4"></i> Fecha de publicación *
                        </label>
                        <input type="date" wire:model.defer="fecha_publicacion"
                            class="block w-full p-2 sm:p-4 text-sm sm:text-base rounded-2xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg focus:ring-2 focus:ring-blue-500" />
                        @error('fecha_publicacion') <span class="text-red-500 text-xs sm:text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="group">
                        <label class="font-semibold text-sm sm:text-base text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-star w-4 h-4"></i> ¿Destacada?
                        </label>
                        <div class="flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-2 sm:py-3 rounded-2xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                            <input type="checkbox" wire:model.defer="destacada" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500 cursor-pointer">
                            <label class="text-gray-700 dark:text-gray-300 text-xs sm:text-sm cursor-pointer">Sí, marcar como destacada</label>
                        </div>
                    </div>
                </div>

                {{-- Fila 4: PDF (Solo si es Noticia) --}}
                @if($this->tipo === 'noticia')
                <div class="relative group animate-in fade-in duration-300">
                    <label class="block text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-file-pdf w-4 h-4"></i> Archivo PDF
                    </label>
                    <div class="flex flex-col gap-3">
                        <input type="file" wire:model="archivo_pdf" accept=".pdf"
                            class="w-full p-2 sm:p-4 text-xs sm:text-sm rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 cursor-pointer hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all duration-300 file:mr-2 sm:file:mr-4 file:py-1 sm:file:py-2 file:px-2 sm:file:px-4 file:rounded-lg sm:file:rounded-xl file:border-0 file:text-xs sm:file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700" />
                    </div>

                    {{-- Indicador de carga --}}
                    <div wire:loading wire:target="archivo_pdf" class="flex items-center justify-center gap-2 sm:gap-3 mt-3 text-blue-600 text-sm sm:text-base">
                        <div class="flex gap-1.5">
                            <div class="w-2 h-2 sm:w-3 sm:h-3 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0s;"></div>
                            <div class="w-2 h-2 sm:w-3 sm:h-3 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
                            <div class="w-2 h-2 sm:w-3 sm:h-3 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.4s;"></div>
                        </div>
                        <span class="text-xs sm:text-sm font-medium">Subiendo archivo...</span>
                    </div>

                    {{-- Confirmación de archivo cargado --}}
                    @if ($archivo_pdf)
                        <div class="mt-4 p-3 sm:p-5 bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-800 dark:to-emerald-800 border-2 border-green-400 dark:border-green-600 rounded-2xl shadow-md animate-pulse">
                            <div class="flex items-center gap-2 sm:gap-4">
                                <i class="fa-solid fa-file-pdf w-6 h-6 sm:w-8 sm:h-8 text-green-600 flex-shrink-0"></i>
                                <div class="flex-1 min-w-0">
                                    <p class="text-green-900 dark:text-green-100 font-bold text-xs sm:text-sm">✓ PDF cargado correctamente</p>
                                    <p class="text-green-800 dark:text-green-200 text-xs truncate">{{ $archivo_pdf->getClientOriginalName() }}</p>
                                </div>
                                <button type="button" wire:click="$set('archivo_pdf', null)" class="flex-shrink-0 text-green-700 dark:text-green-300 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                                    <i class="fa-solid fa-xmark w-4 h-4 sm:w-6 sm:h-6"></i>
                                </button>
                            </div>
                        </div>
                    {{-- PDF Actual --}}
                    @elseif($archivo_pdf_actual)
                        <div class="mt-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2 p-3 sm:p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                            <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                                <div class="p-1.5 sm:p-2 bg-red-100 dark:bg-blue-900/30 rounded-lg flex-shrink-0">
                                    <i class="fa-solid fa-file-pdf w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">PDF actual</p>
                                    <p class="text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-200 truncate">{{ basename($archivo_pdf_actual) }}</p>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $archivo_pdf_actual) }}" target="_blank" 
                                class="inline-flex items-center justify-center gap-1 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs sm:text-sm font-semibold transition-colors whitespace-nowrap">
                                <i class="fa-solid fa-eye"></i> <span class="hidden sm:inline">Ver</span>
                            </a>
                        </div>
                    @endif                                
                </div>
                @endif

                {{-- Fila 5: Video (Solo si es Video O Crónica) --}}
                @if($this->tipo === 'video' || $this->tipo === 'cronica')
                <div class="grid grid-cols-1 gap-4 sm:gap-6 animate-in fade-in duration-300">
                    <div class="group">
                        <label class="font-semibold text-sm sm:text-base text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-video w-4 h-4"></i> Video 
                            @if($this->tipo === 'video') <span class="text-red-500">*</span> @endif
                        </label>
                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mb-3">
                            @if($this->tipo === 'video')
                                Selecciona cómo deseas agregar el video (URL o archivo - máx 20MB)
                            @else
                                Opcionalmente agrega un video al crónica (URL o archivo - máx 20MB)
                            @endif
                        </p>
                        
                        <div class="space-y-3 sm:space-y-4">
                            {{-- URL Video - SOLO PARA TIPO VIDEO --}}
                            @if($this->tipo === 'video')
                            <div>
                                <label class="text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">
                                    URL del Video (YouTube, Vimeo, etc.)
                                </label>
                                <input type="url" 
                                    wire:model.live="video_url" 
                                    placeholder="https://youtube.com/watch?v=..."
                                    class="block w-full p-2 sm:p-4 text-sm sm:text-base rounded-2xl border @error('video_url') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg focus:ring-2 focus:ring-blue-500" />
                                @error('video_url') 
                                    <span class="text-red-500 text-xs sm:text-sm flex items-center gap-1 mt-1">
                                        <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                                    </span> 
                                @enderror
                                
                                {{-- Indicador de URL detectada --}}
                                @if ($video_url && !$this->video_archivo)
                                    <div class="mt-2 sm:mt-3 p-2 sm:p-3 bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg flex items-center gap-2 text-xs sm:text-sm">
                                        <i class="fa-solid fa-check text-blue-600 dark:text-blue-400 flex-shrink-0"></i>
                                        <span class="text-blue-700 dark:text-blue-300">URL detectada ✓</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Separador - SOLO PARA TIPO VIDEO --}}
                            <div class="flex items-center gap-3">
                                <div class="flex-1 h-px bg-gray-300 dark:bg-gray-600"></div>
                                <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">O</span>
                                <div class="flex-1 h-px bg-gray-300 dark:bg-gray-600"></div>
                            </div>
                            @endif

                            {{-- Archivo Video --}}
                            <div>
                                <p class="text-yellow-700 dark:text-yellow-300 text-xs sm:text-base font-semibold mb-2">
                                    ⚠️ Si supera los 20MB, el video no se cargará.
                                </p>
                                <div class="relative">
                                    <input type="file" 
                                        wire:model="video_archivo" 
                                        accept="video/mp4,video/x-msvideo,video/mpeg,video/quicktime,.mp4,.avi,.mpeg,.mov"
                                        class="block w-full p-2 sm:p-4 text-xs sm:text-sm rounded-2xl border @error('video_archivo') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 cursor-pointer hover:border-blue-500 transition-all duration-300 file:mr-2 sm:file:mr-4 file:py-1 sm:file:py-2 file:px-2 sm:file:px-4 file:rounded-lg sm:file:rounded-full file:border-0 file:text-xs sm:file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 dark:file:bg-blue-900 dark:file:text-blue-200 hover:file:bg-blue-100" />
                                </div>
                                
                                {{-- Spinner mientras se sube --}}
                                <div wire:loading wire:target="video_archivo" class="flex items-center gap-2 sm:gap-3 mt-2 sm:mt-3 text-blue-600 dark:text-blue-400 text-sm">
                                    <div class="flex gap-1.5">
                                        <div class="w-2 h-2 sm:w-3 sm:h-3 bg-blue-600 dark:bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0s;"></div>
                                        <div class="w-2 h-2 sm:w-3 sm:h-3 bg-blue-600 dark:bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
                                        <div class="w-2 h-2 sm:w-3 sm:h-3 bg-blue-600 dark:bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0.4s;"></div>
                                    </div>
                                    <span class="text-xs sm:text-sm font-medium">Validando video... Por favor espera.</span>
                                </div>

                                {{-- Error durante la carga --}}
                                <div wire:loading.remove wire:target="video_archivo" class="mt-2">
                                    @error('video_archivo') 
                                        <span class="text-red-500 text-xs sm:text-sm flex items-center gap-1">
                                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                                        </span> 
                                    @enderror
                                </div>

                                {{-- Confirmación de archivo cargado --}}
                                @if ($video_archivo && !is_string($video_archivo))
                                    <div class="mt-4 p-3 sm:p-5 bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-800 dark:to-emerald-800 border-2 border-green-400 dark:border-green-600 rounded-2xl shadow-md animate-in fade-in slide-in-from-top-2 duration-300">
                                        <div class="flex items-center gap-2 sm:gap-4">
                                            <div class="flex-shrink-0">
                                                <i class="fa-solid fa-circle-check w-6 h-6 sm:w-8 sm:h-8 text-green-600 dark:text-green-400"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-green-900 dark:text-green-100 font-bold text-xs sm:text-sm">✓ Video cargado correctamente</p>
                                                <p class="text-green-800 dark:text-green-200 text-xs truncate" title="{{ $video_archivo->getClientOriginalName() }}">
                                                    {{ $video_archivo->getClientOriginalName() }}
                                                </p>
                                                <p class="text-green-700 dark:text-green-300 text-xs mt-1">
                                                    Tamaño: {{ round($video_archivo->getSize() / 1024 / 1024, 2) }} MB / 20 MB máx
                                                </p>
                                            </div>
                                            <button type="button" 
                                                wire:click="$set('video_archivo', null)" 
                                                class="flex-shrink-0 text-green-700 dark:text-green-300 hover:text-red-600 dark:hover:text-red-400 transition-colors p-1 hover:bg-red-100 dark:hover:bg-red-900 rounded-lg">
                                                <i class="fa-solid fa-xmark w-4 h-4 sm:w-6 sm:h-6"></i>
                                            </button>
                                        </div>
                                    </div>
                                @elseif ($video_archivo_actual)
                                    {{-- Video ya guardado en edición --}}
                                    <div class="mt-4 p-3 sm:p-5 bg-gradient-to-r from-purple-100 to-pink-100 dark:from-purple-800 dark:to-pink-800 border-2 border-purple-400 dark:border-purple-600 rounded-2xl shadow-md">
                                        <div class="flex items-center gap-2 sm:gap-4">
                                            <div class="flex-shrink-0">
                                                <i class="fa-solid fa-video w-6 h-6 sm:w-8 sm:h-8 text-purple-600 dark:text-purple-400"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-purple-900 dark:text-purple-100 font-bold text-xs sm:text-sm">Video guardado</p>
                                                <p class="text-purple-800 dark:text-purple-200 text-xs truncate">{{ $video_archivo_actual }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif

           {{-- Fila 6: Cronista y Crónica (Solo si es Crónica) --}}
@if($this->tipo === 'cronica')
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 animate-in fade-in duration-300">
    <div class="group">
        <label class="font-semibold text-sm sm:text-base text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-user-tie w-4 h-4"></i> Cronista *
        </label>
        <select wire:model.defer="cronista_id"
            class="block w-full p-2 sm:p-4 text-sm sm:text-base rounded-2xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg focus:ring-2 focus:ring-blue-500">
            <option value="">Seleccione un cronista</option>
            @foreach($cronistas as $cronista)
                <option value="{{ $cronista->id }}">{{ $cronista->nombre_completo }}</option>
            @endforeach
        </select>
    </div>

    <div class="group">
        <label class="font-semibold text-sm sm:text-base text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-book w-4 h-4"></i> Crónica *
        </label>
        <select wire:model.defer="cronica_id"
            class="block w-full p-2 sm:p-4 text-sm sm:text-base rounded-2xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg focus:ring-2 focus:ring-blue-500">
            <option value="">Seleccione una crónica</option>
            @foreach($cronicas as $cronica)
                <option value="{{ $cronica->id }}">{{ $cronica->titulo }}</option>
            @endforeach
        </select>
    </div>
</div>
@endif

<div class="flex flex-col gap-3 sm:gap-4 justify-center items-center">
    {{-- Mensaje informativo --}}
    <div class="w-full p-3 sm:p-5 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 rounded-lg">
        <p class="text-blue-700 dark:text-blue-200 text-xs sm:text-sm md:text-base font-medium flex items-start gap-2 sm:gap-3">
            <i class="fa-solid fa-lightbulb w-4 h-4 sm:w-5 sm:h-5 text-blue-500 flex-shrink-0 mt-0.5"></i>
            <span><strong>Consejo:</strong> ¿No sabes cómo empezar? Usa nuestra IA para crear contenido profesional automáticamente. Solo describe lo que necesitas.</span>
        </p>
    </div>

    {{-- Botón generar --}}
    <button 
        type="button"
        x-on:click="$openModal('persistentModal')" 
        class="inline-flex items-center justify-center gap-1 sm:gap-2 px-4 sm:px-6 md:px-8 py-2.5 sm:py-3 md:py-4 text-xs sm:text-sm md:text-base bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-lg sm:rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400 w-full sm:w-auto">
        <i class="fa-solid fa-wand-magic-sparkles"></i>
        <span class="hidden sm:inline">Generar con IA</span>
        <span class="sm:hidden">Generar IA</span>
    </button>
</div>

@include('livewire.pages.admin.noticias.form.modal')
{{-- Fila 7: Contenido --}}
<div x-data="{ max: 5000, texto: @entangle('contenido') }" class="relative group">
    <label class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
        <i class="fa-solid fa-file-lines w-4 h-4"></i> Contenido *
    </label>
    <textarea x-model="texto" maxlength="5000" rows="6 sm:rows-8" placeholder="Escribe el contenido completo de la noticia..."
        class="block w-full p-3 sm:p-4 text-sm sm:text-base rounded-2xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-300 resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 group-hover:scale-[1.01] group-hover:shadow-lg"
        wire:model.defer="contenido"></textarea>

    <div class="flex flex-col sm:flex-row sm:justify-between gap-2 mt-2 text-xs sm:text-sm text-gray-500 dark:text-gray-400">
        <p class="flex items-center gap-1">
            <i class="fa-solid fa-info-circle w-4 h-4 text-yellow-500 flex-shrink-0"></i>
            Máximo 5000 caracteres
        </p>
        <p class="font-semibold text-yellow-500 dark:text-yellow-400">
            <span x-text="max - texto.length"></span> restantes
        </p>
    </div>
</div>

{{-- Botones de acción --}}
<div class="flex flex-col sm:flex-row justify-center gap-2 sm:gap-4 md:gap-6 pt-4 sm:pt-6 border-t border-gray-200 dark:border-gray-700">
    @if($mode === 'create')
        <button type="button" wire:click="limpiar" wire:loading.attr="disabled" class="inline-flex items-center justify-center gap-1 sm:gap-2 px-3 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-lg sm:rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-gray-400 hover:to-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 disabled:opacity-50 disabled:cursor-not-allowed w-full sm:w-auto">
            <div wire:loading wire:target="limpiar" class="inline-block">
                <svg class="animate-spin h-4 w-4 sm:h-5 sm:w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            <div wire:loading.remove wire:target="limpiar">
                <i class="fa-solid fa-trash"></i>
            </div>
            <span class="hidden sm:inline">Limpiar</span>
            <span class="sm:hidden">Limpiar</span>
        </button>
    @endif
    <button type="submit" wire:loading.attr="disabled" class="inline-flex items-center justify-center gap-1 sm:gap-2 px-3 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-lg sm:rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400 disabled:opacity-50 disabled:cursor-not-allowed w-full sm:w-auto">
        <div wire:loading wire:target="save" class="inline-block">
            <svg class="animate-spin h-4 w-4 sm:h-5 sm:w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
        <div wire:loading.remove wire:target="save">
            <i class="fa-solid fa-check"></i>
        </div>
        @if($mode === 'edit')
            <span class="hidden sm:inline">Guardar Cambios</span>
            <span class="sm:hidden">Guardar</span>
        @else
            <span class="hidden sm:inline">Guardar</span>
            <span class="sm:hidden">Guardar</span>
        @endif
    </button>
</div>