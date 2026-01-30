<div class="w-full min-h-screen bg-gray-100 dark:bg-gray-900 p-4 py-6 sm:py-8">
    <div class="w-full max-w-6xl mx-auto bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 sm:p-6 md:p-8 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-700 to-indigo-900 text-white gap-4">
            <div class="flex items-center gap-2 sm:gap-3 md:gap-4">
                <x-icon name="{{ $mode === 'create' ? 'document-plus' : 'pencil-square' }}" class="w-7 h-7 sm:w-8 sm:h-8 md:w-10 md:h-10 text-white animate-bounce" />
                <h2 class="text-lg sm:text-2xl md:text-3xl lg:text-4xl font-extrabold tracking-wide">
                    {{ $mode === 'create' ? 'Registrar Categoría' : 'Editar Categoría' }}
                </h2>
            </div>
            <x-button slate wire:click="cancel" spinner="cancel" label="Volver" icon="arrow-left"
                class="inline-flex items-center gap-1 sm:gap-2 px-3 sm:px-4 md:px-6 py-1.5 sm:py-2 md:py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-lg sm:rounded-xl shadow-lg transition-all duration-300 hover:scale-105 text-xs sm:text-sm md:text-base w-full sm:w-auto justify-center" />
        </div>

        {{-- Formulario --}}
        <form wire:submit.prevent="save" class="p-4 sm:p-6 md:p-10 space-y-6 sm:space-y-8 md:space-y-10">

            {{-- Nombre Categoría --}}
            <div class="relative group">
                <label class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                    <x-icon name="tag" class="w-4 h-4 sm:w-5 sm:h-5" /> Nombre de la Categoría
                </label>
                <input type="text" wire:model="nombre" maxlength="255"
                    placeholder="Ej: Servicios Públicos"
                    class="block w-full p-3 sm:p-5 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
                @error('nombre')
                    <span class="text-red-500 text-xs sm:text-sm mt-2 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Descripción Categoría --}}
            <div x-data="{ max: 1000, texto: @entangle('descripcion') }" class="relative group mb-6 sm:mb-8">
                <label class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                    <x-icon name="document-text" class="w-4 h-4 sm:w-5 sm:h-5" /> Descripción de la Categoría
                </label>
                <textarea wire:model="descripcion" maxlength="1000" rows="4 sm:rows-6"
                    placeholder="Describe esta categoría..."
                    class="block w-full p-3 sm:p-5 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg"></textarea>
                <div class="flex flex-col sm:flex-row sm:justify-between gap-2 mt-2 text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                    <p class="flex items-center gap-1"><x-icon name="information-circle" class="w-4 h-4 text-yellow-500 flex-shrink-0" /> Máximo 1000 caracteres</p>
                    <p class="font-semibold text-yellow-500 dark:text-yellow-400"><span x-text="max - texto.length"></span> restantes</p>
                </div>
                @error('descripcion')
                    <span class="text-red-500 text-xs sm:text-sm mt-2 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Botones --}}
            <div class="flex flex-col sm:flex-row justify-center gap-2 sm:gap-4 md:gap-6 pt-6 sm:pt-8 border-t-2 border-gray-200 dark:border-gray-700">
                @if($mode === 'create')
                    <x-button slate wire:click="limpiar" spinner="limpiar" label="Limpiar" icon="trash"
                        class="inline-flex items-center justify-center gap-1 sm:gap-2 px-4 sm:px-6 md:px-8 py-2 sm:py-3 md:py-4 text-xs sm:text-sm md:text-base bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-lg sm:rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl w-full sm:w-auto" />
                @endif
                <x-button info type="submit" spinner="save" label="{{ $mode === 'create' ? 'Guardar Categoría' : 'Guardar Cambios' }}" icon="check"
                    class="inline-flex items-center justify-center gap-1 sm:gap-2 px-4 sm:px-6 md:px-8 py-2 sm:py-3 md:py-4 text-xs sm:text-sm md:text-base bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-lg sm:rounded-xl shadow-lg transition-all duration-300 hover:scale-105 w-full sm:w-auto" />
            </div>
        </form>
    </div>
</div>
