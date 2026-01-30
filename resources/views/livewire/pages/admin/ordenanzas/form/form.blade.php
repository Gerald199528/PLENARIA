<div class="flex justify-center items-center min-h-screen py-10 bg-transparent">
  <div class="w-full max-w-7xl bg-white dark:bg-gray-800 rounded-2xl sm:rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-500">

    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-6 
                p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700 
                bg-gradient-to-r from-blue-700 to-indigo-900 text-white">
      
      <div class="flex items-center gap-2 sm:gap-3">
        <x-icon name="document-plus" class="w-6 h-6 sm:w-8 sm:h-8 text-white animate-bounce" />
        <h2 class="text-xl sm:text-3xl md:text-4xl font-extrabold tracking-wide">
          {{ $mode === 'edit' ? 'Editar Ordenanza' : 'Importar Ordenanza' }}
        </h2>
      </div>

      <x-button slate wire:click="cancel" spinner="cancel" label="Volver" icon="arrow-left"
          class="inline-flex items-center gap-1 sm:gap-2 px-3 sm:px-4 md:px-6 py-1.5 sm:py-2 md:py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-lg sm:rounded-xl shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-2xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 text-xs sm:text-sm md:text-base w-full sm:w-auto" />
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
          @if ($mode === 'edit')
            Aquí podrás <strong>editar solo la fecha de aprobación y observación</strong> de tu ordenanza.
          @else
            Podrás <strong>importar tus ordenanzas</strong> de manera rápida y segura. <span class="block sm:inline">Solo archivos PDF.</span>
          @endif
        </span>
      </p>
    </div>

    <!-- Formulario -->
    <form wire:submit.prevent="save" enctype="multipart/form-data" class="p-4 sm:p-8 space-y-6 sm:space-y-8">

      @if ($mode === 'create')
      <!-- Grid PDF + Fecha (Solo en CREATE) -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
        <!-- Upload PDF -->
        <div class="relative group">
          <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
            <x-icon name="paper-clip" class="w-5 h-5" /> Importar Ordenanza (pdf)
          </label>
          <div class="flex items-end gap-3">
            <div class="flex-1">
              <input 
                type="file"  
                wire:model="nombre"  
                accept=".pdf"  
                class="w-full p-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 cursor-pointer hover:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all" />
            </div>
          </div>

          <!-- Indicador de carga -->
          <div wire:loading wire:target="nombre" class="flex items-center justify-center gap-3 mt-3 text-blue-600">
            <div class="flex gap-1.5">
              <div class="w-3 h-3 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0s;"></div>
              <div class="w-3 h-3 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
              <div class="w-3 h-3 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.4s;"></div>
            </div>
            <span class="text-sm font-medium">Subiendo archivo...</span>
          </div>

          <!-- Confirmación de archivo cargado -->
          @if ($nombre)
            <div class="mt-4 p-5 bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-800 dark:to-emerald-800 border-2 border-green-400 dark:border-green-600 rounded-xl shadow-md animate-pulse">
              <div class="flex items-center gap-4">
                <div class="flex-shrink-0">
                  <svg class="w-8 h-8 text-green-700 dark:text-green-300 animate-bounce" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                  </svg>
                </div>
                <div class="flex-1">
                  <p class="text-green-900 dark:text-green-100 text-base font-bold">Archivo cargado correctamente</p>
                  <p class="text-green-800 dark:text-green-200 text-sm mt-1 truncate">
                    📄 {{ $nombre->getClientOriginalName() }}
                  </p>
                </div>
                <button type="button" wire:click="$set('nombre', null)" class="flex-shrink-0 text-green-700 dark:text-green-300 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                  <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                  </svg>
                </button>
              </div>
            </div>
          @endif
        </div>

        <!-- Fecha Aprobación -->
        <div class="relative group">
          <label for="fecha_aprobacion" class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
            <x-icon name="calendar" /> Fecha y Hora de Aprobación
          </label>
          <input type="datetime-local" id="fecha_aprobacion" wire:model.defer="fecha_aprobacion"
            class="block w-full p-3 sm:p-4 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700
                   border border-gray-300 dark:border-gray-600 rounded-xl sm:rounded-2xl
                   focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300
                   group-hover:scale-[1.01] group-hover:shadow-lg">
          </input>
        </div>
      </div>

      @else
      <!-- MODO EDIT: Mostrar nombre del documento (readonly) -->
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

      <!-- Fecha de Importación (readonly en edit) -->
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

      <!-- Fecha Aprobación (editable) -->
      <div>
        <label for="fecha_aprobacion" class="block text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
          <x-icon name="calendar" /> Fecha de Aprobación
        </label>
        <input type="datetime-local" id="fecha_aprobacion" wire:model.defer="fecha_aprobacion" required
               class="w-full p-3 sm:p-4 rounded-xl sm:rounded-2xl bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm sm:text-base 
                      focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" />
      </div>

      <!-- Categoría (readonly) -->
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
      @endif

      <!-- Categoría (solo en CREATE) -->
      @if ($mode === 'create')
      <div class="relative group">
        <label for="categoria" class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
          <x-icon name="tag" /> Selecciona Categoría
        </label>
        <select id="categoria" wire:model="categoria_id"
                class="block w-full p-3 sm:p-4 text-sm sm:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700
                       border border-gray-300 dark:border-gray-600 rounded-xl sm:rounded-2xl
                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300
                       group-hover:scale-[1.00] group-hover:shadow-lg" required>
          <option value="">{{ $categorias->isEmpty() ? 'No hay categorías' : 'Selecciona una categoría' }}</option>
          @foreach($categorias as $cat)
            <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
          @endforeach
        </select>
      </div>
      @endif

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

      <!-- Botones -->
      <div class="flex flex-col sm:flex-row justify-center gap-4 sm:gap-6 md:gap-8 pt-4 sm:pt-6 border-t border-gray-200 dark:border-gray-700">
        @if ($mode === 'create')
          <x-button slate wire:click="limpiar" spinner="limpiar" label="Limpiar" icon="trash"
            class="inline-flex items-center gap-1 sm:gap-2 
                   px-2 py-1.5 sm:px-4 sm:py-2 md:px-6 md:py-3 
                   text-xs sm:text-sm md:text-base 
                   bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold 
                   rounded-lg sm:rounded-xl md:rounded-2xl 
                   shadow-md md:shadow-xl 
                   transform transition-all duration-300 
                   hover:scale-105 hover:shadow-2xl hover:from-gray-400 hover:to-gray-500 
                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400" />
        @endif

        <x-button info type="submit" spinner="save" 
                  label="{{ $mode === 'edit' ? 'Guardar Cambios' : 'Guardar Ordenanza' }}" 
                  icon="check" interaction="positive"
                  class="inline-flex items-center gap-1 sm:gap-2 
                         px-4 sm:px-5 md:px-6 py-2 sm:py-2.5 md:py-3 
                         text-xs sm:text-sm md:text-base 
                         bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold 
                         rounded-lg sm:rounded-xl md:rounded-2xl 
                         shadow-md md:shadow-xl 
                         transform transition-all duration-300 
                         hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 
                         focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400 w-full sm:w-auto" />
      </div>

    </form>
  </div>
</div>