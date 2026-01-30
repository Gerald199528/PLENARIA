<div class="w-full min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900 p-4">
    <div class="w-full max-w-7xl bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-500">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-700 to-indigo-900 text-white gap-4">
            <div class="flex items-center gap-2 sm:gap-3">
                <x-icon name="document-plus" class="w-6 h-6 sm:w-8 sm:h-8 text-white animate-bounce" />
                <h2 class="text-lg sm:text-2xl md:text-3xl lg:text-4xl font-extrabold tracking-wide">
                    {{ $mode === 'create' ? 'Registrar Sesión Especial' : 'Editar Sesión Especial' }}
                </h2>
            </div>
            <x-button slate wire:click="cancel" spinner="cancel" label="Volver" icon="arrow-left"
                class="inline-flex items-center gap-1 sm:gap-2 px-3 sm:px-4 md:px-6 py-1.5 sm:py-2 md:py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-lg sm:rounded-xl shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-2xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 text-xs sm:text-sm md:text-base w-full sm:w-auto" />
        </div>

        {{-- Formulario --}}
        <form wire:submit.prevent="save" enctype="multipart/form-data" class="p-4 sm:p-8 space-y-6">

            {{-- Fila 1: Nombre y Fecha --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                {{-- Nombre --}}
                <div class="relative group">
                    <label class="block text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                        <x-icon name="document-text" class="w-4 h-4 sm:w-5 sm:h-5" /> Nombre del Acta
                    </label>
                    <input 
                        type="text" 
                        wire:model="nombre" 
                        placeholder="Ejemplo: Sesión Especial Nº 1"
                        class="w-full p-3 sm:p-4 text-sm sm:text-base rounded-2xl bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                    >
                </div>

                {{-- Fecha de Sesión --}}
                <div class="relative group">
                    <label class="block text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                        <x-icon name="calendar" class="w-4 h-4 sm:w-5 sm:h-5" /> Fecha de Sesión
                    </label>
                    <input 
                        type="datetime-local" 
                        wire:model="fecha_sesion"
                        class="w-full p-3 sm:p-4 text-sm sm:text-base rounded-2xl bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 outline-none transition-all" >
                </div>
            </div>

            {{-- Fila 2: Orador de Orden --}}
            <div class="relative group">
                <label class="block text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                    <x-icon name="user" class="w-4 h-4 sm:w-5 sm:h-5" /> Orador de Orden
                </label>
                <input 
                    type="text" 
                    wire:model="orador_de_orden" 
                    placeholder="Nombre del orador"
                    class="w-full p-3 sm:p-4 text-sm sm:text-base rounded-2xl bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                >
            </div>

            {{-- Fila 3: PDF y Botón Ver --}}
            <div class="relative group">
                <label class="block text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                    <x-icon name="paper-clip" class="w-4 h-4 sm:w-5 sm:h-5" /> {{ $mode === 'create' ? 'Importar acta (pdf)' : 'Actualizar acta (pdf)' }}
                </label>        
                <div class="flex flex-col sm:flex-row items-stretch sm:items-end gap-2 sm:gap-3">
                    <div class="flex-1">
                        <input 
                            type="file"  
                            wire:model="ruta"  
                            accept=".pdf"  
                            class="w-full p-2 sm:p-3 text-xs sm:text-sm rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 cursor-pointer hover:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all" />                  
                    </div>

                    {{-- Botón Ver PDF --}}
                    @if ($mode === 'edit' && isset($sesionEspecial) && $sesionEspecial->ruta)
                        <button type="button"
                            onclick="window.open('{{ asset('storage/' . $sesionEspecial->ruta) }}', '_blank')"
                            class="flex-shrink-0 px-4 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl shadow hover:scale-105 transition-transform flex items-center justify-center gap-2 whitespace-nowrap font-semibold">
                            <x-icon name="eye" class="w-4 h-4 animate-pulse" /> Ver PDF
                        </button>
                    @endif   
                </div>

                {{-- Indicador de carga --}}
                <div wire:loading wire:target="ruta" class="flex items-center justify-center gap-2 sm:gap-3 mt-3 text-blue-600 text-sm sm:text-base">
                    <div class="flex gap-1.5">
                        <div class="w-2 h-2 sm:w-3 sm:h-3 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0s;"></div>
                        <div class="w-2 h-2 sm:w-3 sm:h-3 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
                        <div class="w-2 h-2 sm:w-3 sm:h-3 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.4s;"></div>
                    </div>
                    <span class="text-xs sm:text-sm font-medium">Subiendo archivo...</span>
                </div>

                {{-- Confirmación de archivo cargado --}}
                @if ($ruta)
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
                                    📄 {{ $ruta->getClientOriginalName() }}
                                </p>
                            </div>
                            <button type="button" wire:click="$set('ruta', null)" class="flex-shrink-0 text-green-700 dark:text-green-300 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Fila 4: Botones --}}
            <div class="flex flex-col sm:flex-row justify-center gap-2 sm:gap-6 pt-4 sm:pt-6 border-t border-gray-200 dark:border-gray-700">
                @if($mode == 'create')   
                    <x-button slate wire:click="limpiar" spinner="limpiar" label="Limpiar" icon="trash"
                        class="inline-flex items-center justify-center gap-1 sm:gap-2 px-3 sm:px-6 py-1.5 sm:py-3 text-xs sm:text-sm bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-lg sm:rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-gray-400 hover:to-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 w-full sm:w-auto" />
                @endif                          
                <x-button info type="submit" spinner="save" label="{{ $mode === 'create' ? 'Guardar' : 'Guardar Cambios' }}" icon="check" 
                    class="inline-flex items-center justify-center gap-1 sm:gap-2 px-3 sm:px-6 py-1.5 sm:py-3 text-xs sm:text-sm bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-lg sm:rounded-xl shadow-lg hover:scale-105 transition-all duration-300 w-full sm:w-auto" />
            </div>
        </form>
    </div>
</div>