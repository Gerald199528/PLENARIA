<div class="bg-gray-100 dark:bg-gray-900 min-h-screen flex items-center justify-center py-10 px-4">

  <div class="w-full max-w-7xl bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-500">

    <!-- Encabezado -->
    <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-700 to-indigo-900 text-white">
      <div class="flex items-center gap-3">
        <x-icon name="document-plus" class="w-8 h-8 text-white animate-bounce" />
        <h2 class="text-3xl md:text-4xl font-extrabold tracking-wide">Importar Acuerdo</h2>
      </div>
      <x-button slate wire:click="cancel" spinner="cancel" label="Volver" icon="arrow-left"
        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-gray-400 hover:to-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400" />
    </div>

    <!-- Mensaje informativo -->
    <div class="p-6 bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 dark:border-blue-400">
      <p class="text-blue-700 dark:text-blue-200 text-base font-medium flex items-center gap-2 animate-fadeIn">
        <x-icon name="information-circle" class="w-5 h-5 text-blue-500 dark:text-blue-300" />
        Podrás <strong>importar tus acuerdos</strong> de manera rápida y segura. Solo archivos PDF.
      </p>
    </div>

    <!-- Formulario -->
    <form wire:submit.prevent="save" enctype="multipart/form-data" class="p-8 space-y-8">

      <!-- Grid PDF + Fecha -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
       {{-- Fila 2: PDF y Botón Ver (si existe) --}}
            <div class="relative group">
                <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                    <x-icon name="paper-clip" class="w-5 h-5" />    Importar Acuerdo (pdf)           
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

                {{-- Indicador de carga --}}
                <div wire:loading wire:target="nombre" class="flex items-center justify-center gap-3 mt-3 text-blue-600">
                    <div class="flex gap-1.5">
                        <div class="w-3 h-3 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0s;"></div>
                        <div class="w-3 h-3 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
                        <div class="w-3 h-3 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.4s;"></div>
                    </div>
                    <span class="text-sm font-medium">Subiendo archivo...</span>
                </div>

                {{-- Confirmación de archivo cargado --}}
                @if ($nombre)
                    <div class="mt-4 p-5 bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-800 dark:to-emerald-800 border-2 border-green-400 dark:border-green-600 rounded-xl shadow-md animate-pulse">
                        <div class="flex items-center gap-4">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-green-700 dark:text-green-300 animate-bounce" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-green-900 dark:text-green-100 text-base font-bold">
                                    Archivo cargado correctamente
                                </p>
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

        <!-- Fecha de aprobación -->
        <div class="relative group">
          <label for="fecha_aprobacion" class="flex items-center gap-2 mb-2 text-base font-semibold text-gray-700 dark:text-gray-300">
            <x-icon name="calendar" /> Fecha y Hora de Aprobación
          </label>
          <input type="datetime-local" id="fecha_aprobacion" wire:model.defer="fecha_aprobacion"
            class="block w-full p-4 text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700
                   border border-gray-300 dark:border-gray-600 rounded-2xl
                   focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300
                   group-hover:scale-[1.02] group-hover:shadow-lg">
      
        </div>

      </div>

    <!-- Categoría -->
<div class="relative group">
  <label for="categoria" class="flex items-center gap-2 mb-2 text-base font-semibold text-gray-700 dark:text-gray-300">
    <x-icon name="tag" /> Selecciona Categoría
  </label>

  <select id="categoria" wire:model="categoria_id"
          class="block w-full p-4 text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700
                 border border-gray-300 dark:border-gray-600 rounded-2xl
                 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300
                 group-hover:scale-[1.01] group-hover:shadow-lg" required>
    <option value="">{{ $categorias->isEmpty() ? 'No hay categorías de Acuerdos disponibles' : 'Selecciona una categoría' }}</option>
    @foreach($categorias as $cat)
      <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
    @endforeach
  </select>


</div>


      <!-- Observación -->
      <div x-data="{ max: 1000, texto: @entangle('observacion') }" class="relative group">
        <label for="observacion" class="flex items-center gap-2 mb-2 text-base font-semibold text-gray-700 dark:text-gray-300">
          <x-icon name="chat-bubble-left-right" /> Observación
        </label>
        <textarea id="observacion" rows="6" x-model="texto" maxlength="1000"
          class="block w-full p-5 text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700
                 border border-gray-300 dark:border-gray-600 rounded-2xl resize-none
                 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300
                 group-hover:scale-[1.01] group-hover:shadow-lg"
          placeholder="Agrega cualquier detalle o nota relevante sobre el acuerdo..."></textarea>

        <div class="flex justify-between mt-2 text-sm text-gray-500 dark:text-gray-400">
          <p class="flex items-center gap-1">
            <x-icon name="information-circle" class="w-4 h-4 text-yellow-500" />
            Máximo 1000 caracteres
          </p>
          <p class="font-semibold text-yellow-500 dark:text-yellow-400">
            <span x-text="max - texto.length"></span> restantes
          </p>
        </div>

    
      </div>

      <!-- Botones -->
      <div class="flex justify-center gap-8 pt-6 border-t border-gray-200 dark:border-gray-700">
        <x-button slate wire:click="limpiar" spinner="limpiar" label="Limpiar" icon="trash" interaction="secondary"
          class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-gray-400 hover:to-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400" />
        <x-button info wire:click="save" spinner="save" label="Guardar Acuerdo" icon="check" interaction="positive"
          class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400" />
      </div>

    </form>
  </div>
</div>

