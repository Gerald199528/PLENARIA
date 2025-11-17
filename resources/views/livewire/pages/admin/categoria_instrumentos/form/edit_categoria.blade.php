
    <!-- Card principal -->
    <div class="w-full max-w-7xl bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mt-6 transition-all duration-500 mx-auto">
        
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
            <div class="flex items-center gap-3">
                <x-icon name="tag" class="w-8 h-8 text-white animate-bounce" />
                <h2 class="text-3xl md:text-4xl font-extrabold tracking-wide">Editar Categoría</h2>
            </div>

            <x-button slate wire:click="cancel" spinner="cancel" label="Volver" icon="arrow-left"
                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-gray-400 hover:to-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400" />
        </div>

        <!-- Mensaje informativo -->
        <div class="p-6 bg-indigo-50 dark:bg-indigo-900/30 border-l-4 border-indigo-500 dark:border-indigo-400">
            <p class="text-indigo-700 dark:text-indigo-200 text-base font-medium flex items-center gap-2 animate-fadeIn">
                <x-icon name="information-circle" class="w-5 h-5 text-indigo-500 dark:text-indigo-300" />
                Aquí podrás <strong>editar el nombre, tipo y observación</strong> de la categoría.
            </p>
        </div>

        <!-- Formulario -->
        <form wire:submit.prevent="update" class="p-8 space-y-8">

            <!-- Nombre -->
            <div class="relative group">
                <label for="nombre" class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                    <x-icon name="document-text" /> Nombre
                </label>
                <input type="text" id="nombre" wire:model.defer="nombre"
                       class="w-full p-4 rounded-2xl bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 transition-all duration-300
                              focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 group-hover:scale-[1.01] group-hover:shadow-lg">
                @error('nombre')
                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                        <x-icon name="exclamation-circle" class="w-4 h-4" /> {{ $message }}
                    </p>
                @enderror
            </div>

      <!-- Tipo de Categoría -->
      <div class="relative group">
        <label for="tipo_categoria" class="flex items-center gap-2 mb-2 text-base font-semibold text-gray-700 dark:text-gray-300">
          <x-icon name="tag" /> Tipo de Categoría
        </label>
        <select id="tipo_categoria" wire:model.defer="tipo_categoria"
          class="block w-full p-4 text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700
                 border border-gray-300 dark:border-gray-600 rounded-2xl
                 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300
                 group-hover:scale-[1.02] group-hover:shadow-lg">
          <option value="">Selecciona un tipo</option>
          <option value="Acuerdos">Acuerdos</option>
          <option value="Ordenanzas">Ordenanzas</option>
        
        </select>
        @error('tipo_categoria')
          <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
            <x-icon name="exclamation-circle" class="w-4 h-4" /> {{ $message }}
          </p>
        @enderror
      </div>


            <!-- Observación -->
            <div x-data="{ max: 1000, texto: @entangle('observacion') }" class="relative group">
                <label for="observacion" class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                    <x-icon name="chat-bubble-left-right" /> Observación
                </label>
                <textarea id="observacion" x-model="texto" maxlength="1000" rows="6"
                          class="w-full p-5 rounded-2xl bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 resize-none
                                 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg"
                          placeholder="Agrega notas o detalles sobre la categoría..."></textarea>

                <div class="flex justify-between mt-2 text-sm text-gray-500 dark:text-gray-400">
                    <p>Máximo 1000 caracteres</p>
                    <p class="font-semibold text-indigo-500 dark:text-indigo-400"><span x-text="max - texto.length"></span> restantes</p>
                </div>

                @error('observacion')
                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                        <x-icon name="exclamation-circle" class="w-4 h-4" /> {{ $message }}
                    </p>
                @enderror
            </div>

           <!-- Botón Guardar Cambios -->
    <div class="flex justify-center gap-8 pt-6 border-t border-gray-200 dark:border-gray-700">
      <x-button info type="submit" spinner="update" label="Guardar Cambios" icon="check" interaction="positive"
                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400" />
    </div>
        </form>
    </div>
