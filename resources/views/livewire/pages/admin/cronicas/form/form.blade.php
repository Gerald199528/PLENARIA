 
 <div class="bg-gray-100 dark:bg-gray-900 min-h-screen flex items-center justify-center py-10 px-4">
    <div class="w-full max-w-7xl bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-500">

      <!-- Encabezado -->
      <div class="flex flex-col md:flex-row items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-700 to-indigo-900 text-white">
        <div class="flex items-center gap-3 mb-4 md:mb-0">
  <i class="fa-solid fa-book-open animate-bounce text-3xl text-white"></i>

        
          <h2 class="text-3xl md:text-4xl font-extrabold tracking-wide">
            {{ $mode === 'edit' ? 'Editar Crónica' : 'Registrar Crónica' }}
          </h2>
        </div>
        <x-button slate wire:click="cancel" spinner="cancel" label="Volver" icon="arrow-left"
          class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-xl 
          shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400" />
      </div>

      <!-- Formulario -->
      <form class="p-8 space-y-6" wire:submit.prevent="save">


      <!-- Nombre del cronista como input bloqueado -->
      <div class="relative group">
        <label for="cronista_nombre" class="flex items-center gap-2 mb-1 font-semibold text-gray-700 dark:text-gray-300">
          <x-icon name="user" /> Nombre del Cronista
        </label>
        <input 
            type="text" 
            id="cronista_nombre" 
            wire:model="cronista_nombre"
            placeholder="Escribe el nombre del cronista"
            disabled
            class="block w-full p-4 text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 cursor-not-allowed">
        
        <p class="mt-1 text-sm text-yellow-600 dark:text-yellow-400 flex items-center gap-1">
          <x-icon name="lock-closed" class="w-4 h-4" /> Campo bloqueado
        </p>
      </div>

        <!-- Título -->
        <div class="relative group">
          <label for="titulo" class="flex items-center gap-2 mb-1 font-semibold text-gray-700 dark:text-gray-300">
            <x-icon name="pencil" /> Título de la Crónica
          </label>
          <input type="text" id="titulo" wire:model="titulo" placeholder="Escribe el título de la crónica"
            class="block w-full p-4 text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300">
        </div>

        <!-- Categoría -->
        <div class="relative group">
          <label for="categoria_id" class="flex items-center gap-2 mb-1 font-semibold text-gray-700 dark:text-gray-300">
            <x-icon name="tag" /> Seleccione Categoría
          </label>
          <select id="categoria_id" wire:model="categoria_id"
            class="block w-full p-4 text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300">
            <option value="">Seleccione una categoría</option>
            @foreach ($categorias as $categoria)
              <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
            @endforeach
          </select>
        </div>

        <!-- Fecha de publicación -->
        <div class="relative group">
          <label for="fecha_publicacion" class="flex items-center gap-2 mb-1 font-semibold text-gray-700 dark:text-gray-300">
            <x-icon name="calendar" /> Fecha de Publicación:
          </label>
          <input type="date" id="fecha_publicacion" wire:model="fecha_publicacion"
            class="block w-full p-4 text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300">
        </div>

        <!-- Archivo PDF -->
        <div class="relative group">
          <label for="archivo_pdf" class="flex items-center gap-2 mb-1 font-semibold text-gray-700 dark:text-gray-300">
            <x-icon name="document" /> Archivo PDF (opcional)
          </label>
          <input type="file" id="archivo_pdf" wire:model="archivo_pdf" accept=".pdf"
            class="block w-full text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300">
          @if($archivo_pdf)
            <p class="text-sm text-green-600 dark:text-green-400 mt-1">Archivo seleccionado: {{ $archivo_pdf->getClientOriginalName() }}</p>
          @endif
        </div>
        <!-- Contenido de la Crónica -->
        <div x-data="{ max: 5000, texto: @entangle('contenido') }" class="relative group">
          <label for="contenido" class="flex items-center gap-2 mb-1 text-base font-semibold text-gray-700 dark:text-gray-300">
            <x-icon name="document-text" /> Contenido de la Crónica
          </label>
          <br>
          <textarea id="contenido" rows="6" x-model="texto" maxlength="5000"
            class="block w-full p-5 text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg"
            placeholder="Escribe el contenido completo de la crónica..."></textarea>
          <div class="flex justify-between mt-2 text-sm text-gray-500 dark:text-gray-400">
            <p class="flex items-center gap-1"><x-icon name="information-circle" class="w-4 h-4 text-yellow-500" /> Máximo 5000 caracteres</p>
            <p class="font-semibold text-yellow-500 dark:text-yellow-400"><span x-text="max - texto.length"></span> restantes</p>
          </div>
        </div>

        <!-- Botones -->
             
        <div class="flex flex-col md:flex-row justify-center gap-4 md:gap-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                 @if($mode == 'create')   
          <x-button slate wire:click="limpiar" spinner="limpiar" label="Limpiar" icon="trash"
            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-gray-400 hover:to-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400" />
   @endif
          <x-button info type="submit" label="{{ $mode === 'edit' ? 'Actualizar Crónica' : 'Guardar Crónica' }}"
            icon="check" interaction="positive" spinner="save"
            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400" />
        </div>
      </form>
    </div>
  </div>
