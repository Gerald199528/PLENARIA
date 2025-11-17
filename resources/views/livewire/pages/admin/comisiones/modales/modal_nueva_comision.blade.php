        <!-- Modal Nueva Comisión -->
        <x-modal name="comisionModal" max-width="7xl">
            <div class="w-full bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-500">

                <!-- Encabezado -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
                    <div class="flex items-center gap-3">
                        <!-- Icono Handshake animado -->
                        <i class="fa-solid fa-handshake animate-bounce text-white w-8 h-9"></i>
                        <h2 class="text-3xl md:text-4xl font-extrabold tracking-wide">Nueva Comisión</h2>
                    </div>

                    <!-- Botón Cerrar -->
                    <button x-on:click="close" class="text-white hover:text-gray-200 transition">
                        <x-icon name="x-mark" class="w-7 h-7" />
                    </button>
                </div>

                <!-- Mensaje informativo -->
                <div class="p-6 bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 dark:border-blue-400">
                    <p class="text-blue-700 dark:text-blue-200 text-base font-medium flex items-center gap-2 animate-fadeIn">
                        <x-icon name="information-circle" class="w-5 h-5 text-blue-500 dark:text-blue-300" />
                        Aquí podrás <strong>crear nuevas comisiones</strong> de manera rápida y segura.
                    </p>
                </div>

                <!-- Formulario -->
                <form wire:submit.prevent="saveComision" class="p-8 space-y-8">

                    <!-- Nombre de la Comisión -->
                    <div class="relative group">
                        <label for="nombre" class="flex items-center gap-2 mb-2 text-base font-semibold text-gray-700 dark:text-gray-300">
                            <x-icon name="document-text" /> Nombre de la Comisión
                        </label>
                        <input type="text" id="nombre" wire:model="nombre" placeholder="Ej: Comisión de Finanzas"
                            class="block w-full p-4 text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700
                                border border-gray-300 dark:border-gray-600 rounded-2xl
                                focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300
                                group-hover:scale-[1.02] group-hover:shadow-lg">
                 
                    </div>

                    <!-- Descripción -->
                    <div x-data="{ max: 1000, texto: @entangle('descripcion') }" class="relative group">
                        <label for="descripcion" class="flex items-center gap-2 mb-2 text-base font-semibold text-gray-700 dark:text-gray-300">
                            <x-icon name="chat-bubble-left-right" /> Descripción
                        </label>

                        <textarea id="descripcion" rows="6" x-model="texto" maxlength="1000"
                            placeholder="Agrega una breve descripción de la comisión..."
                            class="block w-full p-5 text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700
                                border border-gray-300 dark:border-gray-600 rounded-2xl resize-none
                                focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300
                                group-hover:scale-[1.01] group-hover:shadow-lg"></textarea>

                        <div class="flex justify-between mt-2 text-sm text-gray-500 dark:text-gray-400">
                            <p class="flex items-center gap-1">
                                <x-icon name="information-circle" class="w-4 h-4 text-yellow-500" />
                                Máximo 1000 caracteres
                            </p>
                            <p class="font-semibold text-yellow-500 dark:text-yellow-400">
                                <span x-text="max - texto.length"></span> restantes
                            </p>
                        </div>

                        @error('descripcion')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <x-icon name="exclamation-circle" class="w-4 h-4" /> {{ $message }}
                            </p>
                        @enderror
                    </div>

                                                  <!-- Botones -->
                    <div class="flex justify-center gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">              
                                              <!-- Botón Cancelar -->
                <x-button slate x-on:click="close" label="Cancelar" icon="x-mark"
                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold 
                rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-gray-400
                 hover:to-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400" />
                                                 <!-- BotónLimpiar -->
               <x-button slate wire:click="limpiar" label="Limpiar" icon="arrow-path"
        class="inline-flex items-center gap-2 px-6 py-3 bg-gray-500 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400" />
                                               <!-- Botón Guardar -->
        <x-button info wire:click="saveComision" spinner="saveComision" label="Guardar Comisión" icon="check"
         class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-500 
         text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 
         hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400" />

                    </div>

                </form>
            </div>
        </x-modal>
