<div class="flex justify-center items-center min-h-screen py-10 bg-transparent">
  <div class="w-full max-w-7xl bg-white dark:bg-gray-800 rounded-2xl sm:rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-500">

    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-6 
                p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700 
                bg-gradient-to-r from-blue-700 to-indigo-900 text-white">
      
      <div class="flex items-center gap-2 sm:gap-3">
        <x-icon name="document-plus" class="w-6 h-6 sm:w-8 sm:h-8 text-white animate-bounce" />
        <h2 class="text-xl sm:text-3xl md:text-4xl font-extrabold tracking-wide">Editar Ordenanza</h2>
      </div>

      <!-- Botón Volver -->
      <x-button slate wire:click="cancel" spinner="cancel" label="Volver" icon="arrow-left"
        class="inline-flex items-center gap-1 sm:gap-2 
               px-3 py-2 sm:px-6 sm:py-3 text-xs sm:text-sm md:text-base 
               bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-lg sm:rounded-xl 
               shadow-md hover:scale-105 hover:shadow-xl 
               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition-all" />
    </div>

    <!-- Mensaje informativo -->
    <div class="w-full px-3 sm:px-6 py-3 sm:py-4 
                bg-blue-50 dark:bg-blue-900/30 
                border-l-4 border-blue-500 dark:border-blue-400
                rounded-lg">
      <p class="text-xs sm:text-sm md:text-base 
                text-blue-700 dark:text-blue-200 
                font-medium flex flex-col sm:flex-row 
                items-start sm:items-center gap-2 leading-relaxed break-words">
        <x-icon name="information-circle" 
                class="w-4 h-4 sm:w-5 sm:h-5 
                       text-blue-500 dark:text-blue-300 flex-shrink-0" />
        <span>
          Aquí podrás <strong>editar solo la fecha de aprobación y observación</strong> de tus ordenanzas.
        </span>
      </p>
    </div>


  <!-- Formulario -->
  <form wire:submit.prevent="save" class="p-4 sm:p-8 space-y-6 sm:space-y-8">

    <!-- Nombre y Documento -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
      <!-- Nombre -->
      <div>
        <label class="block text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
          <x-icon name="document-text" /> Nombre
        </label>
        <input type="text" 
               class="w-full p-3 sm:p-4 rounded-xl sm:rounded-2xl bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm sm:text-base"
               value="{{ $ordenanza->nombre }}" readonly>
        <p class="text-yellow-500 text-xs sm:text-sm flex items-center gap-1 mt-1">
          <x-icon name="lock-closed" class="w-4 h-4" /> Campo bloqueado
        </p>
      </div>

      <!-- Documento PDF -->
      <div>
        <label class="block text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
          <x-icon name="eye" /> Documento PDF
        </label>
        <button type="button" 
                onclick="window.open('{{ asset('storage/' . $ordenanza->ruta) }}', '_blank')"
                class="w-full px-3 py-2 sm:p-4 rounded-lg sm:rounded-xl 
                       bg-gradient-to-r from-blue-600 to-blue-500 text-white font-semibold 
                       flex items-center justify-center gap-2 text-sm sm:text-base 
                       shadow-md hover:scale-105 hover:shadow-xl 
                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400 transition-all">
          <x-icon name="eye" class="w-4 h-4 sm:w-5 sm:h-5 animate-pulse" /> Ver PDF
        </button>
      </div>
    </div>

    <!-- Fechas -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
      <!-- Fecha de Importación -->
      <div>
        <label class="block text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
          <x-icon name="calendar" /> Fecha de Importación
        </label>
        <input type="text" 
               class="w-full p-3 sm:p-4 rounded-xl sm:rounded-2xl bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm sm:text-base"
               value="{{ $ordenanza->fecha_importacion ? $ordenanza->fecha_importacion->format('d/m/Y h:i A') : 'No registrada' }}" readonly>
        <p class="text-yellow-500 text-xs sm:text-sm flex items-center gap-1 mt-1">
          <x-icon name="lock-closed" class="w-4 h-4" /> Campo bloqueado
        </p>
      </div>

      <!-- Fecha Aprobación -->
      <div>
        <label for="fecha_aprobacion" class="block text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
          <x-icon name="calendar" /> Fecha de Aprobación
        </label>
        <input type="datetime-local" id="fecha_aprobacion" wire:model.defer="fecha_aprobacion" required
               class="w-full p-3 sm:p-4 rounded-xl sm:rounded-2xl bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm sm:text-base 
                      focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" >
      </div>
    </div>

    <!-- Categoría -->
    <div>
      <label class="block text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
        <x-icon name="tag" /> Categoría
      </label>
      <input type="text" 
             class="w-full p-3 sm:p-4 rounded-xl sm:rounded-2xl bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm sm:text-base"
             value="{{ $ordenanza->categoria ? $ordenanza->categoria->nombre : 'Sin categoría' }}" readonly>
      <p class="text-yellow-500 text-xs sm:text-sm flex items-center gap-1 mt-1">
        <x-icon name="lock-closed" class="w-4 h-4" /> Campo bloqueado
      </p>
    </div>

    <!-- Observación -->
    <div x-data="{ max: 1000, texto: @entangle('observacion') }">
      <label for="observacion" class="block text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
        <x-icon name="chat-bubble-left-right" /> Observación
      </label>
      <textarea id="observacion" x-model="texto" maxlength="1000" rows="5" 
                class="w-full p-3 sm:p-5 text-sm sm:text-base rounded-xl sm:rounded-2xl bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 resize-none 
                       placeholder-yellow-500 dark:placeholder-yellow-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"></textarea>

      <div class="flex justify-between mt-2 text-xs sm:text-sm text-gray-500 dark:text-gray-400">
        <p>Máximo 1000 caracteres</p>
        <p class="font-semibold text-yellow-500 dark:text-yellow-400">
          <span x-text="max - texto.length"></span> restantes
        </p>
      </div>
    </div>

    <!-- Botón Guardar -->
    <div class="flex justify-center pt-4 sm:pt-6 border-t border-gray-200 dark:border-gray-700">
      <x-button info type="submit" spinner="save" label="Guardar Cambios" icon="check" interaction="positive"
                class="inline-flex items-center gap-1 sm:gap-2 
                       px-4 py-2 sm:px-6 sm:py-3 text-sm sm:text-base 
                       bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-lg sm:rounded-xl 
                       shadow-md hover:scale-105 hover:shadow-xl 
                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400 transition-all" />
    </div>

  </form>
</div>
