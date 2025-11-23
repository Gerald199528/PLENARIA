<!-- Card principal -->
<div class="w-full max-w-7xl bg-white dark:bg-gray-800 rounded-2xl sm:rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mt-4 sm:mt-6 transition-all duration-500 mx-auto">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-0 p-3 sm:p-4 md:p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
        <div class="flex items-center gap-2 sm:gap-3">
            <x-icon name="tag" class="w-6 h-6 sm:w-8 sm:h-8 text-white animate-bounce" />
            <h2 class="text-lg sm:text-2xl md:text-3xl lg:text-4xl font-extrabold tracking-wide">
                {{ $mode === 'edit' ? 'Editar Categoría' : 'Nueva Categoría' }}
            </h2>
        </div>

        <x-button slate wire:click="cancel" spinner="cancel" label="Volver" icon="arrow-left"
            class="inline-flex items-center gap-1 sm:gap-2 px-3 sm:px-4 md:px-6 py-1.5 sm:py-2 md:py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-lg sm:rounded-xl shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-2xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 text-xs sm:text-sm md:text-base w-full sm:w-auto" />
    </div>

    <!-- Mensaje informativo -->
    <div class="p-3 sm:p-4 md:p-6 bg-indigo-50 dark:bg-indigo-900/30 border-l-4 border-indigo-500 dark:border-indigo-400">
        <div class="flex items-start gap-2 sm:gap-3">
            <x-icon name="information-circle" class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-500 dark:text-indigo-300 flex-shrink-0 mt-0.5 sm:mt-0" />
            <p class="text-sm sm:text-base text-indigo-700 dark:text-indigo-200 font-medium leading-relaxed break-words">
                @if ($mode === 'edit')
                    Aquí podrás <strong>editar el nombre, tipo y observación</strong> de la categoría.
                @else
                    Podrás <strong>agregar nuevas categorías</strong> de instrumentos legales de manera rápida y segura.
                @endif
            </p>
        </div>
    </div>

    <!-- Formulario -->
    <form wire:submit.prevent="save" class="p-4 sm:p-6 md:p-8 space-y-6 sm:space-y-8">

        <!-- Nombre -->
        <div class="relative group">
            <label for="nombre" class="block text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                <x-icon name="document-text" /> Nombre
            </label>
            <input type="text" id="nombre" wire:model.defer="nombre" 
                   @if ($mode === 'create') placeholder="Ej: Acuerdos de declaratoria" @endif
                   class="w-full p-2.5 sm:p-3 md:p-4 rounded-lg sm:rounded-xl md:rounded-2xl bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm sm:text-base transition-all duration-300
                          focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 group-hover:scale-[1.01] group-hover:shadow-lg">
            @error('nombre')
                <p class="mt-2 text-xs sm:text-sm text-red-600 flex items-center gap-1">
                    <x-icon name="exclamation-circle" class="w-4 h-4" /> {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Tipo de Categoría -->
        <div class="relative group">
            <label for="tipo_categoria" class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                <x-icon name="tag" /> Tipo de Categoría
            </label>
            <select id="tipo_categoria" wire:model.defer="tipo_categoria"
                class="block w-full p-2.5 sm:p-3 md:p-4 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700
                       border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl md:rounded-2xl
                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300
                       group-hover:scale-[1.01] group-hover:shadow-lg">
                <option value="">Selecciona un tipo</option>
                <option value="Acuerdos">Acuerdos</option>
                <option value="Ordenanzas">Ordenanzas</option>
            </select>
            @error('tipo_categoria')
                <p class="mt-2 text-xs sm:text-sm text-red-600 flex items-center gap-1">
                    <x-icon name="exclamation-circle" class="w-4 h-4" /> {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Observación -->
        <div x-data="{ max: 1000, texto: @entangle('observacion') }" class="relative group">
            <label for="observacion" class="block text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                <x-icon name="chat-bubble-left-right" /> Observación
            </label>
            <textarea id="observacion" x-model="texto" maxlength="1000" rows="4 sm:rows-5 md:rows-6"
                      class="w-full p-2.5 sm:p-3 md:p-5 rounded-lg sm:rounded-xl md:rounded-2xl bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm sm:text-base resize-none
                             transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg"
                      placeholder="Agrega notas o detalles sobre la categoría..."></textarea>

            <div class="flex flex-col sm:flex-row justify-between mt-2 text-xs sm:text-sm text-gray-500 dark:text-gray-400 gap-1 sm:gap-0">
                <p>Máximo 1000 caracteres</p>
                <p class="font-semibold text-indigo-500 dark:text-indigo-400"><span x-text="max - texto.length"></span> restantes</p>
            </div>

            @error('observacion')
                <p class="mt-2 text-xs sm:text-sm text-red-600 flex items-center gap-1">
                    <x-icon name="exclamation-circle" class="w-4 h-4" /> {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Botones -->
        <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 md:gap-8 pt-4 sm:pt-6 border-t border-gray-200 dark:border-gray-700">
            @if ($mode === 'create')
                <x-button slate wire:click="limpiar" spinner="limpiar" label="Limpiar" icon="trash"
                    class="inline-flex items-center gap-1 sm:gap-2 px-3 sm:px-4 md:px-6 py-1.5 sm:py-2 md:py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-lg sm:rounded-xl shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-2xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 text-xs sm:text-sm md:text-base w-full sm:w-auto" />
            @endif

            <x-button info type="submit" spinner="save" 
                      label="{{ $mode === 'edit' ? 'Guardar Cambios' : 'Guardar Categoría' }}" 
                      icon="check"
                      class="inline-flex items-center gap-1 sm:gap-2 px-3 sm:px-4 md:px-6 py-1.5 sm:py-2 md:py-3 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-lg sm:rounded-xl shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-2xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400 text-xs sm:text-sm md:text-base w-full sm:w-auto" />
        </div>

    </form>
</div>