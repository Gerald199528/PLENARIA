<div class="bg-gray-100 dark:bg-gray-900 min-h-screen flex items-center justify-center py-10 px-4">
    <div class="w-full max-w-7xl bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-500">

        <!-- Encabezado -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-700 to-indigo-900 text-white gap-4">
            <div class="flex items-center gap-2 sm:gap-3">
                <i class="fa-solid fa-book-open animate-bounce text-2xl sm:text-3xl text-white"></i>
                <h2 class="text-lg sm:text-2xl md:text-3xl lg:text-4xl font-extrabold tracking-wide">
                    {{ $mode === 'edit' ? 'Editar Crónica' : 'Registrar Crónica' }}
                </h2>
            </div>
            <x-button slate wire:click="cancel" spinner="cancel" label="Volver" icon="arrow-left"
                class="inline-flex items-center gap-1 sm:gap-2 px-3 sm:px-4 md:px-6 py-1.5 sm:py-2 md:py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-lg sm:rounded-xl shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-2xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 text-xs sm:text-sm md:text-base w-full sm:w-auto justify-center" />
        </div>

        <!-- Formulario -->
        <form class="p-4 sm:p-6 md:p-8 space-y-4 sm:space-y-6" wire:submit.prevent="save">

            <!-- Nombre del cronista como input bloqueado -->
            <div class="relative group">
                <label for="cronista_nombre" class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                    <x-icon name="user" class="w-4 h-4 sm:w-5 sm:h-5" /> Nombre del Cronista
                </label>
                <input 
                    type="text" 
                    id="cronista_nombre" 
                    wire:model="cronista_nombre"
                    placeholder="Escribe el nombre del cronista"
                    disabled
                    class="block w-full p-2 sm:p-4 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 cursor-not-allowed">
                
                <p class="mt-1 text-xs sm:text-sm text-yellow-600 dark:text-yellow-400 flex items-center gap-1">
                    <x-icon name="lock-closed" class="w-4 h-4" /> Campo bloqueado
                </p>
            </div>

            <!-- Título -->
            <div class="relative group">
                <label for="titulo" class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                    <x-icon name="pencil" class="w-4 h-4 sm:w-5 sm:h-5" /> Título de la Crónica
                </label>
                <input type="text" id="titulo" wire:model="titulo" placeholder="Escribe el título de la crónica"
                    class="block w-full p-2 sm:p-4 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300">
            </div>

            <!-- Categoría -->
            <div class="relative group">
                <label for="categoria_id" class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                    <x-icon name="tag" class="w-4 h-4 sm:w-5 sm:h-5" /> Seleccione Categoría
                </label>
                <select id="categoria_id" wire:model="categoria_id"
                    class="block w-full p-2 sm:p-4 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300">
                    <option value="">Seleccione una categoría</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Fecha de publicación -->
            <div class="relative group">
                <label for="fecha_publicacion" class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                    <x-icon name="calendar" class="w-4 h-4 sm:w-5 sm:h-5" /> Fecha de Publicación:
                </label>
                <input type="date" id="fecha_publicacion" wire:model="fecha_publicacion"
                    class="block w-full p-2 sm:p-4 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300">
            </div>
<!-- Archivo PDF -->
<div class="relative group">
    <label for="archivo_pdf" class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
        <x-icon name="document" class="w-4 h-4 sm:w-5 sm:h-5" /> Archivo PDF (Requerido.)
    </label>
    <div class="flex flex-col sm:flex-row items-stretch sm:items-end gap-2 sm:gap-3">
        <div class="flex-1">
            <input type="file" id="archivo_pdf" wire:model="archivo_pdf" accept=".pdf"
                class="block w-full text-xs sm:text-sm text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl p-2 sm:p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300">
        </div>
        
        {{-- Botón Ver PDF --}}
        @if ($mode === 'edit' && isset($cronica) && $cronica->archivo_pdf)
            <button type="button"
                onclick="window.open('{{ asset('storage/' . $cronica->archivo_pdf) }}', '_blank')"
                class="flex-shrink-0 px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-xs sm:text-sm bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl shadow hover:scale-105 transition-transform flex items-center justify-center gap-1 sm:gap-2 whitespace-nowrap font-semibold">
                <x-icon name="eye" class="w-4 h-4 animate-pulse" /> 
                <span class="hidden sm:inline">Ver PDF</span>
                <span class="sm:hidden">Ver</span>
            </button>
        @endif
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
        <div class="mt-4 p-3 sm:p-5 bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-800 dark:to-emerald-800 border-2 border-green-400 dark:border-green-600 rounded-xl shadow-md animate-pulse">
            <div class="flex items-center gap-3 sm:gap-4">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-green-700 dark:text-green-300 animate-bounce" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-green-900 dark:text-green-100 text-sm sm:text-base font-bold">
                        Archivo cargado correctamente
                    </p>
                    <p class="text-green-800 dark:text-green-200 text-xs sm:text-sm mt-1 truncate">
                        📄 {{ $archivo_pdf->getClientOriginalName() }}
                    </p>
                </div>
                <button type="button" wire:click="$set('archivo_pdf', null)" class="flex-shrink-0 text-green-700 dark:text-green-300 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif
</div>
            <!-- Contenido de la Crónica -->
            <div x-data="{ max: 5000, texto: @entangle('contenido') }" class="relative group">
                <label for="contenido" class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                    <x-icon name="document-text" class="w-4 h-4 sm:w-5 sm:h-5" /> Contenido de la Crónica
                </label>
                <textarea id="contenido" rows="4 sm:rows-6" x-model="texto" maxlength="5000"
                    class="block w-full p-3 sm:p-5 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg"
                    placeholder="Escribe el contenido completo de la crónica..."></textarea>
                <div class="flex flex-col sm:flex-row sm:justify-between gap-2 mt-2 text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                    <p class="flex items-center gap-1"><x-icon name="information-circle" class="w-4 h-4 text-yellow-500 flex-shrink-0" /> Máximo 5000 caracteres</p>
                    <p class="font-semibold text-yellow-500 dark:text-yellow-400"><span x-text="max - texto.length"></span> restantes</p>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex flex-col sm:flex-row justify-center gap-2 sm:gap-4 md:gap-8 pt-4 sm:pt-6 border-t border-gray-200 dark:border-gray-700">
                @if($mode == 'create')   
                    <x-button slate wire:click="limpiar" spinner="limpiar" label="Limpiar" icon="trash"
                        class="inline-flex items-center justify-center gap-1 sm:gap-2 px-3 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-lg sm:rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-gray-400 hover:to-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 w-full sm:w-auto" />
                @endif
                <x-button info type="submit" label="{{ $mode === 'edit' ? 'Actualizar Crónica' : 'Guardar Crónica' }}"
                    icon="check" interaction="positive" spinner="save"
                    class="inline-flex items-center justify-center gap-1 sm:gap-2 px-3 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-lg sm:rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400 w-full sm:w-auto" />
            </div>
        </form>
    </div>
</div>