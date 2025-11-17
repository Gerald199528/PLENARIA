<div class="bg-gray-100 dark:bg-gray-900 min-h-screen flex items-center justify-center py-10 px-4">
    <div class="w-full max-w-7xl bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-500">

        <!-- Encabezado -->
        <div class="flex flex-col md:flex-row items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-700 to-indigo-900 text-white">
            <div class="flex items-center gap-3 mb-4 md:mb-0">
                <x-icon name="users" class="w-8 h-8 text-white animate-bounce" />
                <h2 class="text-3xl md:text-4xl font-extrabold tracking-wide">
                    {{ $mode === 'edit' ? 'Editar Miembro' : 'Registrar Miembro' }}
                </h2>
            </div>
            <x-button slate wire:click="cancel" spinner="cancel" label="Volver" icon="arrow-left"
                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400" />
        </div>

        <!-- Mensaje informativo -->
        <div class="p-6 bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 dark:border-blue-400">
            <p class="text-lg text-blue-700 dark:text-blue-200 font-medium flex items-center gap-2 animate-fadeIn">
                <x-icon name="information-circle" class="w-5 h-5 text-blue-500 dark:text-blue-300" />
                Completa los datos del miembro, seleccionando un concejal y su comisión, con fechas y estado.
            </p>
        </div>

        <!-- Formulario -->
        <form class="p-8 space-y-8" wire:submit.prevent="save">

            <!-- Selección de Concejal -->
            <div class="relative group">
                <label for="concejal_id" class="flex items-center gap-2 mb-1 font-semibold text-gray-700 dark:text-gray-300">
                    <x-icon name="user" /> Seleccione Concejal
                </label>
                <select id="concejal_id" wire:model="concejal_id"
                    class="block w-full p-4 text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300">
                    <option value="">Seleccione un Concejal</option>
                    @foreach ($concejales as $concejal)
                        <option value="{{ $concejal->id }}">{{ $concejal->nombre }} {{ $concejal->apellido }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Comisión -->
            <div x-data="{
                comisionSeleccionada: @entangle('comision_id'),
                actualizarBloqueo() {
                    if (this.comisionSeleccionada) {
                        @this.set('nueva_comision', '');
                        @this.set('descripcion_comision', '');
                    }
                },
                get bloqueado() {
                    return this.comisionSeleccionada !== null && this.comisionSeleccionada !== '';
                }
            }"
            x-effect="actualizarBloqueo()"
            class="mt-4">

                <!-- Selección de comisión existente -->
                <div class="mb-4">
                    <label for="comision_id" class="flex items-center gap-2 mb-1 font-semibold text-gray-700 dark:text-gray-300">
                        <x-icon name="briefcase" /> Seleccione Comisión
                    </label>
                    <select id="comision_id" wire:model="comision_id"
                        class="block w-full p-4 text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Selecciona una Comisión</option>
                        @foreach ($comisiones as $comision)
                            <option value="{{ $comision->id }}">{{ $comision->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                @if($mode === 'create')
                <!-- Nueva comisión -->
                <div class="mb-4 relative">
                    <label for="nueva_comision" class="flex items-center gap-2 mb-1 font-semibold text-gray-700 dark:text-gray-300">
                        <x-icon name="plus-circle" class="text-yellow-500 dark:text-yellow-400" /> Nueva Comisión (opcional)
                    </label>
                    <input type="text" id="nueva_comision" wire:model="nueva_comision" placeholder="Ej: Comisión de Finanzas"
                        :disabled="bloqueado"
                        class="block w-full p-5 text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all duration-300">

                    <div x-show="bloqueado" x-cloak
                        class="mt-2 p-3 rounded-lg border-l-4 border-yellow-500 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-900 dark:text-yellow-300 text-sm font-semibold flex items-center gap-2"
                        style="min-height: 50px;">
                        <x-icon name="information-circle" class="w-4 h-4 text-yellow-600 mr-2" />
                        El campo <strong>Nueva Comisión</strong> está bloqueado porque se seleccionó una comisión existente.
                    </div>

                    <div x-show="!bloqueado && $wire.nueva_comision.length > 0" x-cloak class="mb-4">
                        <label for="descripcion_comision" class="flex items-center gap-2 mb-1 font-semibold text-gray-700 dark:text-gray-300">
                            <x-icon name="clipboard-document-list" class="w-5 h-5 text-yellow-500" /> Descripción de la Comisión (opcional)
                        </label>
                        <textarea id="descripcion_comision" rows="4" wire:model="descripcion_comision" maxlength="500"
                            class="block w-full p-5 text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl resize-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all duration-300"
                            placeholder="Breve descripción de la comisión..."></textarea>
                        <p class="text-right text-sm text-gray-500 dark:text-gray-400">
                            <span x-text="500 - ($wire.descripcion_comision ? $wire.descripcion_comision.length : 0)"></span> caracteres restantes
                        </p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Fechas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="relative group">
                    <label for="fecha_inicio" class="flex items-center gap-2 mb-1 text-base font-semibold text-gray-700 dark:text-gray-300">
                        <x-icon name="calendar" /> Fecha de Inicio
                    </label>
                    <input type="date" id="fecha_inicio" wire:model="fecha_inicio"
                        class="block w-full p-4 text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
                </div>

                <div class="relative group">
                    <label for="fecha_fin" class="flex items-center gap-2 mb-1 text-base font-semibold text-gray-700 dark:text-gray-300">
                        <x-icon name="calendar" /> Fecha de Fin
                    </label>
                    <input type="date" id="fecha_fin" wire:model="fecha_fin"
                        class="block w-full p-4 text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
                </div>
            </div>

            <!-- Estado -->
            <div class="relative group">
                <div class="flex items-center justify-between p-6 bg-gradient-to-r from-gray-50 to-blue-50 dark:from-gray-700 dark:to-blue-900/20 border border-gray-200 dark:border-gray-600 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                            <x-icon name="check-circle" class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                        </div>
                        <div>
                            <label class="block text-lg font-bold text-gray-800 dark:text-gray-100">Estado del Miembro</label>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                {{ $estado === 'Activo' ? 'Miembro activo en la comisión' : 'Miembro inactivo' }}
                            </p>
                        </div>
                    </div>

                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" wire:click="toggleEstado" @if($estado === 'Activo') checked @endif>
                        <div class="w-16 h-8 bg-gray-300 dark:bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-100 dark:peer-focus:ring-blue-800 rounded-full peer peer-checked:after:translate-x-8 peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-7 after:w-7 after:transition-all dark:border-gray-600 peer-checked:bg-gradient-to-r peer-checked:from-blue-500 peer-checked:to-emerald-600 shadow-inner"></div>
                        <span class="ml-3 text-sm font-semibold @if($estado === 'Activo') text-blue-600 dark:text-green-600 @else text-gray-500 dark:text-gray-400 @endif">
                            {{ $estado === 'Activo' ? 'ACTIVO' : 'INACTIVO' }}
                        </span>
                    </label>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex flex-col md:flex-row justify-center gap-4 md:gap-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                @if ($mode !== 'edit')
                    <x-button slate wire:click="limpiar" spinner="limpiar" label="Limpiar" icon="trash"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl" />
                @endif

                <x-button info type="submit" label="{{ $mode === 'edit' ? 'Actualizar Miembro' : 'Guardar Miembro' }}"
                    icon="check" interaction="positive" spinner="save"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl" />
            </div>
        </form>
    </div>
</div>
