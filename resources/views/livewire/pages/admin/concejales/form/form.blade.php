  <div class="bg-gray-100 dark:bg-gray-900 min-h-screen flex items-center justify-center py-6 sm:py-8 md:py-10 px-2 sm:px-3 md:px-4">
    <div class="w-full max-w-7xl bg-white dark:bg-gray-800 rounded-2xl sm:rounded-2xl md:rounded-3xl shadow-xl sm:shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-500">

      <!-- Encabezado -->
      <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-3 sm:p-4 md:p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-700 to-indigo-900 text-white gap-3 sm:gap-0">
        <div class="flex items-center gap-2 sm:gap-3">
          <x-icon name="users" class="w-7 sm:w-8 md:w-10 h-7 sm:h-8 md:h-10 text-white animate-bounce flex-shrink-0" />
          <h2 class="text-lg sm:text-xl md:text-2xl lg:text-3xl font-extrabold tracking-wide">
            {{ $mode === 'edit' ? 'Editar Concejal' : 'Registrar Concejal' }}
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
            @if($mode === 'create')
              <strong>Para registrar</strong> Completa la información del <strong>concejal</strong> con sus datos personales, comisión, cargo y foto de perfil.
            @endif
            @if($mode === 'edit')
              <strong>Para editar puedes cambiar cualquier campo existente.</strong>
            @endif
          </p>
        </div>
      </div>

      <!-- Formulario -->
      <form class="p-4 sm:p-6 md:p-8 space-y-6 sm:space-y-8 md:space-y-10" wire:submit.prevent="{{ $mode === 'edit' ? 'update' : 'save' }}">
        
        <!-- Imagen de Perfil -->
        <div class="relative group bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 p-4 sm:p-5 md:p-6 rounded-2xl sm:rounded-2xl md:rounded-3xl border border-blue-200 dark:border-blue-700">
          <label for="imagen" class="flex items-center gap-2 sm:gap-3 mb-4 sm:mb-5 md:mb-6 text-base sm:text-lg md:text-lg font-bold text-gray-800 dark:text-gray-200">
            <x-icon name="photo" class="w-5 sm:w-6 md:w-6 h-5 sm:h-6 md:h-6 text-blue-600 flex-shrink-0" />
            <span>Imagen de Perfil del Concejal</span>
          </label>
          
          <div class="space-y-4 sm:space-y-5 md:space-y-6">
            <input type="file" id="imagen" wire:model="imagen" accept="image/*"
              class="block w-full p-3 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 border-2 border-dashed border-blue-300 dark:border-yellow-600 rounded-xl sm:rounded-xl md:rounded-2xl cursor-pointer hover:border-yellow-500 dark:hover:border-yellow-400 focus:ring-2 focus:ring-blue-500 focus:border-yellow-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
            
            <p class="mt-1 sm:mt-2 text-xs sm:text-xs md:text-sm text-gray-600 dark:text-gray-400 flex items-center gap-1.5">
              <x-icon name="information-circle" class="w-3 sm:w-4 md:w-4 h-3 sm:h-4 md:h-4 text-blue-500 flex-shrink-0" />
              Formatos: JPEG, PNG, JPG, GIF - Máximo 2MB
            </p>

            <div class="flex justify-center">
              <div wire:loading wire:target="imagen" class="text-center">
                <div class="w-24 h-24 sm:w-32 sm:h-32 md:w-40 md:h-40 border-2 border-dashed border-yellow-300 dark:border-yellow-600 rounded-2xl sm:rounded-2xl md:rounded-3xl flex items-center justify-center bg-yellow-50 dark:bg-yellow-900/30 animate-pulse">
                  <div class="text-center">
                    <div class="animate-spin rounded-full h-6 w-6 sm:h-8 sm:w-8 border-b-2 border-yellow-600 mx-auto mb-2"></div>
                    <p class="text-xs sm:text-xs md:text-sm font-medium text-yellow-600 dark:text-yellow-400">Cargando imagen...</p>
                  </div>
                </div>
              </div>

              <div wire:loading.remove wire:target="imagen">
                @if ($imagen)
                  <div class="text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 font-medium">Vista Previa:</p>
                    <div class="relative inline-block">
                      <img src="{{ $imagen->temporaryUrl() }}" alt="Vista previa de imagen" 
                        class="w-40 h-40 rounded-3xl object-cover shadow-xl border-4 border-white dark:border-gray-600 transform transition-all duration-300 hover:scale-105">
                      <div class="absolute -top-2 -right-2 bg-green-500 text-white rounded-full p-1 shadow-lg animate-bounce">
                        <x-icon name="check" class="w-4 h-4" />
                      </div>
                    </div>
                    <p class="mt-2 text-xs text-green-600 dark:text-green-400 font-medium flex items-center justify-center gap-1">
                      <x-icon name="check-circle" class="w-4 h-4" />
                      Imagen cargada correctamente
                    </p>
                  </div>
                @elseif ($mode === 'edit' && $concejal && $concejal->imagen_url)
                  <div class="text-center mb-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Imagen actual:</p>
                    <img src="{{ Storage::url($concejal->imagen_url) }}" 
                      alt="Imagen actual"
                      class="w-40 h-40 rounded-3xl object-cover shadow-xl border-4 border-white dark:border-gray-600">
                  </div>
                @else
                  <div class="text-center text-gray-500 dark:text-gray-400">
                    <div class="w-40 h-40 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-3xl flex items-center justify-center bg-gray-50 dark:bg-gray-700/50">
                      <div class="text-center">
                        <x-icon name="photo" class="w-12 h-12 mx-auto mb-2 text-gray-400" />
                        <p class="text-sm font-medium">Sin imagen seleccionada</p>
                        <p class="text-xs text-gray-400 mt-1">Haz clic arriba para seleccionar</p>
                      </div>
                    </div>
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>

        <!-- Cédula, Nombre y Apellido -->
        <section>
          <h3 class="text-base sm:text-lg md:text-2xl font-bold mb-3 sm:mb-4">Datos Personales</h3>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 md:gap-6">

                  <!-- Cédula Responsivo - CORRECTO -->
          <div class="relative group" x-data>
            <label for="cedula" class="flex items-center gap-1.5 sm:gap-2 mb-1 text-xs sm:text-sm md:text-base font-semibold text-gray-700 dark:text-gray-300">
              <x-icon name="identification" class="w-3.5 sm:w-4 md:w-4 h-3.5 sm:h-4 md:h-4 flex-shrink-0" /> Cédula
            </label>
            <div class="flex transform transition-all duration-300 group-hover:scale-[1.02]">
              <span class="inline-flex items-center px-2 sm:px-2.5 md:px-3 rounded-l-lg sm:rounded-l-xl md:rounded-l-2xl border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-xs sm:text-sm md:text-base font-semibold">
                V
              </span>
              <input type="text" id="cedula" wire:model="cedula"
                x-on:input="$el.value = $el.value.replace(/[^0-9]/g,''); if($el.value.length > 8) { $el.value = $el.value.slice(0,8) } @this.set('cedula', $el.value);"
                maxlength="8" placeholder="Ej: 12345678"
                class="block w-full p-2.5 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-r-lg sm:rounded-r-xl md:rounded-r-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 hover:scale-[1.01] group-hover:shadow-lg">
            </div>
          </div>

            <!-- Nombre Completo -->
            <div class="relative group">
              <label for="nombre" class="flex items-center gap-1.5 sm:gap-2 mb-1 text-xs sm:text-sm md:text-base font-semibold text-gray-700 dark:text-gray-300">
                <x-icon name="user" class="w-3.5 sm:w-4 md:w-4 h-3.5 sm:h-4 md:h-4 flex-shrink-0" /> Nombre Completo
              </label>
              <input type="text" id="nombre" wire:model="nombre" placeholder="Ej: Juan"
                class="block w-full p-2.5 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl md:rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 transform hover:scale-[1.01]">
            </div>
          </div>
        </section>

        <!-- Apellido Completo + Fecha de nacimiento -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 md:gap-6">
          <div class="relative group">
            <label for="apellido" class="flex items-center gap-1.5 sm:gap-2 mb-1 text-xs sm:text-sm md:text-base font-semibold text-gray-700 dark:text-gray-300">
              <x-icon name="user" class="w-3.5 sm:w-4 md:w-4 h-3.5 sm:h-4 md:h-4 flex-shrink-0" /> Apellido Completo
            </label>
            <input type="text" id="apellido" wire:model="apellido" placeholder="Ej: Ramírez"
              class="block w-full p-2.5 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl md:rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 transform hover:scale-[1.01]">
          </div>

          <div class="relative group">
            <label for="fecha_nacimiento" class="flex items-center gap-1.5 sm:gap-2 mb-1 text-xs sm:text-sm md:text-base font-semibold text-gray-700 dark:text-gray-300">
              <x-icon name="calendar" class="w-3.5 sm:w-4 md:w-4 h-3.5 sm:h-4 md:h-4 flex-shrink-0" /> Fecha de Nacimiento
            </label>
            <input type="date" id="fecha_nacimiento" wire:model="fecha_nacimiento"
              class="block w-full p-2.5 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl md:rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 transform hover:scale-[1.01]">
          </div>
        </div>

        <!-- Teléfono y Cargo -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 md:gap-6">
          <div class="relative group">
            <label for="telefono" class="flex items-center gap-1.5 sm:gap-2 mb-1 text-xs sm:text-sm md:text-base font-semibold text-gray-700 dark:text-gray-300">
              <x-icon name="phone" class="w-3.5 sm:w-4 md:w-4 h-3.5 sm:h-4 md:h-4 flex-shrink-0" /> Teléfono
            </label>
            <div class="flex transform transition-all duration-300 group-hover:scale-[1.02] focus-within:scale-[1.03] hover:shadow-lg rounded-lg sm:rounded-xl md:rounded-2xl overflow-hidden">
              <span class="inline-flex items-center px-2 sm:px-3 md:px-3 rounded-l-lg sm:rounded-l-xl md:rounded-l-2xl border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold text-xs sm:text-sm md:text-base transition-all duration-300 group-hover:bg-gray-300 dark:group-hover:bg-gray-600">
                +58
              </span>
              <input type="text" id="telefono" wire:model="telefono"
                x-on:input="$el.value = $el.value.replace(/[^0-9]/g,''); if($el.value.length > 11) { $el.value = $el.value.slice(0,11) } @this.set('telefono', $el.value);"
                maxlength="11" placeholder="Ej: 04129766844"
                class="block w-full p-2.5 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 rounded-r-lg sm:rounded-r-xl md:rounded-r-2xl transform hover:scale-[1.01]">
            </div>
          </div>

          <div class="relative group">
            <label for="cargo" class="flex items-center gap-1.5 sm:gap-2 mb-1 text-xs sm:text-sm md:text-base font-semibold text-gray-700 dark:text-gray-300">
              <x-icon name="identification" class="w-3.5 sm:w-4 md:w-4 h-3.5 sm:h-4 md:h-4 flex-shrink-0" /> Cargo
            </label>
            <input type="text" id="cargo" wire:model="cargo" placeholder="Ej: Presidente Comisión Finanzas"
              class="block w-full p-2.5 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl md:rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 transform hover:scale-[1.01]">
          </div>
        </div>

        @if($mode === 'edit')
        <!-- Comisión -->
        <div 
            x-data="{
                comisionSeleccionada: @entangle('comision_id'),
                miembroExiste: @js($miembro ? true : false),
                limpiarNuevaComision() {
                    if(this.comisionSeleccionada != '') {
                        @this.set('nueva_comision', '');
                        @this.set('descripcion_comision', '');
                    }
                }
            }" 
            x-init="$watch('comisionSeleccionada', value => limpiarNuevaComision())"
            class="mt-4"
        >
            <!-- Selección de comisión existente -->
            <div class="mb-4 relative">
              <label for="comision_id" class="flex items-center gap-1.5 sm:gap-2 mb-1 text-xs sm:text-sm md:text-base font-semibold text-gray-700 dark:text-gray-300">
                <x-icon name="briefcase" class="w-3.5 sm:w-4 md:w-4 h-3.5 sm:h-4 md:h-4 flex-shrink-0" /> Seleccione Comisión
              </label>

              <select 
                  id="comision_id" 
                  wire:model="comision_id"
                  :disabled="!miembroExiste"
                  class="block w-full p-2.5 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl md:rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 disabled:opacity-60 disabled:cursor-not-allowed transform hover:scale-[1.01]"
              >
                  <option value="">Selecciona una Comisión</option>
                  @foreach ($comisiones as $comision)
                      <option value="{{ $comision->id }}">{{ $comision->nombre }}</option>
                  @endforeach
              </select>

  <!-- Mensaje de bloqueo  -->
  <div 
      x-show="!miembroExiste" 
      x-cloak
      class="mt-3 sm:mt-4 md:mt-5 p-3 sm:p-4 md:p-5 rounded-lg sm:rounded-xl border-l-4 border-yellow-400 bg-gradient-to-r from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-900/30 text-yellow-900 dark:text-yellow-200 shadow-md hover:shadow-lg transition-all duration-300"
  >
      <div class="flex items-start sm:items-center gap-2 sm:gap-3 md:gap-4">
          <x-icon name="lock-closed" class="w-5 sm:w-6 md:w-6 h-5 sm:h-6 md:h-6 text-yellow-600 dark:text-yellow-400 flex-shrink-0 mt-0.5 sm:mt-0" />
          <div class="flex-1 min-w-0">
              <p class="text-xs sm:text-sm md:text-base font-semibold leading-relaxed">
                  El campo <strong class="font-bold text-yellow-700 dark:text-yellow-300">Comisión</strong> está bloqueado porque este concejal aún no está registrado como miembro.
              </p>
                  
                </div>
            </div>
            </div>
        </div>
        @endif

        <!-- Perfil / Descripción del Concejal -->
        <div x-data="{ max: 1000, texto: @entangle('perfil') }" class="relative group">
          <label for="perfil" class="flex items-center gap-1.5 sm:gap-2 mb-1 text-xs sm:text-sm md:text-base font-semibold text-gray-700 dark:text-gray-300">
            <x-icon name="chat-bubble-left-right" class="w-3.5 sm:w-4 md:w-4 h-3.5 sm:h-4 md:h-4 flex-shrink-0" /> Perfil / Descripción del concejal
          </label>
          <textarea id="perfil" rows="6" x-model="texto" maxlength="1000"
            class="block w-full p-3 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl md:rounded-2xl resize-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg"
            placeholder="Describe aquí el cargo, funciones y comisión del concejal..."></textarea>
          <div class="flex flex-col sm:flex-row sm:justify-between mt-2 text-xs sm:text-xs md:text-sm text-gray-500 dark:text-gray-400 gap-1 sm:gap-0">
            <p class="flex items-center gap-1"><x-icon name="information-circle" class="w-3 sm:w-4 md:w-4 h-3 sm:h-4 md:h-4 text-yellow-500 flex-shrink-0" /> Máximo 1000 caracteres</p>
            <p class="font-semibold text-yellow-500 dark:text-yellow-400"><span x-text="max - texto.length"></span> restantes</p>
          </div>
        </div>

        <!-- Botones -->
        <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 md:gap-8 pt-4 sm:pt-5 md:pt-6 border-t border-gray-200 dark:border-gray-700">
          @if ($mode !== 'edit')
          <x-button slate wire:click="limpiar" spinner="limpiar" label="Limpiar" icon="trash"
            class="inline-flex items-center gap-1.5 sm:gap-2 px-4 sm:px-5 md:px-6 py-2 sm:py-2.5 md:py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-lg sm:rounded-xl shadow-md sm:shadow-lg hover:scale-105 hover:shadow-2xl text-xs sm:text-sm md:text-base transition-all duration-300 w-full sm:w-auto" />
          @endif

          <!-- Botón Guardar Cambios -->
          <x-button info type="button" label="{{ $mode === 'edit' ? 'Actualizar Concejal' : 'Guardar Concejal' }}" 
            icon="check" interaction="positive" spinner="{{ $mode === 'edit' ? 'update' : 'save' }}" 
            wire:click.prevent="{{ $mode === 'edit' ? 'update' : 'save' }}" 
            class="inline-flex items-center gap-1.5 sm:gap-2 px-4 sm:px-5 md:px-6 py-2 sm:py-2.5 md:py-3 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-lg sm:rounded-xl shadow-md sm:shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400 text-xs sm:text-sm md:text-base w-full sm:w-auto" />
        </div>

      </form>

      <!-- Mensaje de éxito -->
      @if (session()->has('success'))
        <div class="m-4 sm:m-5 md:m-6 p-3 sm:p-4 bg-green-100 text-green-700 rounded-lg text-xs sm:text-sm md:text-base">
          {{ session('success') }}
        </div>
      @endif

    </div>
  </div>

