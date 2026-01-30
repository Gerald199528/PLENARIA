<div class="bg-gray-100 dark:bg-gray-900 min-h-screen flex items-center justify-center py-6 sm:py-8 md:py-10 px-2 sm:px-3 md:px-4">
    <div class="w-full max-w-7xl bg-white dark:bg-gray-800 rounded-2xl sm:rounded-2xl md:rounded-3xl shadow-xl sm:shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-500">

        <!-- Encabezado -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-3 sm:p-4 md:p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-700 to-indigo-900 text-white gap-3 sm:gap-0">
            <div class="flex items-center gap-2 sm:gap-3">
                <x-icon name="users" class="w-7 sm:w-8 md:w-10 h-7 sm:h-8 md:h-10 text-white animate-bounce flex-shrink-0" />
                <h2 class="text-lg sm:text-xl md:text-2xl lg:text-3xl font-extrabold tracking-wide">
                    {{ $mode === 'edit' ? 'Editar Miembro' : 'Registrar Miembro' }}
                </h2>
            </div>
            <x-button slate wire:click="cancel" spinner="cancel" label="Volver" icon="arrow-left"
                class="inline-flex items-center gap-1 sm:gap-2 px-3 sm:px-4 md:px-6 py-1.5 sm:py-2 md:py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-lg sm:rounded-xl shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-2xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 text-xs sm:text-sm md:text-base w-full sm:w-auto" />
        </div>

        <!-- Mensaje informativo mejorado -->
        <div class="p-2.5 sm:p-3.5 md:p-5 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/40 dark:to-blue-800/40 border-l-4 border-blue-500 dark:border-blue-400 rounded-r-lg sm:rounded-r-xl shadow-sm hover:shadow-md transition-all duration-300">
            <div class="flex items-start sm:items-center gap-2 sm:gap-3 md:gap-4">
                <x-icon name="information-circle" class="w-4 sm:w-5 md:w-6 h-4 sm:h-5 md:h-6 text-blue-600 dark:text-blue-300 flex-shrink-0 mt-0.5 sm:mt-0" />
                <p class="text-blue-700 dark:text-blue-200 text-xs sm:text-sm md:text-base font-medium leading-relaxed animate-fadeIn">
                    Completa los datos del miembro, seleccionando un concejal y su comisión, con fechas y estado.
                </p>
            </div>
        </div>

        <!-- Formulario -->
        <form class="p-4 sm:p-6 md:p-8 space-y-6 sm:space-y-8 md:space-y-10" wire:submit.prevent="save">

            <!-- Selección de Concejal -->
            <div class="relative group">
                <label for="concejal_id" class="flex items-center gap-1.5 sm:gap-2 mb-1 text-xs sm:text-sm md:text-base font-semibold text-gray-700 dark:text-gray-300">
                    <x-icon name="user" class="w-3.5 sm:w-4 md:w-4 h-3.5 sm:h-4 md:h-4 flex-shrink-0" /> Seleccione Concejal
                </label>
                <select id="concejal_id" wire:model="concejal_id"
                    class="block w-full p-2.5 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl md:rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 transform hover:scale-[1.01]">
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
                    <label for="comision_id" class="flex items-center gap-1.5 sm:gap-2 mb-1 text-xs sm:text-sm md:text-base font-semibold text-gray-700 dark:text-gray-300">
                        <x-icon name="briefcase" class="w-3.5 sm:w-4 md:w-4 h-3.5 sm:h-4 md:h-4 flex-shrink-0" /> Seleccione Comisión
                    </label>
                    <select id="comision_id" wire:model="comision_id"
                        class="block w-full p-2.5 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl md:rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 transform hover:scale-[1.01]">
                        <option value="">Selecciona una Comisión</option>
                        @foreach ($comisiones as $comision)
                            <option value="{{ $comision->id }}">{{ $comision->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                @if($mode === 'create')
                <!-- Nueva comisión -->
                <div class="mb-4 relative">
                    <label for="nueva_comision" class="flex items-center gap-1.5 sm:gap-2 mb-1 text-xs sm:text-sm md:text-base font-semibold text-gray-700 dark:text-gray-300">
                        <x-icon name="plus-circle" class="w-3.5 sm:w-4 md:w-4 h-3.5 sm:h-4 md:h-4 text-yellow-500 dark:text-yellow-400 flex-shrink-0" /> Nueva Comisión (opcional)
                    </label>
                    <input type="text" id="nueva_comision" wire:model="nueva_comision" placeholder="Ej: Comisión de Finanzas"
                        :disabled="bloqueado"
                        class="block w-full p-2.5 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl md:rounded-2xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all duration-300 disabled:opacity-60 disabled:cursor-not-allowed transform hover:scale-[1.01]">

                    <!-- Mensaje de bloqueo mejorado -->
                    <div x-show="bloqueado" x-cloak
                        class="mt-3 sm:mt-4 p-2.5 sm:p-3 md:p-4 rounded-lg sm:rounded-xl border-l-4 border-yellow-400 bg-gradient-to-r from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-900/30 text-yellow-900 dark:text-yellow-200 shadow-md hover:shadow-lg transition-all duration-300">
                        <div class="flex items-start sm:items-center gap-2 sm:gap-3">
                            <x-icon name="information-circle" class="w-4 sm:w-5 md:w-5 h-4 sm:h-5 md:h-5 text-yellow-600 dark:text-yellow-400 flex-shrink-0 mt-0.5 sm:mt-0" />
                            <p class="text-xs sm:text-sm md:text-base font-semibold">
                                El campo <strong>Nueva Comisión</strong> está bloqueado porque se seleccionó una comisión existente.
                            </p>
                        </div>
                    </div>

                    <div x-show="!bloqueado && $wire.nueva_comision.length > 0" x-cloak class="mb-4">
                        <label for="descripcion_comision" class="flex items-center gap-1.5 sm:gap-2 mb-1 mt-4 text-xs sm:text-sm md:text-base font-semibold text-gray-700 dark:text-gray-300">
                            <x-icon name="clipboard-document-list" class="w-3.5 sm:w-4 md:w-4 h-3.5 sm:h-4 md:h-4 text-yellow-500 flex-shrink-0" /> Descripción de la Comisión (opcional)
                        </label>
                        <textarea id="descripcion_comision" rows="4" wire:model="descripcion_comision" maxlength="500"
                            class="block w-full p-2.5 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl md:rounded-2xl resize-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all duration-300"
                            placeholder="Breve descripción de la comisión..."></textarea>
                        <p class="text-right text-xs sm:text-xs md:text-sm text-gray-500 dark:text-gray-400 mt-1">
                            <span x-text="500 - ($wire.descripcion_comision ? $wire.descripcion_comision.length : 0)"></span> caracteres restantes
                        </p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Fechas -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 md:gap-6">
                <div class="relative group">
                    <label for="fecha_inicio" class="flex items-center gap-1.5 sm:gap-2 mb-1 text-xs sm:text-sm md:text-base font-semibold text-gray-700 dark:text-gray-300">
                        <x-icon name="calendar" class="w-3.5 sm:w-4 md:w-4 h-3.5 sm:h-4 md:h-4 flex-shrink-0" /> Fecha de Inicio
                    </label>
                    <input type="date" id="fecha_inicio" wire:model="fecha_inicio"
                        class="block w-full p-2.5 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl md:rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 transform hover:scale-[1.01] group-hover:shadow-lg">
                </div>

                <div class="relative group">
                    <label for="fecha_fin" class="flex items-center gap-1.5 sm:gap-2 mb-1 text-xs sm:text-sm md:text-base font-semibold text-gray-700 dark:text-gray-300">
                        <x-icon name="calendar" class="w-3.5 sm:w-4 md:w-4 h-3.5 sm:h-4 md:h-4 flex-shrink-0" /> Fecha de Fin
                    </label>
                    <input type="date" id="fecha_fin" wire:model="fecha_fin"
                        class="block w-full p-2.5 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl md:rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 transform hover:scale-[1.01] group-hover:shadow-lg">
                </div>
            </div>

            <!-- Estado -->
            <div class="relative group">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-4 sm:p-5 md:p-6 bg-gradient-to-r from-gray-50 to-blue-50 dark:from-gray-700 dark:to-blue-900/20 border border-gray-200 dark:border-gray-600 rounded-lg sm:rounded-xl md:rounded-2xl shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="flex items-start sm:items-center gap-2 sm:gap-3 flex-1">
                        <div class="p-2 sm:p-2.5 md:p-3 bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-sm flex-shrink-0">
                            <x-icon name="check-circle" class="w-5 sm:w-6 md:w-6 h-5 sm:h-6 md:h-6 text-blue-600 dark:text-blue-400" />
                        </div>
                        <div>
                            <label class="block text-sm sm:text-base md:text-lg font-bold text-gray-800 dark:text-gray-100">Estado del Miembro</label>
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-0.5 sm:mt-1">
                                {{ $estado === 'Activo' ? 'Miembro activo en la comisión' : 'Miembro inactivo' }}
                            </p>
                        </div>
                    </div>

                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                        <input type="checkbox" class="sr-only peer" wire:click="toggleEstado" @if($estado === 'Activo') checked @endif>
                        <div class="w-14 h-7 sm:w-16 sm:h-8 bg-gray-300 dark:bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-100 dark:peer-focus:ring-blue-800 rounded-full peer peer-checked:after:translate-x-7 sm:peer-checked:after:translate-x-8 peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 sm:after:h-7 after:w-6 sm:after:w-7 after:transition-all dark:border-gray-600 peer-checked:bg-gradient-to-r peer-checked:from-blue-500 peer-checked:to-emerald-600 shadow-inner"></div>
                        <span class="ml-2 sm:ml-3 text-xs sm:text-sm font-semibold @if($estado === 'Activo') text-blue-600 dark:text-green-600 @else text-gray-500 dark:text-gray-400 @endif">
                            {{ $estado === 'Activo' ? 'ACTIVO' : 'INACTIVO' }}
                        </span>
                    </label>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 md:gap-8 pt-4 sm:pt-5 md:pt-6 border-t border-gray-200 dark:border-gray-700">
                @if ($mode !== 'edit')
                    <x-button slate wire:click="limpiar" spinner="limpiar" label="Limpiar" icon="trash"
                        class="inline-flex items-center gap-1.5 sm:gap-2 px-4 sm:px-5 md:px-6 py-2 sm:py-2.5 md:py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-lg sm:rounded-xl shadow-md sm:shadow-lg hover:scale-105 hover:shadow-2xl text-xs sm:text-sm md:text-base transition-all duration-300 w-full sm:w-auto" />
                @endif

                <x-button info type="submit" label="{{ $mode === 'edit' ? 'Actualizar Miembro' : 'Guardar Miembro' }}"
                    icon="check" interaction="positive" spinner="save"
                    class="inline-flex items-center gap-1.5 sm:gap-2 px-4 sm:px-5 md:px-6 py-2 sm:py-2.5 md:py-3 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-lg sm:rounded-xl shadow-md sm:shadow-lg hover:scale-105 hover:shadow-2xl text-xs sm:text-sm md:text-base transition-all duration-300 w-full sm:w-auto" />
            </div>
        </form>
    </div>
</div>

