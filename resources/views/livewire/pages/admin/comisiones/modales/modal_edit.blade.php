<x-modal name="editarComisionModal" max-width="8xl" x-on:abrir-editar-comision-modal.window="$openModal('editarComisionModal')">
    <div class="w-full bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-500">
        <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
            <div class="flex items-center gap-2 sm:gap-3">
                <i class="fa-solid fa-pencil animate-bounce text-white w-6 h-6 sm:w-8 sm:h-9"></i>
                <h2 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-extrabold tracking-wide">Editar Comisión</h2>
            </div>
            <button x-on:click="$closeModal('editarComisionModal')" class="text-white hover:text-gray-200 transition">
                <x-icon name="x-mark" class="w-5 h-5 sm:w-7 sm:h-7" />
            </button>
        </div>

        <!-- Mensaje informativo -->
        <div class="p-3 sm:p-4 md:p-6 bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 dark:border-blue-400">
            <p class="text-blue-700 dark:text-blue-200 text-xs sm:text-sm md:text-base font-medium flex items-start gap-2 animate-fadeIn">
                <x-icon name="information-circle" class="w-4 h-4 sm:w-5 sm:h-5 text-blue-500 dark:text-blue-300 flex-shrink-0 mt-0.5" />
                <span>Aquí podrás editar la información de la comisión de manera rápida y segura.</span>
            </p>
        </div>

        <form wire:submit.prevent="updateComision" class="p-4 sm:p-8 space-y-6 sm:space-y-8">
            <!-- Nombre de la Comisión -->
            <div class="relative group">
                <label for="nombre" class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                    <x-icon name="document-text" class="w-4 h-4 sm:w-5 sm:h-5" /> Nombre de la Comisión
                </label>
                <input type="text" id="nombre" wire:model.defer="nombre" placeholder="Ej: Comisión de Finanzas"
                    class="block w-full p-3 sm:p-4 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700
                        border border-gray-300 dark:border-gray-600 rounded-2xl
                        focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300
                        group-hover:scale-[1.02] group-hover:shadow-lg">
                @error('nombre')
                    <p class="mt-2 text-xs sm:text-sm text-red-600 flex items-center gap-1">
                        <x-icon name="exclamation-circle" class="w-4 h-4 flex-shrink-0" /> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Descripción -->
            <div x-data="{ max: 1000, texto: @entangle('descripcion') || '' }" class="relative group">
                <label for="descripcion" class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                    <x-icon name="chat-bubble-left-right" class="w-4 h-4 sm:w-5 sm:h-5" /> Descripción
                </label>
                <textarea id="descripcion" rows="4 sm:rows-6" x-model="texto" maxlength="1000"
                    placeholder="Agrega una breve descripción de la comisión..."
                    class="block w-full p-3 sm:p-5 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700
                        border border-gray-300 dark:border-gray-600 rounded-2xl resize-none
                        focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300
                        group-hover:scale-[1.01] group-hover:shadow-lg"></textarea>

                <div class="flex flex-col sm:flex-row sm:justify-between gap-2 mt-2 text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                    <p class="flex items-center gap-1">
                        <x-icon name="information-circle" class="w-4 h-4 text-yellow-500 flex-shrink-0" />
                        Máximo 1000 caracteres
                    </p>
                    <p class="font-semibold text-yellow-500 dark:text-yellow-400">
                        <span x-text="max - (texto ? texto.length : 0)"></span> restantes
                    </p>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex flex-col sm:flex-row justify-center gap-2 sm:gap-4 pt-4 sm:pt-6 border-t border-gray-200 dark:border-gray-700">
                <!-- Botón Cancelar -->
                <x-button slate x-on:click="close" label="Cancelar" icon="x-mark"
                    class="inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold 
                    rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-gray-400
                    hover:to-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400" />

                <!-- Botón Guardar -->
                <x-button info wire:click="updateComision" spinner="updateComision" label="Guardar Comisión" icon="check"
                    class="inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm bg-gradient-to-r from-blue-600 to-indigo-500 
                    text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 
                    hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400" />
            </div>
        </form>
    </div>
</x-modal>