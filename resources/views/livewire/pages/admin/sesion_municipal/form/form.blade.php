<div class="w-full min-h-screen bg-gray-100 dark:bg-gray-900 p-4 py-8">
    <div class="w-full max-w-6xl mx-auto bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        
        {{-- Header --}}
        <div class="flex items-center justify-between p-8 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-700 to-indigo-900 text-white">
            <div class="flex items-center gap-4">
                <x-icon name="{{ $mode === 'create' ? 'document-plus' : 'pencil-square' }}" class="w-10 h-10 text-white animate-bounce" />
                <h2 class="text-4xl font-extrabold tracking-wide">
                    {{ $mode === 'create' ? 'Registrar Sesión Municipal' : 'Editar Sesión Municipal' }}
                </h2>
            </div>
            <x-button slate wire:click="cancel" spinner="cancel" label="Volver" icon="arrow-left"
                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-xl shadow-lg hover:scale-105" />
        </div>

        {{-- Formulario --}}
        <form wire:submit.prevent="save" class="p-10 space-y-10">

            {{-- SECCIÓN CATEGORÍA --}}
            <div class="border-b-2 pb-8 border-gray-200 dark:border-gray-700">
                <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-8 flex items-center gap-3">
                    <x-icon name="folder" class="w-8 h-8 text-blue-600" />
                    Información de la Categoría
                </h3>

                {{-- SELECT DE CATEGORÍAS EXISTENTES (Aparece en ambos modos) --}}
                <div class="relative group mb-8">
                    <label class="flex items-center gap-2 mb-2 text-base font-semibold text-gray-700 dark:text-gray-300">
                        <x-icon name="tag" /> Seleccionar Categoría {{ $mode === 'create' ? '(Opcional)' : '' }}
                    </label>
                    <select wire:model.live="categoria_id" 
                        class="block w-full p-5 text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
                        <option value="">-- {{ $mode === 'create' ? 'Seleccionar o crear nueva' : 'Seleccionar categoría' }} --</option>
                        @foreach($this->categorias as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- CAMPOS PARA CREAR NUEVA CATEGORÍA (Solo aparecen si no seleccionó una existente en CREATE) --}}
                @if($mode === 'create' && !$categoria_id)
                    {{-- Nombre Categoría --}}
                    <div class="relative group mb-8 animate-fadeIn">
                        <label class="flex items-center gap-2 mb-2 text-base font-semibold text-gray-700 dark:text-gray-300">
                            <x-icon name="tag" /> Nombre de la Categoría
                        </label>
                        <input type="text" wire:model="categoria_nombre" maxlength="255"
                            placeholder="Ej: Servicios Públicos"
                            class="block w-full p-5 text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
                    </div>

                    {{-- Descripción Categoría --}}
                    <div x-data="{ max: 1000, texto: @entangle('categoria_descripcion') }" class="relative group animate-fadeIn">
                        <label class="flex items-center gap-2 mb-2 text-base font-semibold text-gray-700 dark:text-gray-300">
                            <x-icon name="document-text" /> Descripción de la Categoría
                        </label>
                        <textarea wire:model="categoria_descripcion" maxlength="1000" rows="4"
                            placeholder="Describe esta categoría..."
                            class="block w-full p-5 text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg"></textarea>
                        <div class="flex justify-between mt-2 text-sm text-gray-500 dark:text-gray-400">
                            <p class="flex items-center gap-1"><x-icon name="information-circle" class="w-4 h-4 text-yellow-500" /> Máximo 1000 caracteres</p>
                            <p class="font-semibold text-yellow-500 dark:text-yellow-400"><span x-text="max - texto.length"></span> restantes</p>
                        </div>
                    </div>
                @endif

                {{-- MOSTRAR DESCRIPCIÓN DE LA CATEGORÍA SELECCIONADA (En EDIT) --}}
                @if($mode === 'edit' && $categoria_id)
                    <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-2xl animate-fadeIn">
                        <p class="text-sm font-semibold text-blue-900 dark:text-blue-100">Descripción de la categoría:</p>
                        <p class="text-sm text-blue-800 dark:text-blue-200 mt-2">{{ $categoria_actual->descripcion ?? 'Sin descripción' }}</p>
                    </div>
                @endif
            </div>

            {{-- SECCIÓN SESIÓN --}}
            <div class="pb-8">
                <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-8 flex items-center gap-3">
                    <x-icon name="calendar" class="w-8 h-8 text-blue-600" />
                    Información de la Sesión
                </h3>

                {{-- Título Sesión --}}
                <div x-data="{ max: 255, texto: @entangle('titulo') }" class="relative group mb-8">
                    <label class="flex items-center gap-2 mb-2 text-base font-semibold text-gray-700 dark:text-gray-300">
                        <x-icon name="document-text" /> Título de la Sesión
                    </label>
                    <input type="text" wire:model="titulo" maxlength="255"
                        placeholder="Ej: Sesiones Próximas del 2024"
                        class="block w-full p-5 text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
                    <div class="flex justify-between mt-2 text-sm text-gray-500 dark:text-gray-400">
                        <p class="flex items-center gap-1"><x-icon name="information-circle" class="w-4 h-4 text-yellow-500" /> Máximo 255 caracteres</p>
                        <p class="font-semibold text-yellow-500 dark:text-yellow-400"><span x-text="max - texto.length"></span> restantes</p>
                    </div>
                </div>

                {{-- Descripción Sesión --}}
                <div x-data="{ max: 2000, texto: @entangle('descripcion') }" class="relative group mb-8">
                    <label class="flex items-center gap-2 mb-2 text-base font-semibold text-gray-700 dark:text-gray-300">
                        <x-icon name="chat-bubble-left-right" /> Descripción de la Sesión
                    </label>
                    <textarea wire:model="descripcion" maxlength="2000" rows="7"
                        placeholder="Describe detalladamente el contenido y propósito de la sesión..."
                        class="block w-full p-5 text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg"></textarea>
                    <div class="flex justify-between mt-2 text-sm text-gray-500 dark:text-gray-400">
                        <p class="flex items-center gap-1"><x-icon name="information-circle" class="w-4 h-4 text-yellow-500" /> Máximo 2000 caracteres</p>
                        <p class="font-semibold text-yellow-500 dark:text-yellow-400"><span x-text="max - texto.length"></span> restantes</p>
                    </div>
                </div>

                {{-- Fecha y Estado --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="relative group">
                        <label class="flex items-center gap-2 mb-2 text-base font-semibold text-gray-700 dark:text-gray-300">
                            <x-icon name="calendar" /> Fecha y Hora
                        </label>
                        <input type="datetime-local" wire:model="fecha_hora" 
                            class="block w-full p-5 text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
                    </div>

                    <div class="relative group">
                        <label class="flex items-center gap-2 mb-2 text-base font-semibold text-gray-700 dark:text-gray-300">
                            <x-icon name="information-circle" /> Estado de la Sesión
                        </label>
                        <select wire:model="estado" 
                            class="block w-full p-5 text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
                            <option value="proxima">Próxima</option>
                            @if($mode === 'edit')
                                <option value="abierta">Abierta</option>
                                <option value="cerrada">Cerrada</option>
                                <option value="completada">Completada</option>
                            @endif
                        </select>  
                    </div>
                </div>
            </div>

            {{-- Botones --}}
            <div class="flex justify-center gap-6 pt-8 border-t-2 border-gray-200 dark:border-gray-700">
                @if($mode === 'create')
                    <x-button slate wire:click="limpiar" spinner="limpiar" label="Limpiar" icon="trash"
                        class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold text-lg rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl" />
                @endif
                <x-button info type="submit" spinner="save" label="{{ $mode === 'create' ? 'Guardar Sesión' : 'Guardar Cambios' }}" icon="check"
                    class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold text-lg rounded-xl shadow-lg hover:scale-105" />
            </div>
        </form>
    </div>

</div>