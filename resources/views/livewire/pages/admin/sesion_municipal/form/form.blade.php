<div class="w-full min-h-screen bg-gray-100 dark:bg-gray-900 p-4 py-6 sm:py-8">
    <div class="w-full max-w-6xl mx-auto bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 sm:p-6 md:p-8 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-700 to-indigo-900 text-white gap-4">
            <div class="flex items-center gap-2 sm:gap-3 md:gap-4">
                <x-icon name="{{ $mode === 'create' ? 'document-plus' : 'pencil-square' }}" class="w-7 h-7 sm:w-8 sm:h-8 md:w-10 md:h-10 text-white animate-bounce" />
                <h2 class="text-lg sm:text-2xl md:text-3xl lg:text-4xl font-extrabold tracking-wide">
                    {{ $mode === 'create' ? 'Registrar Sesión Municipal' : 'Editar Sesión Municipal' }}
                </h2>
            </div>
            <x-button slate wire:click="cancel" spinner="cancel" label="Volver" icon="arrow-left"
                class="inline-flex items-center gap-1 sm:gap-2 px-3 sm:px-4 md:px-6 py-1.5 sm:py-2 md:py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-lg sm:rounded-xl shadow-lg transition-all duration-300 hover:scale-105 text-xs sm:text-sm md:text-base w-full sm:w-auto justify-center" />
        </div>

        {{-- Formulario --}}
        <form wire:submit.prevent="save" class="p-4 sm:p-6 md:p-10 space-y-6 sm:space-y-8 md:space-y-10">

       
            {{-- SECCIÓN CATEGORÍA --}}
            <div class="border-b-2 pb-6 sm:pb-8 border-gray-200 dark:border-gray-700">
                <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 dark:text-gray-100 mb-4 sm:mb-6 md:mb-8 flex items-center gap-2 sm:gap-3">
                    <x-icon name="folder" class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 text-blue-600" />
                    Información de la Categoría
                </h3>

                {{-- SELECT DE CATEGORÍAS EXISTENTES --}}
                <div class="relative group mb-6 sm:mb-8">
                    <label class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                        <x-icon name="tag" class="w-4 h-4 sm:w-5 sm:h-5" /> Seleccionar Categoría {{ $mode === 'create' ? '(Requerido)' : '' }}
                    </label>
                    <select wire:model.live="categoria_id" 
                        class="block w-full p-3 sm:p-5 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
                        <option value="">-- {{ $mode === 'create' ? 'Seleccionar o crear nueva' : 'Seleccionar categoría' }} --</option>
                        @foreach($this->categorias as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                    @error('categoria_id')
                        <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- CAMPOS PARA CREAR NUEVA CATEGORÍA --}}
                @if($mode === 'create' && !$categoria_id)
                    {{-- MENSAJE DE ALERTA AMARILLO --}}
                    <div class="mb-6 sm:mb-8 p-4 sm:p-5 bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-700 rounded-2xl animate-fadeIn">
                        <div class="flex items-start gap-3">
                            <x-icon name="exclamation-triangle" class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-600 dark:text-yellow-500 flex-shrink-0 mt-0.5" />
                            <div>
                                <p class="text-sm sm:text-base font-semibold text-yellow-900 dark:text-yellow-100">
                                    ¡Advertencia: Categoría!
                                </p>
                                <p class="text-sm sm:text-base text-yellow-800 dark:text-yellow-200 mt-2">
                                    Si no existe. Por favor ¡Registra una! llenando (nombre) y ( descripción)
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Nombre Categoría --}}
                    <div class="relative group mb-6 sm:mb-8 animate-fadeIn">
                        <label class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                            <x-icon name="tag" class="w-4 h-4 sm:w-5 sm:h-5" /> Nombre de la Categoría
                        </label>
                        <input type="text" wire:model="categoria_nombre" maxlength="255"
                            placeholder="Ej: Servicios Públicos"
                            class="block w-full p-3 sm:p-5 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
                        @error('categoria_nombre')
                            <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Descripción Categoría --}}
                    <div x-data="{ max: 1000, texto: @entangle('categoria_descripcion') }" class="relative group animate-fadeIn">
                        <label class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                            <x-icon name="document-text" class="w-4 h-4 sm:w-5 sm:h-5" /> Descripción de la Categoría
                        </label>
                        <textarea wire:model="categoria_descripcion" maxlength="1000" rows="3 sm:rows-4"
                            placeholder="Describe esta categoría..."
                            class="block w-full p-3 sm:p-5 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg"></textarea>
                        <div class="flex flex-col sm:flex-row sm:justify-between gap-2 mt-2 text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                            <p class="flex items-center gap-1"><x-icon name="information-circle" class="w-4 h-4 text-yellow-500 flex-shrink-0" /> Máximo 1000 caracteres</p>
                            <p class="font-semibold text-yellow-500 dark:text-yellow-400"><span x-text="max - texto.length"></span> restantes</p>
                        </div>
                        @error('categoria_descripcion')
                            <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                {{-- MOSTRAR DESCRIPCIÓN DE LA CATEGORÍA SELECCIONADA (En EDIT) --}}
                @if($mode === 'edit' && $categoria_id)
                    <div class="mt-4 sm:mt-6 p-3 sm:p-4 bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-2xl animate-fadeIn">
                        <p class="text-xs sm:text-sm font-semibold text-blue-900 dark:text-blue-100">Descripción de la categoría:</p>
                        <p class="text-xs sm:text-sm text-blue-800 dark:text-blue-200 mt-2">{{ $categoria_actual->descripcion ?? 'Sin descripción' }}</p>
                    </div>
                @endif
            </div>

            {{-- SECCIÓN SESIÓN --}}
            <div class="border-b-2 pb-6 sm:pb-8 border-gray-200 dark:border-gray-700">
                <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 dark:text-gray-100 mb-4 sm:mb-6 md:mb-8 flex items-center gap-2 sm:gap-3">
                    <x-icon name="calendar" class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 text-blue-600" />
                    Información de la Sesión
                </h3>

                {{-- Título Sesión --}}
                <div x-data="{ max: 255, texto: @entangle('titulo') }" class="relative group mb-6 sm:mb-8">
                    <label class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                        <x-icon name="document-text" class="w-4 h-4 sm:w-5 sm:h-5" /> Título de la Sesión
                    </label>
                    <input type="text" wire:model="titulo" maxlength="255"
                        placeholder="Ej: Sesiones Próximas del 2026"
                        class="block w-full p-3 sm:p-5 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
                    <div class="flex flex-col sm:flex-row sm:justify-between gap-2 mt-2 text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                        <p class="flex items-center gap-1"><x-icon name="information-circle" class="w-4 h-4 text-yellow-500 flex-shrink-0" /> Máximo 255 caracteres</p>
                        <p class="font-semibold text-yellow-500 dark:text-yellow-400"><span x-text="max - texto.length"></span> restantes</p>
                    </div>
                    @error('titulo')
                        <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Descripción Sesión --}}
                <div x-data="{ max: 2000, texto: @entangle('descripcion') }" class="relative group mb-6 sm:mb-8">
                    <label class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                        <x-icon name="chat-bubble-left-right" class="w-4 h-4 sm:w-5 sm:h-5" /> Descripción de la Sesión
                    </label>
                    <textarea wire:model="descripcion" maxlength="2000" rows="5 sm:rows-7"
                        placeholder="Describe detalladamente el contenido y propósito de la sesión..."
                        class="block w-full p-3 sm:p-5 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg"></textarea>
                    <div class="flex flex-col sm:flex-row sm:justify-between gap-2 mt-2 text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                        <p class="flex items-center gap-1"><x-icon name="information-circle" class="w-4 h-4 text-yellow-500 flex-shrink-0" /> Máximo 2000 caracteres</p>
                        <p class="font-semibold text-yellow-500 dark:text-yellow-400"><span x-text="max - texto.length"></span> restantes</p>
                    </div>
                    @error('descripcion')
                        <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Fecha y Estado --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 md:gap-8">
                    <div class="relative group">
                        <label class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                            <x-icon name="calendar" class="w-4 h-4 sm:w-5 sm:h-5" /> Fecha y Hora
                        </label>
                        <input type="datetime-local" wire:model="fecha_hora" 
                            class="block w-full p-3 sm:p-5 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
                        @error('fecha_hora')
                            <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="relative group">
                        <label class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                            <x-icon name="information-circle" class="w-4 h-4 sm:w-5 sm:h-5" /> Estado de la Sesión
                        </label>
                        <select wire:model="estado" 
                            class="block w-full p-3 sm:p-5 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
                            <option value="proxima">Próxima</option>
                            @if($mode === 'edit')
                                <option value="abierta">Abierta</option>
                                <option value="cerrada">Cerrada</option>
                                <option value="completada">Completada</option>
                            @endif
                        </select>
                        @error('estado')
                            <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Botones --}}
            <div class="flex flex-col sm:flex-row justify-center gap-2 sm:gap-4 md:gap-6 pt-6 sm:pt-8">
                @if($mode === 'create')
                    <x-button slate wire:click="limpiar" spinner="limpiar" label="Limpiar" icon="trash"
                        class="inline-flex items-center justify-center gap-1 sm:gap-2 px-4 sm:px-6 md:px-8 py-2 sm:py-3 md:py-4 text-xs sm:text-sm md:text-base bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-lg sm:rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl w-full sm:w-auto" />
                @endif
                <x-button info type="submit" spinner="save" label="{{ $mode === 'create' ? 'Guardar Sesión' : 'Guardar Cambios' }}" icon="check"
                    class="inline-flex items-center justify-center gap-1 sm:gap-2 px-4 sm:px-6 md:px-8 py-2 sm:py-3 md:py-4 text-xs sm:text-sm md:text-base bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-lg sm:rounded-xl shadow-lg transition-all duration-300 hover:scale-105 w-full sm:w-auto" />
            </div>
        </form>
    </div>
</div>