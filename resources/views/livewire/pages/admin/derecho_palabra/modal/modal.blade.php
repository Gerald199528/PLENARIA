<x-modal name="confirmarModal" max-width="10xl" x-on:abrir-confirmar-modal.window="$openModal('confirmarModal')">
    <div class="w-full bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-500">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-handshake animate-bounce text-white text-4xl"></i>
                <h2 class="text-3xl md:text-4xl font-extrabold tracking-wide">Confirmar Solicitud</h2>
            </div>
            <button x-on:click="$closeModal('confirmarModal')" class="text-white hover:text-gray-200 transition">
                <x-icon name="x-mark" class="w-7 h-7" />
            </button>
        </div>

        <!-- Info Banner -->
        <div class="p-6 bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 dark:border-blue-400">
            <p class="text-blue-700 dark:text-blue-200 text-base font-medium flex items-center gap-2 animate-fadeIn">
                <x-icon name="information-circle" class="w-5 h-5 text-blue-500 dark:text-blue-300" />
                Confirma la solicitud de derecho de palabra y envía un correo al solicitante con las observaciones.
            </p>
        </div>

        <!-- Form -->
        <form wire:submit.prevent="confirmar" class="p-8 space-y-8">
            <!-- Email Input -->
            <div class="relative group">
                <label for="email" class="flex items-center gap-2 mb-2 text-base font-semibold text-gray-700 dark:text-gray-300">
                    <x-icon name="envelope" /> Correo Electrónico
                </label>
                <input 
                    type="email" 
                    id="email" 
                    wire:model="email"
                    readonly
                    class="block w-full p-4 text-base text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-600
                           border border-gray-300 dark:border-gray-600 rounded-2xl
                           focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300
                           cursor-not-allowed opacity-75">
            </div>

            <!-- Observaciones Textarea -->
            <div x-data="{ max: 1000, texto: @entangle('observaciones') || '' }" class="relative group">
                <label for="observaciones" class="flex items-center gap-2 mb-2 text-base font-semibold text-gray-700 dark:text-gray-300">
                    <x-icon name="chat-bubble-left-right" /> Observaciones (Obligatorio)
                </label>
                <textarea 
                    id="observaciones" 
                    wire:model="observaciones"
                    rows="6"
                    x-model="texto"
                    maxlength="1000"
                    placeholder="Ingresa observaciones sobre esta solicitud..."
                    class="block w-full p-5 text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700
                           border border-gray-300 dark:border-gray-600 rounded-2xl resize-none
                           focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300
                           group-hover:scale-[1.01] group-hover:shadow-lg">
                </textarea>
                @error('observaciones')
                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                        <x-icon name="exclamation-circle" class="w-4 h-4" /> {{ $message }}
                    </p>
                @enderror
                <div class="flex justify-between mt-2 text-sm text-gray-500 dark:text-gray-400">
                    <p class="flex items-center gap-1">
                        <x-icon name="information-circle" class="w-4 h-4 text-yellow-500" />
                        Máximo 1000 caracteres
                    </p>
                    <p class="font-semibold text-yellow-500 dark:text-yellow-400">
                        <span x-text="max - (texto ? texto.length : 0)"></span> restantes
                    </p>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-center gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                <button 
                    x-on:click="$closeModal('confirmarModal')"
                    type="button"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold 
                           rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-gray-400
                           hover:to-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                    <x-icon name="x-mark" class="w-5 h-5" />
                    Cancelar
                </button>
                <button 
                    wire:click="confirmar"
                    type="button"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-500 
                           text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 
                           hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400
                           disabled:opacity-50 disabled:cursor-not-allowed">
                    <x-icon name="check" class="w-5 h-5" />
                    <span wire:loading.remove>Confirmar y Enviar</span>
                    <span wire:loading>Enviando...</span>
                </button>
            </div>
        </form>
    </div>
</x-modal>