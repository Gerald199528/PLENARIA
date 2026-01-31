<x-modal name="confirmarModal" max-width="10xl" x-on:abrir-confirmar-modal.window="$openModal('confirmarModal')">
    <div class="w-full bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-500">
        <!-- Modal Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-500 to-indigo-600 text-white gap-4">
            <div class="flex items-center gap-2 sm:gap-3">
                <i class="fa-solid fa-handshake animate-bounce text-white text-2xl sm:text-3xl md:text-4xl"></i>
                <h2 class="text-lg sm:text-2xl md:text-3xl lg:text-4xl font-extrabold tracking-wide">Confirmar Solicitud</h2>
            </div>
        <button x-on:click="$closeModal('confirmarModal')" class="text-white hover:text-gray-200 hover:bg-white/10 transition flex-shrink-0 p-2 rounded-lg hover:scale-110">
                <i class="fa-solid fa-x text-lg sm:text-xl"></i>
            </button>
        </div>

        <!-- Info Banner -->
        <div class="p-3 sm:p-4 md:p-6 bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 dark:border-blue-400">
            <p class="text-blue-700 dark:text-blue-200 text-xs sm:text-sm md:text-base font-medium flex items-start gap-2 animate-fadeIn">
                <x-icon name="information-circle" class="w-4 h-4 sm:w-5 sm:h-5 text-blue-500 dark:text-blue-300 flex-shrink-0 mt-0.5" />
                <span>Confirma la solicitud de derecho de palabra, selecciona el estado y se enviará un mensaje por WhatsApp y correo al solicitante.</span>
            </p>
        </div>

        <!-- Form -->
        <form wire:submit.prevent="confirmar" class="p-4 sm:p-6 md:p-8 space-y-4 sm:space-y-6 md:space-y-8">

            <!-- Datos de Contacto -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-2xl border border-gray-200 dark:border-gray-600">
                <!-- Email Input -->
                <div class="relative group">
                    <label for="email" class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                        <x-icon name="envelope" class="w-4 h-4 sm:w-5 sm:h-5" /> Correo Electrónico
                    </label>
                    <input
                        type="email"
                        id="email"
                        wire:model="email"
                        readonly
                        class="block w-full p-2 sm:p-4 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-600
                               border border-gray-300 dark:border-gray-600 rounded-2xl
                               focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300
                               cursor-not-allowed opacity-75">
                </div>

                <!-- WhatsApp Input -->
                <div class="relative group">
                    <label for="whatsapp" class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                        <i class="fa-brands fa-whatsapp text-green-500"></i> WhatsApp
                    </label>
                    <input
                        type="tel"
                        id="whatsapp"
                        wire:model="whatsapp"
                        readonly
                        class="block w-full p-2 sm:p-4 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-600
                               border border-gray-300 dark:border-gray-600 rounded-2xl
                               focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300
                               cursor-not-allowed opacity-75">
                </div>
            </div>

            <!-- Comisión Input -->
            <div class="relative group">
                <label for="comision" class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-briefcase text-indigo-500"></i> Comisión Solicitada
                </label>
                <input
                    type="text"
                    id="comision"
                    wire:model="comision"
                    readonly
                    class="block w-full p-2 sm:p-4 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-600
                           border border-gray-300 dark:border-gray-600 rounded-2xl
                           focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300
                           cursor-not-allowed opacity-75">
                </input>
                <!-- Mensaje si no hay comisión -->
                @if(empty($comision))
                    <div class="mt-2 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-300 text-xs sm:text-sm font-medium">
                        <i class="fa-solid fa-info-circle"></i>
                        <span>El ciudadano no solicitó comisión específica</span>
                    </div>
                @endif
            </div>

            <!-- Estado Select -->
            <div class="relative group">
                <label for="estado" class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-flag text-indigo-500"></i> Estado de la Solicitud (Obligatorio)
                </label>
                <select
                    id="estado"
                    wire:model="estado"
                    class="block w-full p-3 sm:p-4 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700
                           border-2 border-gray-300 dark:border-gray-600 rounded-2xl
                           focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300
                           group-hover:border-indigo-400 dark:group-hover:border-indigo-500
                           appearance-none cursor-pointer font-medium"
                    style="background-image: url('data:image/svg+xml;utf8,<svg fill=\\'none\\' height=\\'24\\' stroke=\\'%236b7280\\' stroke-width=\\'2\\' viewBox=\\'0 0 24 24\\' width=\\'24\\' xmlns=\\'http://www.w3.org/2000/svg\\'><path d=\\'M19 14l-7 7m0 0l-7-7m7 7V3\\'></path></svg>'); background-repeat: no-repeat; background-position: right 12px center; background-size: 20px; padding-right: 40px;"
                    style="color: #6b7280;">
                    <option value="">-- Selecciona un estado --</option>

                    <option value="aprobada">
                        <span style="color: #10b981;">✓</span> Aprobar
                    </option>
                    <option value="rechazada">
                        <span style="color: #ef4444;">✕</span> Rechazar
                    </option>
                </select>
                @error('estado')
                    <p class="mt-2 text-xs sm:text-sm text-red-600 flex items-center gap-1">
                        <x-icon name="exclamation-circle" class="w-4 h-4 flex-shrink-0" /> {{ $message }}
                    </p>
                @enderror

                <!-- Estado Badge (visual feedback) -->
                @if($estado)
                <div class="mt-3 flex gap-2 items-center">
                    @if($estado === 'aprobada')
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 text-xs sm:text-sm font-semibold">
                            <i class="fa-solid fa-check-circle"></i> Aprobada
                        </span>
                    @elseif($estado === 'rechazada')
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 text-xs sm:text-sm font-semibold">
                            <i class="fa-solid fa-x-circle"></i> Rechazada
                        </span>
                    @endif
                </div>
                @endif
            </div>

            <!-- Observaciones Textarea -->
            <div x-data="{ max: 1000, texto: @entangle('observaciones') || '' }" class="relative group">
                <label for="observaciones" class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                    <x-icon name="chat-bubble-left-right" class="w-4 h-4 sm:w-5 sm:h-5" /> Observaciones (Obligatorio)
                </label>
                <textarea
                    id="observaciones"
                    wire:model="observaciones"
                    rows="4"
                    x-model="texto"
                    maxlength="1000"
                    placeholder="Ingresa observaciones sobre esta solicitud..."
                    class="block w-full p-3 sm:p-5 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700
                           border border-gray-300 dark:border-gray-600 rounded-2xl resize-none
                           focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300
                           group-hover:scale-[1.01] group-hover:shadow-lg">
                </textarea>
                @error('observaciones')
                    <p class="mt-2 text-xs sm:text-sm text-red-600 flex items-center gap-1">
                        <x-icon name="exclamation-circle" class="w-4 h-4 flex-shrink-0" /> {{ $message }}
                    </p>
                @enderror
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

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row justify-center gap-2 sm:gap-4 pt-4 sm:pt-6 border-t border-gray-200 dark:border-gray-700">
                <button
                    x-on:click="$closeModal('confirmarModal')"
                    type="button"
                    class="inline-flex items-center justify-center gap-1 sm:gap-2 px-4 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold
                           rounded-lg sm:rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-gray-400
                           hover:to-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 w-full sm:w-auto">
                    <x-icon name="x-mark" class="w-4 h-4 sm:w-5 sm:h-5" />
                    Cerrar
                </button>
                <button
                    wire:click="confirmar"
                    type="button"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center justify-center gap-1 sm:gap-2 px-4 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm bg-gradient-to-r from-blue-600 to-indigo-500
                           text-white font-semibold rounded-lg sm:rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105
                           hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400
                           disabled:opacity-50 disabled:cursor-not-allowed w-full sm:w-auto">
                    <x-icon name="check" class="w-4 h-4 sm:w-5 sm:h-5" />
                    <span wire:loading.remove>Confirmar y Enviar</span>
                    <span wire:loading>Enviando...</span>
                </button>
            </div>
        </form>
    </div>
</x-modal>
