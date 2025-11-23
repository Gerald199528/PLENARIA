<div class="w-full min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900 p-4">
    <div class="w-full max-w-7xl bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-500">

        <!-- Encabezado -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-700 to-indigo-900 text-white gap-4">
            <div class="flex items-center gap-2 sm:gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 sm:w-8 sm:h-8 text-white animate-bounce" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.48.91 2.47 2.22 2.97 3.45V19h3v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                </svg>
                <h2 class="text-lg sm:text-2xl md:text-3xl lg:text-4xl font-extrabold tracking-wide">
                    {{ $mode === 'edit' ? 'Editar Cronista' : 'Registrar Cronista' }}
                </h2>
            </div>
            <x-button slate wire:click="cancel" spinner="cancel" label="Volver" icon="arrow-left"
                class="inline-flex items-center gap-1 sm:gap-2 px-3 sm:px-4 md:px-6 py-1.5 sm:py-2 md:py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-lg sm:rounded-xl shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-2xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 text-xs sm:text-sm md:text-base w-full sm:w-auto justify-center" />    
        </div>

        <!-- Mensaje informativo -->
        <div class="p-3 sm:p-4 md:p-6 bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 dark:border-blue-400">
            <p class="text-sm sm:text-base md:text-lg text-blue-700 dark:text-blue-200 font-medium flex items-start gap-2 animate-fadeIn">
                <x-icon name="information-circle" class="w-4 h-4 sm:w-5 sm:h-5 text-blue-500 dark:text-blue-300 flex-shrink-0 mt-0.5" />
                <span>
                    @if($mode === 'create') <strong>Para registrar:</strong> Completa la información del <strong>cronista</strong> con sus datos personales, cargo y foto de perfil. @endif
                    @if($mode === 'edit') <strong>Para editar:</strong> Puedes cambiar cualquier campo existente. @endif
                </span>
            </p>
        </div>

        <!-- Formulario -->
        <form wire:submit.prevent="{{ $mode === 'edit' ? 'update' : 'save' }}" class="p-4 sm:p-6 md:p-8">

            <!-- Imagen de Perfil -->
            <div class="relative group bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 p-4 sm:p-6 rounded-3xl border border-blue-200 dark:border-blue-700 mb-6">
                <label for="imagen" class="flex items-center gap-2 sm:gap-3 mb-4 sm:mb-6 text-base sm:text-lg font-bold text-gray-800 dark:text-gray-200">
                    <x-icon name="photo" class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" /> 
                    <span>Imagen de Perfil del Cronista</span>
                </label>
                <div class="space-y-4 sm:space-y-6">
                    <input type="file" id="imagen" wire:model="imagen" accept="image/*"
                        class="block w-full p-2 sm:p-4 text-xs sm:text-base text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 border-2 border-dashed border-blue-300 dark:border-yellow-600 rounded-2xl cursor-pointer hover:border-yellow-500 dark:hover:border-yellow-400 focus:ring-2 focus:ring-blue-500 focus:border-yellow-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
                    <p class="mt-2 text-xs sm:text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
                        <x-icon name="information-circle" class="w-4 h-4 text-blue-500 flex-shrink-0" />
                        Formatos: JPEG, PNG, JPG, GIF - Máximo 2MB
                    </p>

                    <div class="flex justify-center">
                        <div wire:loading wire:target="imagen" class="text-center">
                            <div class="w-32 h-32 sm:w-40 sm:h-40 border-2 border-dashed border-yellow-300 dark:border-yellow-600 rounded-3xl flex items-center justify-center bg-yellow-50 dark:bg-yellow-900/30 animate-pulse">
                                <div class="text-center">
                                    <div class="animate-spin rounded-full h-6 h-6 sm:h-8 sm:w-8 border-b-2 border-yellow-600 mx-auto mb-2"></div>
                                    <p class="text-xs sm:text-sm font-medium text-yellow-600 dark:text-yellow-400">Cargando imagen...</p>
                                </div>
                            </div>
                        </div>

                        <div wire:loading.remove wire:target="imagen">
                            @if ($imagen && is_object($imagen))
                                <div class="text-center">
                                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mb-3 font-medium">Vista Previa:</p>
                                    <div class="relative inline-block">
                                        <img src="{{ $imagen->temporaryUrl() }}" alt="Vista previa de imagen" 
                                            class="w-32 h-32 sm:w-40 sm:h-40 md:w-48 md:h-48 rounded-3xl object-cover shadow-xl border-4 border-white dark:border-gray-600 transform transition-all duration-300 hover:scale-105">
                                        <div class="absolute -top-2 -right-2 bg-green-500 text-white rounded-full p-1 shadow-lg animate-bounce">
                                            <x-icon name="check" class="w-4 h-4" />
                                        </div>
                                    </div>
                                    <p class="mt-2 text-xs text-green-600 dark:text-green-400 font-medium flex items-center justify-center gap-1">
                                        <x-icon name="check-circle" class="w-4 h-4" />
                                        Imagen cargada correctamente
                                    </p>
                                </div>
                            @elseif ($mode === 'edit' && $cronista && $cronista->imagen_url)
                                <div class="text-center mb-4">
                                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mb-2">Imagen actual:</p>
                                    <img src="{{ Storage::url($cronista->imagen_url) }}" 
                                        alt="Imagen actual"
                                        class="w-32 h-32 sm:w-40 sm:h-40 md:w-48 md:h-48 rounded-3xl object-cover shadow-xl border-4 border-white dark:border-gray-600">
                                </div>
                            @else
                                <div class="text-center text-gray-500 dark:text-gray-400">
                                    <div class="w-32 h-32 sm:w-40 sm:h-40 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-3xl flex items-center justify-center bg-gray-50 dark:bg-gray-700/50 transition-all duration-300 hover:border-yellow-400 hover:bg-yellow-50 dark:hover:bg-blue-900/20">
                                        <div class="text-center px-2">
                                            <x-icon name="photo" class="w-8 h-8 sm:w-12 sm:h-12 mx-auto mb-2 text-gray-400" />
                                            <p class="text-xs sm:text-sm font-medium">Sin imagen seleccionada</p>
                                            <p class="text-xs text-gray-400 mt-1">Haz clic arriba para seleccionar</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Formulario Cronista -->
            <div class="space-y-4 sm:space-y-6">

                <!-- Cédula -->
                <div class="relative group" x-data>
                    <label for="cedula"
                        class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                        <x-icon name="identification" class="w-4 h-4 sm:w-5 sm:h-5" /> Cédula
                    </label>
                    <div
                        class="flex transform transition-all duration-300 group-hover:scale-[1.01] focus-within:scale-[1.01] hover:shadow-lg rounded-2xl overflow-hidden">
                        <span
                            class="inline-flex items-center px-2 sm:px-3 rounded-l-2xl border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold text-sm sm:text-base transition-all duration-300 group-hover:bg-gray-300 dark:group-hover:bg-gray-600">
                            V
                        </span>
                        <input type="text" id="cedula" wire:model="cedula"
                            x-on:input="$el.value = $el.value.replace(/[^0-9]/g,''); if($el.value.length > 8) { $el.value = $el.value.slice(0,8) } @this.set('cedula', $el.value);"
                            maxlength="8" placeholder="Ej: 12345678"
                            class="block w-full p-2 sm:p-4 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 rounded-r-2xl transform hover:scale-[1.01] focus:scale-[1.01]">
                    </div>
                </div>

                <!-- Nombre y Apellido en la misma fila -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                    <!-- Nombre -->
                    <div class="relative group">
                        <label for="nombre" class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                            <x-icon name="user" class="w-4 h-4 sm:w-5 sm:h-5" /> Nombre completo
                        </label>
                        <input type="text" id="nombre" wire:model="nombre" placeholder="Ej: Juan"
                            class="block w-full p-2 sm:p-4 text-sm sm:text-base text-gray-900 dark:text-gray-100 
                            bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 
                            rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 
                            transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
                    </div>

                    <!-- Apellido -->
                    <div class="relative group">
                        <label for="apellido" class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                            <x-icon name="user" class="w-4 h-4 sm:w-5 sm:h-5" /> Apellido completo
                        </label>
                        <input type="text" id="apellido" wire:model="apellido" placeholder="Ej: Pérez"
                            class="block w-full p-2 sm:p-4 text-sm sm:text-base text-gray-900 dark:text-gray-100 
                            bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 
                            rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 
                            transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
                    </div>
                </div>

                <!-- Teléfono y Cargo -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                    <div class="relative group" x-data>
                        <label for="telefono" class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                            <x-icon name="phone" class="w-4 h-4 sm:w-5 sm:h-5" /> Teléfono
                        </label>
                        <div class="flex transform transition-all duration-300 group-hover:scale-[1.01] focus-within:scale-[1.01] hover:shadow-lg rounded-2xl overflow-hidden">
                            <span class="inline-flex items-center px-2 sm:px-3 rounded-l-2xl border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold text-sm sm:text-base transition-all duration-300 group-hover:bg-gray-300 dark:group-hover:bg-gray-600">
                                +58
                            </span>
                            <input type="text" id="telefono" wire:model="telefono"
                                x-on:input="$el.value = $el.value.replace(/[^0-9]/g,''); if($el.value.length > 11) { $el.value = $el.value.slice(0,11) } @this.set('telefono', $el.value);"
                                maxlength="11" placeholder="Ej: 04129766844"
                                class="block w-full p-2 sm:p-4 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 rounded-r-2xl transform hover:scale-[1.01] focus:scale-[1.01]">
                            </div>
                    </div>

                    <div class="relative group">
                        <label for="cargo" class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                            <x-icon name="briefcase" class="w-4 h-4 sm:w-5 sm:h-5" /> Cargo
                        </label>
                        <input type="text" id="cargo" wire:model="cargo" placeholder="Ej: Cronista"
                            class="block w-full p-2 sm:p-4 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
                    </div>
                </div>

                <!-- Email y Fecha de Ingreso -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                    <!-- Email -->
                    <div class="relative group">
                        <label for="email" class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                            <x-icon name="envelope" class="w-4 h-4 sm:w-5 sm:h-5" /> Email
                        </label>
                        <input type="email" id="email" wire:model="email" placeholder="Ej: correo@ejemplo.com"
                            class="block w-full p-2 sm:p-4 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
                    </div>

                    <!-- Fecha de Ingreso -->
                    <div class="relative group">
                        <label for="fecha_ingreso" class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                            <x-icon name="calendar" class="w-4 h-4 sm:w-5 sm:h-5" /> Fecha de Ingreso
                        </label>
                        <input type="date" id="fecha_ingreso" wire:model="fecha_ingreso"
                            class="block w-full p-2 sm:p-4 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
                    </div>
                </div>

                <!-- Selects de Ubicación -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 sm:gap-4">
                    <!-- Estado -->
                    <div class="relative group">
                        <label class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                            <x-icon name="map" class="w-4 h-4 sm:w-5 sm:h-5" /> Estado
                        </label>
                        <select wire:model.live="estado_id"
                            class="block w-full p-2 sm:p-4 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
                            <option value="">-- Seleccione Estado --</option>
                            @foreach($estados as $estado)
                            <option value="{{ $estado->id }}">{{ $estado->estado }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Municipio -->
                    @if($estado_id)
                    <div class="relative group">
                        <label class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                            <x-icon name="map-pin" class="w-4 h-4 sm:w-5 sm:h-5" /> Municipio
                        </label>
                        <select wire:model.live="municipio_id"
                            class="block w-full p-2 sm:p-4 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
                            <option value="">-- Seleccione Municipio --</option>
                            @foreach($municipios as $municipio)
                            <option value="{{ $municipio->id }}">{{ $municipio->municipio }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <!-- Parroquia -->
                    @if($municipio_id)
                    <div class="relative group">
                        <label class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                            <x-icon name="map-pin" class="w-4 h-4 sm:w-5 sm:h-5" /> Parroquia
                        </label>
                        <select wire:model.live="parroquia_id"
                            class="block w-full p-2 sm:p-4 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
                            <option value="">-- Seleccione Parroquia --</option>
                            @foreach($parroquias as $parroquia)
                            <option value="{{ $parroquia->id }}">{{ $parroquia->parroquia }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>

                <!-- Perfil -->
                <div x-data="{ max: 1000, texto: @entangle('perfil') || '' }" class="relative group">
                    <label for="perfil"
                        class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                        <x-icon name="chat-bubble-left-right" class="w-4 h-4 sm:w-5 sm:h-5" /> Perfil / Descripción del cronista
                    </label>
                    <textarea id="perfil" rows="4 sm:rows-6" x-model="texto" maxlength="1000"
                        class="block w-full p-3 sm:p-5 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl resize-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg"
                        placeholder="Describe aquí el perfil y funciones del cronista..."></textarea>
                    <div class="flex flex-col sm:flex-row sm:justify-between gap-2 mt-2 text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                        <p class="flex items-center gap-1"><x-icon name="information-circle" class="w-4 h-4 text-yellow-500 flex-shrink-0" />
                            Máximo 1000 caracteres</p>
                        <p class="font-semibold text-yellow-500 dark:text-yellow-400">
                            <span x-text="max - (texto ? texto.length : 0)"></span> restantes
                        </p>
                    </div>
                </div>

                <!-- Botones centrados -->
                <div class="flex flex-col sm:flex-row justify-center gap-2 sm:gap-4 md:gap-8 pt-4 sm:pt-6 border-t border-gray-200 dark:border-gray-700">
                    @if($mode == 'create')
                        <x-button slate wire:click="limpiar" spinner="limpiar" label="Limpiar" icon="trash"
                            class="inline-flex items-center justify-center gap-1 sm:gap-2 px-3 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-lg sm:rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-gray-400 hover:to-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 w-full sm:w-auto" />
                    @endif

                    <x-button info type="submit" label="{{ $mode === 'edit' ? 'Actualizar Cronista' : 'Guardar Cronista' }}" 
                        icon="check" spinner="{{ $mode === 'edit' ? 'update' : 'save' }}" 
                        class="inline-flex items-center justify-center gap-1 sm:gap-2 px-3 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-lg sm:rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400 w-full sm:w-auto" />
                </div>

            </div>

        </form>

    </div>
</div>