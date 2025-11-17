   
   <div class=" min-h-screen flex items-center justify-center py-10 px-4">
        <div class="w-full max-w-7xl bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-500">

            <!-- Encabezado -->
            <div class="flex flex-col md:flex-row items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-700 to-indigo-900 text-white">
                <div class="flex items-center gap-3 mb-4 md:mb-0">
                    <i class="fa-solid fa-tags animate-bounce text-3xl text-white"></i>
                    <h2 class="text-3xl md:text-4xl font-extrabold tracking-wide">
                        {{ $mode === 'edit' ? 'Editar Categoría' : 'Registrar Categoría' }}
                    </h2>
                </div>
                <x-button slate wire:click="cancel" spinner="cancel" label="Volver" icon="arrow-left"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-xl 
                    shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400" />
            </div>

            <!-- Formulario -->
          <form class="p-8 space-y-6" wire:submit.prevent="save">

                <!-- Nombre -->
                <div class="relative group">
                    <label for="nombre" class="flex items-center gap-2 mb-1 font-semibold text-gray-700 dark:text-gray-300">
                        <x-icon name="tag" /> Nombre de la Categoría
                    </label>
                    <input type="text" id="nombre" wire:model="nombre" placeholder="Escribe el nombre de la categoría"
                        class="block w-full p-4 text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300">
                </div>

                <!-- Descripción -->
                <div class="relative group">
                    <label for="descripcion" class="flex items-center gap-2 mb-1 font-semibold text-gray-700 dark:text-gray-300">
                        <x-icon name="document-text" /> Descripción
                    </label>
                    <textarea id="descripcion" rows="4" wire:model="descripcion" placeholder="Describe la categoría"
                        class="block w-full p-4 text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"></textarea>
                </div>

                <!-- Botones -->
                <div class="flex flex-col md:flex-row justify-center gap-4 md:gap-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    @if($mode == 'create')
                        <x-button slate wire:click="limpiar" spinner="limpiar" label="Limpiar" icon="trash"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-gray-400 hover:to-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400" />
                    @endif

                    <x-button info type="submit" label="{{ $mode === 'edit' ? 'Actualizar Categoría' : 'Guardar Categoría' }}"
                        icon="check" interaction="positive" spinner="save"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400" />
                </div>

            </form>
        </div>
    </div>






















