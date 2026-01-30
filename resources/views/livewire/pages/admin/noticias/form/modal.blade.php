<x-modal name="persistentModal" persistent max-width="7xl">
    <div class="w-full max-w-full sm:max-w-2xl md:max-w-4xl lg:max-w-6xl mx-auto bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl md:rounded-3xl shadow-lg sm:shadow-xl md:shadow-2xl 
    border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-500">
        
        {{-- Header --}}
        <div class="flex items-center justify-between p-3 sm:p-4 md:p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-700 to-indigo-900 text-white gap-2 sm:gap-3 md:gap-4">
            <div class="flex items-center gap-2 sm:gap-2 md:gap-3 min-w-0">
                <i class="fa-solid fa-wand-magic-sparkles animate-bounce text-lg sm:text-xl md:text-2xl text-white flex-shrink-0"></i>
                <h2 class="text-sm sm:text-base md:text-lg lg:text-2xl font-extrabold tracking-wide truncate">Generador de Contenido con IA</h2>
            </div>
           <x-buttonlabel="Cancel"  x-on:click="close" class="text-white hover:text-gray-200 transition flex-shrink-0"/>
                <i class="fa-solid fa-xmark w-5 h-5 sm:w-6 sm:h-6 md:w-7 md:h-7"></i>
         
        </div>

        {{-- Info --}}
        <div class="p-2 sm:p-3 md:p-4 bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500">
            <p class="text-blue-700 dark:text-blue-200 text-xs sm:text-sm md:text-base font-medium flex items-start gap-2 sm:gap-2 md:gap-3">
                <i class="fa-solid fa-lightbulb w-4 h-4 sm:w-4 sm:h-4 md:w-5 md:h-5 text-blue-500 flex-shrink-0 mt-0.5"></i>
                <span><strong>Instrucciones:</strong> Describe el contenido que deseas generar. La IA creará un artículo profesional basado en tu solicitud.</span>
            </p>
        </div>

        {{-- Contenido del formulario --}}
        <div class="p-3 sm:p-4 md:p-6 max-h-[60vh] sm:max-h-[70vh] overflow-y-auto">
            
            {{-- SECCIÓN 1: PROMPT --}}
            <div x-data="{ max: 1000, prompt: @entangle('prompt') }" class="flex flex-col gap-2 sm:gap-3 md:gap-4 mb-4 sm:mb-6 md:mb-8">
                <label class="flex items-center gap-2 sm:gap-2 md:gap-3 text-sm sm:text-base md:text-lg font-semibold text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-pen-fancy text-blue-500 w-4 h-4 sm:w-4 sm:h-4 md:w-5 md:h-5 flex-shrink-0"></i> 
                    Describe tu contenido
                </label>

                <textarea 
                    x-model="prompt"
                    maxlength="1000"
                    rows="4"
                    placeholder="Ej: Escribe un artículo sobre las nuevas políticas de sostenibilidad de nuestra empresa."
                    wire:model="prompt"
                    class="block w-full p-2 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base rounded-lg sm:rounded-xl md:rounded-2xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 hover:shadow-lg">
                </textarea>

                <div class="flex flex-col sm:flex-row sm:justify-between gap-1 text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                    <p class="flex items-center gap-1">
                        <i class="fa-solid fa-info-circle w-3 h-3 text-yellow-500 flex-shrink-0"></i>
                        Máximo 1000 caracteres
                    </p>
                    <p class="font-semibold text-yellow-500 dark:text-yellow-400">
                        <span x-text="max - prompt.length"></span> restantes
                    </p>
                </div>
            </div>

            {{-- BOTÓN GENERAR --}}
            <div class="flex justify-center mb-4 sm:mb-6 md:mb-8">
                <button 
                    type="button"
                    wire:click="generarContenido"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center justify-center gap-1 sm:gap-2 md:gap-3 px-4 sm:px-6 md:px-8 py-2 sm:py-2.5 md:py-3 text-xs sm:text-sm md:text-base font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-lg sm:rounded-xl transition-all duration-300 hover:shadow-lg hover:scale-105 disabled:opacity-50 w-full sm:w-auto">
                    <div wire:loading wire:target="generarContenido">
                        <svg class="animate-spin h-4 w-4 sm:h-5 sm:w-5 md:h-6 md:w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <div wire:loading.remove wire:target="generarContenido">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                    </div>
                    <span class="hidden sm:inline">Generar con IA</span>
                    <span class="sm:hidden">Generar</span>
                </button>
            </div>

            {{-- SECCIÓN 2: CONTENIDO GENERADO --}}
            <div x-data="{ max: 5000, texto: @entangle('contenidoGeneradoTemporal') }" class="flex flex-col gap-2 sm:gap-3 md:gap-4">
                <wire:loading.remove wire:target="generarContenido">
                    @if($contenidoGenerado)
                        <div class="p-2 sm:p-3 md:p-4 bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 rounded-lg">
                            <p class="text-green-700 dark:text-green-200 text-xs sm:text-sm md:text-base font-medium flex items-start gap-2">
                                <i class="fa-solid fa-circle-check w-4 h-4 text-green-500 flex-shrink-0 mt-0.5"></i>
                                <span><strong>Generado:</strong> Revisa y edita si es necesario. Haz clic en "Continuar" para agregar al formulario.</span>
                            </p>
                        </div>

                        <label class="flex items-center gap-2 text-sm sm:text-base md:text-lg font-semibold text-gray-700 dark:text-gray-300">
                            <i class="fa-solid fa-file-lines w-4 h-4 md:w-5 md:h-5"></i> 
                            Contenido Generado
                        </label>

                        <textarea 
                            x-model="texto"
                            maxlength="5000"
                            rows="5"
                            wire:model="contenidoGeneradoTemporal"
                            class="block w-full p-2 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base rounded-lg sm:rounded-xl md:rounded-2xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 hover:shadow-lg">
                        </textarea>

                        <div class="flex flex-col sm:flex-row sm:justify-between gap-1 text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                            <p class="flex items-center gap-1">
                                <i class="fa-solid fa-info-circle w-3 h-3 text-yellow-500 flex-shrink-0"></i>
                                Máximo 5000 caracteres
                            </p>
                            <p class="font-semibold text-yellow-500 dark:text-yellow-400">
                                <span x-text="max - texto.length"></span> restantes
                            </p>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center gap-2 sm:gap-3 p-4 sm:p-6 md:p-8 bg-gray-50 dark:bg-gray-700 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl md:rounded-2xl min-h-48 sm:min-h-56 md:min-h-64 text-center">
                            <i class="fa-solid fa-file-lines text-4xl sm:text-5xl md:text-6xl text-gray-300 dark:text-gray-600"></i>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm md:text-base font-medium">El contenido generado aparecerá aquí</p>
                                <p class="text-gray-400 dark:text-gray-500 text-xs mt-1">Completa tu prompt y genera</p>
                            </div>
                        </div>
                    @endif
                </wire:loading.remove>
            </div>
        </div>

        {{-- Footer --}}
        <div class="flex flex-col sm:flex-row justify-center gap-2 p-3 sm:p-4 md:p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
            
            <button 
                type="button"
                x-on:click="$closeModal('persistentModal')"
                class="inline-flex items-center justify-center gap-1 px-3 sm:px-4 md:px-6 py-2 sm:py-2.5 md:py-3 text-xs sm:text-sm md:text-base font-semibold text-white bg-gradient-to-r from-gray-400 to-gray-500 hover:from-gray-500 hover:to-gray-600 rounded-lg sm:rounded-xl shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-2xl w-full sm:w-auto">
                <i class="fa-solid fa-xmark"></i>
                <span>Cancelar</span>
            </button>

            @if($contenidoGenerado)
                <button 
                    type="button"
                    wire:click="reiniciarModal"
                    class="inline-flex items-center justify-center gap-1 px-3 sm:px-4 md:px-6 py-2 sm:py-2.5 md:py-3 text-xs sm:text-sm md:text-base font-semibold text-white bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 rounded-lg sm:rounded-xl shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-2xl w-full sm:w-auto">
                    <i class="fa-solid fa-rotate-left"></i>
                    <span>Reiniciar</span>
                </button>

                <button 
                    type="button"
                    wire:click="confirmarContenido"
                    wire:loading.attr="disabled"
                    x-on:click="setTimeout(() => $closeModal('persistentModal'), 500)"
                    class="inline-flex items-center justify-center gap-1 px-3 sm:px-4 md:px-6 py-2 sm:py-2.5 md:py-3 text-xs sm:text-sm md:text-base font-semibold text-white bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 rounded-lg sm:rounded-xl shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-2xl disabled:opacity-50 w-full sm:w-auto">
                    <div wire:loading wire:target="confirmarContenido">
                        <svg class="animate-spin h-4 w-4 sm:h-5 sm:w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <div wire:loading.remove wire:target="confirmarContenido">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <span class="hidden sm:inline">Continuar</span>
                
                </button>
            @endif
        </div>
    </div>
</x-modal>