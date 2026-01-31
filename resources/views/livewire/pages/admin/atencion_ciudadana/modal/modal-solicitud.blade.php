<x-modal name="confirmarModalSolicitud" max-width="4xl" x-on:abrir-confirmar-modal-solicitud.window="$openModal('confirmarModalSolicitud')">
    <div class="w-full bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-500">

        <!-- Modal Header -->
        <div class="flex items-center justify-between p-4 md:p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-500 to-indigo-600 text-white gap-3 md:gap-4">
            <div class="flex items-center gap-2 md:gap-3 min-w-0">
                <i class="fa-solid fa-handshake animate-bounce text-white text-2xl md:text-3xl lg:text-4xl flex-shrink-0"></i>
                <h2 class="text-base md:text-2xl lg:text-3xl font-extrabold tracking-wide truncate">Confirmar Solicitud</h2>
            </div>
            <button x-on:click="$closeModal('confirmarModalSolicitud')" class="text-white hover:text-gray-200 hover:bg-white/10 transition flex-shrink-0 p-2 rounded-lg hover:scale-110">
                <i class="fa-solid fa-x text-lg md:text-xl"></i>
            </button>
        </div>

        <!-- Info Banner -->
        <div class="p-3 md:p-6 bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 dark:border-blue-400">
            <p class="text-blue-700 dark:text-blue-200 text-xs md:text-sm font-medium flex items-start gap-2 animate-fadeIn">
                <x-icon name="information-circle" class="w-4 h-4 md:w-5 md:h-5 text-blue-500 dark:text-blue-300 flex-shrink-0 mt-0.5" />
                <span>Confirma la solicitud del ciudadano, selecciona el estado y envía un correo al solicitante con las observaciones.</span>
            </p>
        </div>

        <!-- Form -->
        <form wire:submit.prevent="confirmar" class="p-4 md:p-8 space-y-4 md:space-y-8">

            <!-- Datos de Contacto -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                <!-- Email Input -->
                <div class="relative group">
                    <label for="email" class="flex items-center gap-2 mb-2 text-sm md:text-base font-semibold text-gray-700 dark:text-gray-300">
                        <x-icon name="envelope" class="w-4 h-4 md:w-5 md:h-5" /> Correo Electrónico
                    </label>
                    <input
                        type="email"
                        id="email"
                        wire:model="email"
                        readonly
                        class="block w-full p-3 md:p-4 text-sm md:text-base text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-600
                               border border-gray-300 dark:border-gray-600 rounded-xl md:rounded-2xl
                               focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300
                               cursor-not-allowed opacity-75">
                </div>

                <!-- WhatsApp Input -->
                <div class="relative group">
                    <label for="whatsapp" class="flex items-center gap-2 mb-2 text-sm md:text-base font-semibold text-gray-700 dark:text-gray-300">
                        <i class="fa-brands fa-whatsapp text-green-500 text-lg md:text-xl"></i> WhatsApp
                    </label>
                    <input
                        type="text"
                        id="whatsapp"
                        wire:model="whatsapp"
                        readonly
                        class="block w-full p-3 md:p-4 text-sm md:text-base text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-600
                               border border-gray-300 dark:border-gray-600 rounded-xl md:rounded-2xl
                               focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300
                               cursor-not-allowed opacity-75">

                    @if(empty($whatsapp))
                        <div class="mt-2 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-300 text-xs md:text-sm font-medium">
                            <i class="fa-solid fa-info-circle"></i>
                            <span>El ciudadano no registró número de WhatsApp</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tipo de Solicitud Input -->
            <div class="relative group">
                <label for="tipo_solicitud" class="flex items-center gap-2 mb-2 text-sm md:text-base font-semibold text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-file-alt text-indigo-500"></i> Tipo de Solicitud
                </label>
                <input
                    type="text"
                    id="tipo_solicitud"
                    wire:model="tipo_solicitud"
                    readonly
                    class="block w-full p-3 md:p-4 text-sm md:text-base text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-600
                           border border-gray-300 dark:border-gray-600 rounded-xl md:rounded-2xl
                           focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300
                           cursor-not-allowed opacity-75">
            </div>

            <!-- Descripción de la Solicitud -->
            <div class="relative group">
                <label for="descripcion" class="flex items-center gap-2 mb-2 text-sm md:text-base font-semibold text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-align-left text-indigo-500"></i> Descripción de la Solicitud
                </label>
                <textarea
                    id="descripcion"
                    wire:model="descripcion"
                    rows="3"
                    readonly
                    class="block w-full p-3 md:p-4 text-sm md:text-base text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-600
                           border border-gray-300 dark:border-gray-600 rounded-xl md:rounded-2xl resize-none
                           focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300
                           cursor-not-allowed opacity-75">
                </textarea>
            </div>

            <!-- Estado Select -->
            <div class="relative group">
                <label for="estado" class="flex items-center gap-2 mb-2 text-sm md:text-base font-semibold text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-flag text-indigo-500"></i> Estado de la Solicitud (Obligatorio)
                </label>
                <select
                    id="estado"
                    wire:model="estado"
                    class="block w-full p-3 md:p-4 text-sm md:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700
                           border-2 border-gray-300 dark:border-gray-600 rounded-xl md:rounded-2xl
                           focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300
                           group-hover:border-indigo-400 dark:group-hover:border-indigo-500
                           appearance-none cursor-pointer font-medium"
                    style="background-image: url('data:image/svg+xml;utf8,<svg fill=\'none\' height=\'24\' stroke=\'%236b7280\' stroke-width=\'2\' viewBox=\'0 0 24 24\' width=\'24\' xmlns=\'http://www.w3.org/2000/svg\'><path d=\'M19 14l-7 7m0 0l-7-7m7 7V3\'></path></svg>'); background-repeat: no-repeat; background-position: right 12px center; background-size: 20px; padding-right: 40px;">
                    <option value="">-- Selecciona un estado --</option>
                    <option value="aprobado">Aprobar</option>
                    <option value="rechazado">Rechazar</option>
                </select>
                @error('estado')
                    <p class="mt-2 text-xs md:text-sm text-red-600 flex items-center gap-1">
                        <x-icon name="exclamation-circle" class="w-4 h-4 flex-shrink-0" /> {{ $message }}
                    </p>
                @enderror

                @if($estado)
                <div class="mt-3 flex gap-2 items-center">
                    @if($estado === 'aprobado')
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 text-xs md:text-sm font-semibold">
                            <i class="fa-solid fa-check-circle"></i> Aprobado
                        </span>
                    @elseif($estado === 'rechazado')
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 text-xs md:text-sm font-semibold">
                            <i class="fa-solid fa-x-circle"></i> Rechazado
                        </span>
                    @endif
                </div>
                @endif
            </div>

            <!-- Respuesta/Observaciones Textarea -->
            <div x-data="{ max: 1000, texto: @entangle('respuesta') || '' }" class="relative group">
                <label for="respuesta" class="flex items-center gap-2 mb-2 text-sm md:text-base font-semibold text-gray-700 dark:text-gray-300">
                    <x-icon name="chat-bubble-left-right" class="w-4 h-4 md:w-5 md:h-5" /> Respuesta/Observaciones (Obligatorio)
                </label>
                <textarea
                    id="respuesta"
                    wire:model="respuesta"
                    rows="4"
                    x-model="texto"
                    maxlength="1000"
                    placeholder="Ingresa la respuesta u observaciones sobre esta solicitud..."
                    class="block w-full p-3 md:p-5 text-sm md:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700
                           border border-gray-300 dark:border-gray-600 rounded-xl md:rounded-2xl resize-none
                           focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300">
                </textarea>
                @error('respuesta')
                    <p class="mt-2 text-xs md:text-sm text-red-600 flex items-center gap-1">
                        <x-icon name="exclamation-circle" class="w-4 h-4 flex-shrink-0" /> {{ $message }}
                    </p>
                @enderror
                <div class="flex flex-col md:flex-row md:justify-between gap-2 mt-2 text-xs md:text-sm text-gray-500 dark:text-gray-400">
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
            <div class="flex flex-col md:flex-row justify-center gap-2 md:gap-4 pt-4 md:pt-6 border-t border-gray-200 dark:border-gray-700">
                <button
                    x-on:click="$closeModal('confirmarModalSolicitud')"
                    type="button"
                    class="inline-flex items-center justify-center gap-2 px-4 md:px-6 py-2 md:py-3 text-xs md:text-sm bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold
                           rounded-lg md:rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-gray-400
                           hover:to-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 w-full md:w-auto">
                    <x-icon name="x-mark" class="w-4 h-4 md:w-5 md:h-5" />
                    Cerrar
                </button>
                <button
                    wire:click="confirmar"
                    type="button"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center justify-center gap-2 px-4 md:px-6 py-2 md:py-3 text-xs md:text-sm bg-gradient-to-r from-blue-600 to-indigo-500
                           text-white font-semibold rounded-lg md:rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105
                           hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400
                           disabled:opacity-50 disabled:cursor-not-allowed w-full md:w-auto">
                    <x-icon name="check" class="w-4 h-4 md:w-5 md:h-5" />
                    <span wire:loading.remove>Confirmar y Enviar</span>
                    <span wire:loading>Enviando...</span>
                </button>
            </div>
        </form>
    </div>
</x-modal>
