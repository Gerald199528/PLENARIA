<div class="w-full min-h-screen bg-gray-100 dark:bg-gray-900 p-4 py-6 sm:py-8">
        <div class="w-full max-w-6xl mx-auto bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 sm:p-6 md:p-8 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-700 to-indigo-900 text-white gap-4">
                <div class="flex items-center gap-2 sm:gap-3 md:gap-4">
                    <i class="fa-solid {{ $mode === 'create' ? 'fa-plus-circle' : 'fa-pencil-square' }} text-2xl sm:text-3xl md:text-4xl animate-bounce"></i>
                    <h2 class="text-lg sm:text-2xl md:text-3xl lg:text-4xl font-extrabold tracking-wide">
                        {{ $mode === 'create' ? 'Crear Tipo de Solicitud' : 'Editar Tipo de Solicitud' }}
                    </h2>
                </div>
                <button wire:click="cancel" class="inline-flex items-center gap-1 sm:gap-2 px-3 sm:px-4 md:px-6 py-1.5 sm:py-2 md:py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-lg sm:rounded-xl shadow-lg transition-all duration-300 hover:scale-105 text-xs sm:text-sm md:text-base w-full sm:w-auto justify-center">
                    <i class="fa-solid fa-arrow-left"></i> Volver
                </button>
            </div>

            {{-- Formulario --}}
            <form wire:submit.prevent="save" class="p-4 sm:p-6 md:p-10 space-y-6 sm:space-y-8">

                {{-- Nombre --}}
                <div class="relative group">
                    <label class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                        <i class="fa-solid fa-pen"></i> Nombre del Tipo de Solicitud
                    </label>
                    <input type="text" wire:model="nombre" maxlength="100"
                        placeholder="Ej: Servicios Públicos, Educación, Salud, Seguridad..."
                        class="block w-full p-3 sm:p-5 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
                    @error('nombre')
                        <span class="text-red-500 text-xs sm:text-sm mt-2 block flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </span>
                    @enderror
                </div>

                {{-- Descripción --}}
                <div x-data="{ max: 1000, texto: @entangle('descripcion') }" class="relative group">
                    <label class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                        <i class="fa-solid fa-file-lines"></i> Descripción
                    </label>
                    <textarea wire:model="descripcion" maxlength="1000" rows="5"
                        placeholder="Describe el propósito y alcance de este tipo de solicitud..."
                        class="block w-full p-3 sm:p-5 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg"></textarea>
                    <div class="flex flex-col sm:flex-row sm:justify-between gap-2 mt-2 text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                        <p class="flex items-center gap-1"><i class="fa-solid fa-circle-info text-yellow-500"></i> Mínimo 20 caracteres, máximo 1000</p>
                        <p class="font-semibold text-yellow-500"><span x-text="max - texto.length"></span> restantes</p>
                    </div>
                    @error('descripcion')
                        <span class="text-red-500 text-xs sm:text-sm mt-2 block flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </span>
                    @enderror
                </div>

                {{-- Botones --}}
                <div class="flex flex-col sm:flex-row justify-center gap-2 sm:gap-4 md:gap-6 pt-6 sm:pt-8 border-t-2 border-gray-200 dark:border-gray-700">
                    @if($mode === 'create')
                        <button type="button" wire:click="limpiar" class="inline-flex items-center justify-center gap-1 sm:gap-2 px-4 sm:px-6 md:px-8 py-2 sm:py-3 md:py-4 text-xs sm:text-sm md:text-base bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-lg sm:rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl w-full sm:w-auto">
                            <i class="fa-solid fa-trash-can"></i> Limpiar
                        </button>
                    @endif
                    <button type="submit" class="inline-flex items-center justify-center gap-1 sm:gap-2 px-4 sm:px-6 md:px-8 py-2 sm:py-3 md:py-4 text-xs sm:text-sm md:text-base bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-lg sm:rounded-xl shadow-lg transition-all duration-300 hover:scale-105 w-full sm:w-auto">
                        <i class="fa-solid fa-check-circle"></i> {{ $mode === 'create' ? 'Guardar Tipo de Solicitud' : 'Guardar Cambios' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
