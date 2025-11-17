<div class="bg-gray-100 dark:bg-gray-900 min-h-screen flex items-center justify-center py-10 px-4">
  <div class="w-full max-w-7xl bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-500">

    <!-- Encabezado -->
    <div class="flex flex-col md:flex-row items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-700 to-indigo-900 text-white">
      <div class="flex items-center gap-3 mb-4 md:mb-0">
        <x-icon name="users" class="w-8 h-8 text-white animate-bounce" />
        <h2 class="text-3xl md:text-4xl font-extrabold tracking-wide">
          {{ $mode === 'edit' ? 'Editar Concejal' : 'Registrar Concejal' }}
        </h2>
      </div>
      <x-button slate wire:click="cancel" spinner="cancel" label="Volver" icon="arrow-left"
        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400" />
    </div>

    <!-- Mensaje informativo -->
    <div class="p-6 bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 dark:border-blue-400">
      <p class="text-lg text-blue-700 dark:text-blue-200 font-medium flex items-center gap-2 animate-fadeIn">
        <x-icon name="information-circle" class="w-5 h-5 text-blue-500 dark:text-blue-300" />
        @if($mode === 'create')
          <strong>Para registrar</strong> Completa la información del <strong>concejal</strong> con sus datos personales, comisión, cargo y foto de perfil.
        @endif
        @if($mode === 'edit')
          <strong>Para editar puedes cambiar cualquier campo existente.</strong>
        @endif
      </p>
    </div>

    <!-- Formulario -->
    <form class="p-8 space-y-8" wire:submit.prevent="{{ $mode === 'edit' ? 'update' : 'save' }}">
      
      <!-- Imagen de Perfil -->
      <div class="relative group bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 p-6 rounded-3xl border border-blue-200 dark:border-blue-700">
        <label for="imagen" class="flex items-center gap-3 mb-6 text-lg font-bold text-gray-800 dark:text-gray-200">
          <x-icon name="photo" class="w-6 h-6 text-blue-600" /> 
          <span>Imagen de Perfil del Concejal</span>
        </label>
        <div class="space-y-6">
          <input type="file" id="imagen" wire:model="imagen" accept="image/*"
            class="block w-full p-4 text-base text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 border-2 border-dashed border-blue-300 dark:border-yellow-600 rounded-2xl cursor-pointer hover:border-yellow-500 dark:hover:border-yellow-400 focus:ring-2 focus:ring-blue-500 focus:border-yellow-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
          
          <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
            <x-icon name="information-circle" class="w-4 h-4 text-blue-500" />
            Formatos: JPEG, PNG, JPG, GIF - Máximo 2MB
          </p>

          <div class="flex justify-center">
            <div wire:loading wire:target="imagen" class="text-center">
              <div class="w-40 h-40 border-2 border-dashed border-yellow-300 dark:border-yellow-600 rounded-3xl flex items-center justify-center bg-yellow-50 dark:bg-yellow-900/30 animate-pulse">
                <div class="text-center">
                  <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-yellow-600 mx-auto mb-2"></div>
                  <p class="text-sm font-medium text-yellow-600 dark:text-yellow-400">Cargando imagen...</p>
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
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Cédula -->
        <div class="relative group" x-data>
          <label for="cedula" class="flex items-center gap-2 mb-1 text-base font-semibold text-gray-700 dark:text-gray-300">
            <x-icon name="identification" /> Cédula
          </label>
          <div class="flex transform transition-all duration-300 group-hover:scale-[1.02]">
            <span class="inline-flex items-center px-3 rounded-l-2xl border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
              V
            </span>
            <input type="text" id="cedula" wire:model="cedula"
              x-on:input="$el.value = $el.value.replace(/[^0-9]/g,''); if($el.value.length > 8) { $el.value = $el.value.slice(0,8) } @this.set('cedula', $el.value);"
              maxlength="8" placeholder="Ej: 12345678"
              class="block w-full p-4 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-r-2xl focus:ring-2 focus:ring-indigo-500">
          </div>
        </div>

        <!--  Nombre Completo -->
        <div class="relative group">
          <label for="nombre" class="flex items-center gap-2 mb-1 text-base font-semibold text-gray-700 dark:text-gray-300">
            <x-icon name="user" /> Nombre Completo
          </label>
          <input type="text" id="nombre" wire:model="nombre" placeholder="Ej: Juan"
            class="block w-full p-4 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500">
        </div>
      </div>

      <!-- Apellido Compelto + Fecha de nacimiento -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="relative group">
          <label for="apellido" class="flex items-center gap-2 mb-1 text-base font-semibold text-gray-700 dark:text-gray-300">
            <x-icon name="user" /> Apellido Compelto
          </label>
          <input type="text" id="apellido" wire:model="apellido" placeholder="Ej: Ramírez"
            class="block w-full p-4 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500">
        </div>

        <div class="relative group">
          <label for="fecha_nacimiento" class="flex items-center gap-2 mb-1 text-base font-semibold text-gray-700 dark:text-gray-300">
            <x-icon name="calendar" /> Fecha de Nacimiento
          </label>
          <input type="date" id="fecha_nacimiento" wire:model="fecha_nacimiento"
            class="block w-full p-4 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500">
        </div>
      </div>

      <!-- Teléfono y Cargo -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="relative group" x-data>
          <label for="telefono" class="flex items-center gap-2 mb-1 text-base font-semibold text-gray-700 dark:text-gray-300">
            <x-icon name="phone" /> Teléfono
          </label>
          <div class="flex transform transition-all duration-300 group-hover:scale-[1.02]">
            <span class="inline-flex items-center px-3 rounded-l-2xl border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold">
              +58
            </span>
            <input type="text" id="telefono" wire:model="telefono"
              x-on:input="$el.value = $el.value.replace(/[^0-9]/g,''); if($el.value.length > 11) { $el.value = $el.value.slice(0,11) } @this.set('telefono', $el.value);"
              maxlength="11" placeholder="Ej: 04129766844"
              class="block w-full p-4 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-r-2xl focus:ring-2 focus:ring-blue-500">
          </div>
        </div>

        <div class="relative group">
          <label for="cargo" class="flex items-center gap-2 mb-1 text-base font-semibold text-gray-700 dark:text-gray-300">
            <x-icon name="identification" /> Cargo
          </label>
          <input type="text" id="cargo" wire:model="cargo" placeholder="Ej: Presidente Comisión Finanzas"
            class="block w-full p-4 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500">
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
            <label for="comision_id" class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
                <x-icon name="briefcase" /> Seleccione Comisión
            </label>

            <select 
                id="comision_id" 
                wire:model="comision_id"
                :disabled="!miembroExiste"
                class="block w-full p-4 text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 disabled:opacity-60 disabled:cursor-not-allowed"
            >
                <option value="">Selecciona una Comisión</option>
                @foreach ($comisiones as $comision)
                    <option value="{{ $comision->id }}">{{ $comision->nombre }}</option>
                @endforeach
            </select>

            <!-- Mensaje de bloqueo -->
            <div 
                x-show="!miembroExiste" 
                x-cloak
                class="mt-6 p-6 rounded-lg  md:text-2xl border-l-4 border-yellow-300 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-900 dark:text-yellow-300 text-sm font-semibold flex items-center gap-2"
                style="min-height: 50px;"
            >
                <x-icon name="lock-closed" class="w-4 h-4 text-yellow-600 mr-2" />
                El campo <strong>Comisión</strong> está bloqueado porque este concejal aún no está registrado como miembro.
            </div>
        </div>
    </div>
@endif



          <!-- Perfil / Descripción del Concejal -->
          <div x-data="{ max: 1000, texto: @entangle('perfil') }" class="relative group">
            <label for="perfil" class="flex items-center gap-2 mb-1 text-base font-semibold text-gray-700 dark:text-gray-300">
              <x-icon name="chat-bubble-left-right" /> Perfil / Descripción del concejal
            </label>
            <textarea id="perfil" rows="6" x-model="texto" maxlength="1000"
              class="block w-full p-5 text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl resize-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg"
              placeholder="Describe aquí el cargo, funciones y comisión del concejal..."></textarea>
            <div class="flex justify-between mt-2 text-sm text-gray-500 dark:text-gray-400">
              <p class="flex items-center gap-1"><x-icon name="information-circle" class="w-4 h-4 text-yellow-500" /> Máximo 1000 caracteres</p>
              <p class="font-semibold text-yellow-500 dark:text-yellow-400"><span x-text="max - texto.length"></span> restantes</p>
            </div>
          </div>

          <!-- Botones -->
          <div class="flex flex-col md:flex-row justify-center gap-4 md:gap-8 pt-6 border-t border-gray-200 dark:border-gray-700">
          @if ($mode !== 'edit')
        <x-button slate wire:click="limpiar" spinner="limpiar" label="Limpiar" icon="trash"
            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-gray-400 hover:to-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400" />
    @endif

    <!-- Botón Guardar Cambios -->
        <x-button  info   type="button"  label="{{ $mode === 'edit' ? 'Actualizar Concejal' : 'Guardar Concejal' }}" 
            icon="check"     interaction="positive"  spinner="{{ $mode === 'edit' ? 'update' : 'save' }}" 
            wire:click.prevent="{{ $mode === 'edit' ? 'update' : 'save' }}"   class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r
            from-blue-600 to-indigo-500 text-white font-semibold rounded-xl shadow-lg transform transition-all 
            duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none 
            focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400" />
    </div>
        </form>
        <!-- Mensaje de éxito -->
        @if (session()->has('success'))
          <div class="m-6 p-3 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
          </div>
        @endif

      </div>
    </div>
