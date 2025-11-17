                <div class="w-full">
                    <div class="w-full min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900 p-4">
                        <div class="w-full max-w-7xl bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-500">

                            {{-- Header --}}
                            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-700 to-indigo-900 text-white">
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-newspaper text-3xl text-white animate-bounce"></i>
                                    <h2 class="text-3xl md:text-4xl font-extrabold tracking-wide">
                                        @if($mode === 'edit')
                                            Editar Noticia
                                        @else
                                            Crear Nueva Noticia
                                        @endif
                                    </h2>
                                </div>
                                    <x-button slate wire:click="cancel" spinner="cancel" label="Volver" icon="arrow-left"
                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white 
                font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2x
                l focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400" />    
                            </div>

                            {{-- Información --}}
                            <div class="p-4 bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 dark:border-blue-400 rounded-b-3xl">
                                <p class="text-blue-700 dark:text-blue-200 text-sm md:text-base flex items-center gap-2">
                                    <i class="fa-solid fa-circle-info text-blue-500 dark:text-blue-300"></i>
                                    Completa la información de la noticia. Los campos con * son obligatorios.
                                </p>
                            </div>

                            {{-- Formulario --}}
                            <form wire:submit.prevent="save" enctype="multipart/form-data" class="p-8 space-y-8">

                                {{-- Fila 1: Título --}}
                                <div class="grid grid-cols-1 gap-6">
                                    <div class="group">
                                        <label class="font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                                            <i class="fa-solid fa-heading w-4 h-4"></i> Título *
                                        </label>
                                        <input type="text" wire:model.defer="titulo" placeholder="Ej: Noticia importante de la empresa"
                                            class="block w-full p-4 rounded-2xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg focus:ring-2 focus:ring-blue-500" />
                                     
                                    </div>
                                </div>

                                {{-- Fila 2: Imagen y Tipo --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {{-- Imagen --}}
                                    <div class="relative group">
                                        <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                                            <i class="fa-solid fa-image w-4 h-4"></i> Imagen destacada *
                                        </label>
                                        <div class="flex flex-col gap-3">
                                            <input type="file" wire:model="imagen" accept="image/*"
                                                class="w-full p-4 rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 cursor-pointer hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all duration-300 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700" />
                                        </div>

                                        {{-- Indicador de carga --}}
                                        <div wire:loading wire:target="imagen" class="flex items-center justify-center gap-3 mt-3 text-blue-600">
                                            <div class="flex gap-1.5">
                                                <div class="w-3 h-3 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0s;"></div>
                                                <div class="w-3 h-3 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
                                                <div class="w-3 h-3 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.4s;"></div>
                                            </div>
                                            <span class="text-sm font-medium">Subiendo imagen...</span>
                                        </div>

                                        {{-- Confirmación de archivo cargado --}}
                                        @if ($imagen)
                                            <div class="mt-4 p-5 bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-800 dark:to-emerald-800 border-2 border-green-400 dark:border-green-600 rounded-2xl shadow-md animate-pulse">
                                                <div class="flex items-center gap-4">
                                                    <img src="{{ $imagen->temporaryUrl() }}" class="w-12 h-12 object-cover rounded-lg">
                                                    <div class="flex-1">
                                                        <p class="text-green-900 dark:text-green-100 font-bold">✓ Imagen cargada</p>
                                                        <p class="text-green-800 dark:text-green-200 text-sm truncate">{{ $imagen->getClientOriginalName() }}</p>
                                                    </div>
                                                    <button type="button" wire:click="$set('imagen', null)" class="text-green-700 dark:text-green-300 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                                                        <i class="fa-solid fa-xmark w-6 h-6"></i>
                                                    </button>
                                                </div>
                                            </div>
                              {{-- Imagen Actual --}}
                                @elseif($imagen_actual)
                                    <div class="mt-4 flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ asset('storage/' . $imagen_actual) }}" class="w-12 h-12 object-cover rounded-lg shadow-sm">
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Imagen actual</p>
                                                <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Imagen cargada</p>
                                            </div>
                                        </div>
                                        <a href="{{ asset('storage/' . $imagen_actual) }}" target="_blank" 
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-sm font-semibold transition-colors whitespace-nowrap">
                                            <i class="fa-solid fa-eye"></i> Ver
                                        </a>
                                    </div>
                                @endif
                                    
                                    </div>

                                    {{-- Tipo --}}
                                    <div class="group">
                                        <label class="font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                                            <i class="fa-solid fa-list w-4 h-4"></i> Tipo de publicación *
                                        </label>
                                        <select wire:model.live="tipo"
                                            class="block w-full p-4 rounded-2xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg focus:ring-2 focus:ring-blue-500">
                                            <option value="">Seleccione un tipo</option>
                                            <option value="noticia">Noticia</option>
                                            <option value="flyer">Flyer</option>
                                            <option value="video">Video</option>
                                            <option value="cronica">Crónica</option>
                                        </select>
                                     
                                    </div>
                                </div>

                                {{-- Fila 3: Fecha de Publicación y Destacada --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="group">
                                        <label class="font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                                            <i class="fa-solid fa-calendar w-4 h-4"></i> Fecha de publicación *
                                        </label>
                                        <input type="date" wire:model.defer="fecha_publicacion"
                                            class="block w-full p-4 rounded-2xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg focus:ring-2 focus:ring-blue-500" />
                                        @error('fecha_publicacion') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="group">
                                        <label class="font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                                            <i class="fa-solid fa-star w-4 h-4"></i> ¿Destacada?
                                        </label>
                                        <div class="flex items-center gap-3 px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                                            <input type="checkbox" wire:model.defer="destacada" class="w-4 h-8 text-blue-600 rounded focus:ring-blue-500 cursor-pointer">
                                            <label class="text-gray-700 dark:text-gray-300 text-sm cursor-pointer">Sí, marcar como destacada</label>
                                        </div>
                                    </div>
                                </div>

                                {{-- Fila 4: PDF (Solo si es Noticia) --}}
                                @if($this->tipo === 'noticia')
                                <div class="relative group animate-in fade-in duration-300">
                                    <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                                        <i class="fa-solid fa-file-pdf w-4 h-4"></i> Archivo PDF
                                    </label>
                                    <div class="flex flex-col gap-3">
                                        <input type="file" wire:model="archivo_pdf" accept=".pdf"
                                            class="w-full p-4 rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 cursor-pointer hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all duration-300 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700" />
                                    </div>

                                    {{-- Indicador de carga --}}
                                    <div wire:loading wire:target="archivo_pdf" class="flex items-center justify-center gap-3 mt-3 text-blue-600">
                                        <div class="flex gap-1.5">
                                            <div class="w-3 h-3 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0s;"></div>
                                            <div class="w-3 h-3 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
                                            <div class="w-3 h-3 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.4s;"></div>
                                        </div>
                                        <span class="text-sm font-medium">Subiendo archivo...</span>
                                    </div>

                                    {{-- Confirmación de archivo cargado --}}
                                    @if ($archivo_pdf)
                                        <div class="mt-4 p-5 bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-800 dark:to-emerald-800 border-2 border-green-400 dark:border-green-600 rounded-2xl shadow-md animate-pulse">
                                            <div class="flex items-center gap-4">
                                                <i class="fa-solid fa-file-pdf w-8 h-8 text-green-600"></i>
                                                <div class="flex-1">
                                                    <p class="text-green-900 dark:text-green-100 font-bold">✓ PDF cargado correctamente</p>
                                                    <p class="text-green-800 dark:text-green-200 text-sm truncate">{{ $archivo_pdf->getClientOriginalName() }}</p>
                                                </div>
                                                <button type="button" wire:click="$set('archivo_pdf', null)" class="flex-shrink-0 text-green-700 dark:text-green-300 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                                                    <i class="fa-solid fa-xmark w-6 h-6"></i>
                                                </button>
                                            </div>
                                        </div>
                       {{-- PDF Actual --}}
@elseif($archivo_pdf_actual)
    <div class="mt-4 flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-red-100 dark:bg-blue-900/30 rounded-lg">
                <i class="fa-solid fa-file-pdf w-5 h-5 text-blue-600 dark:text-blue-400"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">PDF actual</p>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-200 truncate">{{ basename($archivo_pdf_actual) }}</p>
            </div>
        </div>
        <a href="{{ asset('storage/' . $archivo_pdf_actual) }}" target="_blank" 
            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition-colors whitespace-nowrap">
            <i class="fa-solid fa-eye"></i> Ver
        </a>
    </div>
@endif                                
                                </div>
                                @endif


                        {{-- Fila 5: Video (Solo si es Video O Crónica) --}}
                @if($this->tipo === 'video' || $this->tipo === 'cronica')
                <div class="grid grid-cols-1 gap-6 animate-in fade-in duration-300">
                    <div class="group">
                        <label class="font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-video w-4 h-4"></i> Video 
                            @if($this->tipo === 'video') <span class="text-red-500">*</span> @endif
                        </label>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                            @if($this->tipo === 'video')
                                Selecciona cómo deseas agregar el video (URL o archivo - máx 20MB)
                            @else
                                Opcionalmente agrega un video al crónica (URL o archivo - máx 20MB)
                            @endif
                        </p>
                        
                        <div class="space-y-4">
                            {{-- URL Video - SOLO PARA TIPO VIDEO --}}
                            @if($this->tipo === 'video')
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">
                                    URL del Video (YouTube, Vimeo, etc.)
                                </label>
                                <input type="url" 
                                    wire:model.live="video_url" 
                                    placeholder="https://youtube.com/watch?v=..."
                                    class="block w-full p-4 rounded-2xl border @error('video_url') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg focus:ring-2 focus:ring-blue-500" />
                                @error('video_url') 
                                    <span class="text-red-500 text-sm flex items-center gap-1 mt-1">
                                        <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                                    </span> 
                                @enderror
                                
                                {{-- Indicador de URL detectada --}}
                                @if ($video_url && !$this->video_archivo)
                                    <div class="mt-3 p-3 bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg flex items-center gap-2">
                                        <i class="fa-solid fa-check text-blue-600 dark:text-blue-400"></i>
                                        <span class="text-sm text-blue-700 dark:text-blue-300">URL detectada ✓</span>
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
                                <p class="text-yellow-700 dark:text-yellow-300 text-base font-semibold mt-2">
                                    ⚠️ Si supera los 20MB, el video no se cargará.
                                </p>
                                <br>
                                <div class="relative">
                                    <input type="file" 
                                        wire:model="video_archivo" 
                                        accept="video/mp4,video/x-msvideo,video/mpeg,video/quicktime,.mp4,.avi,.mpeg,.mov"
                                        class="block w-full p-4 rounded-2xl border @error('video_archivo') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 cursor-pointer hover:border-blue-500 transition-all duration-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 dark:file:bg-blue-900 dark:file:text-blue-200 hover:file:bg-blue-100" />
                                </div>
                                
                                {{-- Spinner mientras se sube --}}
                                <div wire:loading wire:target="video_archivo" class="flex items-center gap-3 mt-3 text-blue-600 dark:text-blue-400">
                                    <div class="flex gap-1.5">
                                        <div class="w-3 h-3 bg-blue-600 dark:bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0s;"></div>
                                        <div class="w-3 h-3 bg-blue-600 dark:bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
                                        <div class="w-3 h-3 bg-blue-600 dark:bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0.4s;"></div>
                                    </div>
                                    <span class="text-sm font-medium">Validando video... Por favor espera.</span>
                                </div>

                                {{-- Error durante la carga --}}
                                <div wire:loading.remove wire:target="video_archivo" class="mt-2">
                                    @error('video_archivo') 
                                        <span class="text-red-500 text-sm flex items-center gap-1">
                                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                                        </span> 
                                    @enderror
                                </div>

                                {{-- Confirmación de archivo cargado --}}
                                @if ($video_archivo && !is_string($video_archivo))
                                    <div class="mt-4 p-5 bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-800 dark:to-emerald-800 border-2 border-green-400 dark:border-green-600 rounded-2xl shadow-md animate-in fade-in slide-in-from-top-2 duration-300">
                                        <div class="flex items-center gap-4">
                                            <div class="flex-shrink-0">
                                                <i class="fa-solid fa-circle-check w-8 h-8 text-green-600 dark:text-green-400"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-green-900 dark:text-green-100 font-bold">✓ Video cargado correctamente</p>
                                                <p class="text-green-800 dark:text-green-200 text-sm truncate" title="{{ $video_archivo->getClientOriginalName() }}">
                                                    {{ $video_archivo->getClientOriginalName() }}
                                                </p>
                                                <p class="text-green-700 dark:text-green-300 text-xs mt-1">
                                                    Tamaño: {{ round($video_archivo->getSize() / 1024 / 1024, 2) }} MB / 20 MB máx
                                                </p>
                                            </div>
                                            <button type="button" 
                                                wire:click="$set('video_archivo', null)" 
                                                class="flex-shrink-0 text-green-700 dark:text-green-300 hover:text-red-600 dark:hover:text-red-400 transition-colors p-2 hover:bg-red-100 dark:hover:bg-red-900 rounded-lg">
                                                <i class="fa-solid fa-xmark w-6 h-6"></i>
                                            </button>
                                        </div>
                                    </div>
                                @elseif ($video_archivo_actual)
                                    {{-- Video ya guardado en edición --}}
                                    <div class="mt-4 p-5 bg-gradient-to-r from-purple-100 to-pink-100 dark:from-purple-800 dark:to-pink-800 border-2 border-purple-400 dark:border-purple-600 rounded-2xl shadow-md">
                                        <div class="flex items-center gap-4">
                                            <div class="flex-shrink-0">
                                                <i class="fa-solid fa-video w-8 h-8 text-purple-600 dark:text-purple-400"></i>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-purple-900 dark:text-purple-100 font-bold">Video guardado</p>
                                                <p class="text-purple-800 dark:text-purple-200 text-sm">{{ $video_archivo_actual }}</p>
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 animate-in fade-in duration-300">
                    <div class="group">
                        <label class="font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-user-tie w-4 h-4"></i> Cronista *
                        </label>
                        <select wire:model.defer="cronista_id"
                            class="block w-full p-4 rounded-2xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccione un cronista</option>
                            @foreach($cronistas as $cronista)
                                <option value="{{ $cronista->id }}">{{ $cronista->nombre_completo }}</option>
                            @endforeach
                        </select>
                    
                    </div>

                                <div class="group">
                    <label class="font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-book w-4 h-4"></i> Crónica *
                    </label>
                    <select wire:model.defer="cronica_id"
                        class="block w-full p-4 rounded-2xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Seleccione una crónica</option>
                        @foreach($cronicas as $cronica)
                            <option value="{{ $cronica->id }}">{{ $cronica->titulo }}</option>
                        @endforeach
                    </select>
                
                </div>
            </div>
            @endif
                {{-- Fila 7: Contenido --}}
                <div x-data="{ max: 5000, texto: @entangle('contenido') }" class="relative group">
                    <label class="flex items-center gap-2 mb-2 text-base font-semibold text-gray-700 dark:text-gray-300">
                        <i class="fa-solid fa-file-lines w-4 h-4"></i> Contenido *
                    </label>
                    <textarea x-model="texto" maxlength="5000" rows="8" placeholder="Escribe el contenido completo de la noticia..."
                        class="block w-full p-4 rounded-2xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-300 resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 group-hover:scale-[1.01] group-hover:shadow-lg"
                        wire:model.defer="contenido"></textarea>

                    <div class="flex justify-between mt-2 text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                        <p class="flex items-center gap-1">
                            <i class="fa-solid fa-info-circle w-4 h-4 text-yellow-500"></i>
                            Máximo 5000 caracteres
                        </p>
                        <p class="font-semibold text-yellow-500 dark:text-yellow-400">
                            <span x-text="max - texto.length"></span> restantes
                        </p>
                    </div>
              
                </div>

                {{-- Botones de acción --}}
                <div class="flex justify-center gap-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    @if($mode === 'create')
                        <button type="button" wire:click="limpiar" wire:loading.attr="disabled" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-gray-400 hover:to-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 disabled:opacity-50 disabled:cursor-not-allowed">
                            <div wire:loading wire:target="limpiar" class="inline-block">
                                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                            <div wire:loading.remove wire:target="limpiar">
                                <i class="fa-solid fa-trash"></i>
                            </div>
                            Limpiar
                        </button>
                    @endif
                    <button type="submit" wire:loading.attr="disabled" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400 disabled:opacity-50 disabled:cursor-not-allowed">
                        <div wire:loading wire:target="save" class="inline-block">
                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <div wire:loading.remove wire:target="save">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        @if($mode === 'edit')
                            Guardar Cambios
                        @else
                            Guardar
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>