<div class="bg-gray-100 dark:bg-gray-900 min-h-screen flex items-center justify-center py-6 sm:py-8 md:py-10 px-2 sm:px-3 md:px-4">
  <div class="w-full max-w-7xl bg-white dark:bg-gray-800 rounded-2xl sm:rounded-2xl md:rounded-3xl shadow-xl sm:shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-500">
    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-3 sm:p-4 md:p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-700 to-indigo-900 text-white gap-3 sm:gap-0">
      <div class="flex items-center gap-2 sm:gap-3">
        <x-icon name="user" class="w-7 sm:w-8 md:w-10 h-7 sm:h-8 md:h-10 text-white animate-bounce flex-shrink-0" />
        <h2 class="text-lg sm:text-xl md:text-2xl lg:text-3xl font-extrabold tracking-wide">
          @if($mode == 'create') Registrar Usuario 
          @elseif($mode == 'edit') Editar Usuario 
          @else Ver Usuario 
          @endif
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
          Completa la información del <strong class="font-bold">usuario</strong>. Los campos con <span class="text-red-500 font-bold">*</span> son obligatorios.
        </p>
      </div>
    </div>

    <form wire:submit.prevent="{{ $mode != 'show' ? 'save' : '' }}" enctype="multipart/form-data" class="p-4 sm:p-6 md:p-8 space-y-6 sm:space-y-8 md:space-y-10">
      <!-- Imagen de Perfil -->
      <div class="relative group bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 p-4 sm:p-5 md:p-6 rounded-2xl sm:rounded-2xl md:rounded-3xl border border-blue-200 dark:border-blue-700">
        <label class="flex items-center gap-2 sm:gap-3 mb-4 sm:mb-5 md:mb-6 text-base sm:text-lg md:text-lg font-bold text-gray-800 dark:text-gray-200">
          <x-icon name="photo" class="w-5 sm:w-6 md:w-6 h-5 sm:h-6 md:h-6 text-blue-600 flex-shrink-0" />
          <span>Imagen de Perfil del Usuario</span>
        </label>

        <div class="space-y-4 sm:space-y-5 md:space-y-6">
          @if($mode != 'show')
          <input type="file" id="image" wire:model="image" accept="image/*"
            class="block w-full p-3 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 border-2 border-dashed border-blue-300 dark:border-yellow-600 rounded-xl sm:rounded-xl md:rounded-2xl cursor-pointer hover:border-yellow-500 dark:hover:border-yellow-400 focus:ring-2 focus:ring-blue-500 focus:border-yellow-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg" />
          <p class="mt-1 sm:mt-2 text-xs sm:text-xs md:text-sm text-gray-600 dark:text-gray-400 flex items-center gap-1.5">
            <x-icon name="information-circle" class="w-3 sm:w-4 md:w-4 h-3 sm:h-4 md:h-4 text-blue-500 flex-shrink-0" />
            Formatos: JPEG, PNG, JPG, GIF - Máximo 2MB
          </p>
          @endif        
          <div class="flex justify-center">
              @if($mode == 'show' && $image_url)
                  <div class="text-center mb-4">
                      <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Imagen del Usuario:</p>
                      <img src="{{ Storage::url($image_url) }}" 
                          alt="Imagen del Usuario"
                          class="w-40 h-40 rounded-3xl object-cover shadow-xl border-4 border-white dark:border-gray-600">
                  </div>
              @else
                  <div wire:loading wire:target="image" class="text-center">
                      <div class="w-40 h-40 border-2 border-dashed border-yellow-300 dark:border-yellow-600 rounded-3xl flex items-center justify-center bg-yellow-50 dark:bg-yellow-900/30 animate-pulse">
                          <div class="text-center">
                              <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-yellow-600 mx-auto mb-2"></div>
                              <p class="text-sm font-medium text-yellow-600 dark:text-yellow-400">Cargando imagen...</p>
                          </div>
                      </div>
                  </div>

                  <div wire:loading.remove wire:target="image">
                      @if ($image)
                          <div class="text-center">
                              <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 font-medium">Vista Previa:</p>
                              <div class="relative inline-block">
                                  <img src="{{ $image->temporaryUrl() }}" alt="Vista previa de imagen" 
                                      class="w-40 h-40 rounded-3xl object-cover shadow-xl border-4 border-white dark:border-gray-600 transform transition-all duration-300 hover:scale-105">
                                  <div class="absolute -top-2 -right-2 bg-green-500 text-white rounded-full p-1 shadow-lg animate-bounce">
                                      <x-icon name="check" class="w-4 h-4" />
                                  </div>
                              </div>
                          </div>
                      @elseif($mode === 'edit' && $user && $user->image_url)
                          <div class="text-center mb-4">
                              <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Imagen actual:</p>
                              <img src="{{ Storage::url($user->image_url) }}" 
                                  alt="Imagen actual"
                                  class="w-40 h-40 rounded-3xl object-cover shadow-xl border-4 border-white dark:border-gray-600">
                          </div>
                      @else
                          <div class="text-center text-gray-500 dark:text-gray-400">
                              <div class="w-40 h-40 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-3xl flex items-center justify-center bg-gray-50 dark:bg-gray-700/50 transition-all duration-300 hover:border-yellow-400 hover:bg-yellow-50 dark:hover:bg-blue-900/20">
                                  <div class="text-center">
                                      <x-icon name="photo" class="w-12 h-12 mx-auto mb-2 text-gray-400" />
                                      <p class="text-sm font-medium">Sin imagen seleccionada</p>
                                      <p class="text-xs text-gray-400 mt-1">Haz clic arriba para seleccionar</p>
                                  </div>
                              </div>
                  </div>
                @endif
              </div>
            @endif
          </div>
        </div>
      </div>

      <!-- Datos Personales -->
      <section>
        <h3 class="text-base sm:text-lg md:text-2xl font-bold mb-3 sm:mb-4">Datos Personales</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 md:gap-6">

          <!-- Documento de Identidad -->
          <div class="relative group flex items-center gap-0">
            <select wire:model="document_type"
              class="p-2.5 sm:p-3 md:p-4 rounded-l-lg sm:rounded-l-xl md:rounded-l-2xl border border-r-0 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg text-xs sm:text-sm md:text-base"
              @if($mode != 'create') disabled @endif>
              <option value="V">V</option>
              <option value="E">E</option>
            </select>

            <input type="text" wire:model="document_number" placeholder="12345678" maxlength="8"
              class="block w-full p-2.5 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-r-lg sm:rounded-r-xl md:rounded-r-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 transform hover:scale-[1.01] group-hover:shadow-lg"
              @if($mode == 'show') readonly @endif />
          </div>

          <!-- Nombres -->
          <div class="relative group">
            <label class="flex items-center gap-1.5 sm:gap-2 mb-1 text-xs sm:text-sm md:text-base font-semibold text-gray-700 dark:text-gray-300">
              <x-icon name="user" class="w-3.5 sm:w-4 md:w-4 h-3.5 sm:h-4 md:h-4 flex-shrink-0" /> Nombres*
            </label>
            <input type="text" wire:model="name" placeholder="Ej: Juan"
              class="block w-full p-2.5 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl md:rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 transform hover:scale-[1.01]"
              @if($mode == 'show') readonly @endif />
          </div>

          <!-- Apellidos -->
          <div class="relative group">
            <label class="flex items-center gap-1.5 sm:gap-2 mb-1 text-xs sm:text-sm md:text-base font-semibold text-gray-700 dark:text-gray-300">
              <x-icon name="user" class="w-3.5 sm:w-4 md:w-4 h-3.5 sm:h-4 md:h-4 flex-shrink-0" /> Apellidos *
            </label>
            <input type="text" wire:model="last_name" placeholder="Ej: Pérez"
              class="block w-full p-2.5 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl md:rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 transform hover:scale-[1.01]"
              @if($mode == 'show') readonly @endif />
          </div>

          <!-- Teléfono -->
          <div class="relative group">
            <label class="flex items-center gap-1.5 sm:gap-2 mb-1 text-xs sm:text-sm md:text-base font-semibold text-gray-700 dark:text-gray-300">
              <x-icon name="phone" class="w-3.5 sm:w-4 md:w-4 h-3.5 sm:h-4 md:h-4 flex-shrink-0" /> Teléfono *
            </label>
            <div class="flex transform transition-all duration-300 group-hover:scale-[1.02] focus-within:scale-[1.03] hover:shadow-lg rounded-lg sm:rounded-xl md:rounded-2xl overflow-hidden">
              <span class="inline-flex items-center px-2 sm:px-3 md:px-3 rounded-l-lg sm:rounded-l-xl md:rounded-l-2xl border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold text-xs sm:text-sm md:text-base transition-all duration-300 group-hover:bg-gray-300 dark:group-hover:bg-gray-600">
                +58
              </span>
              <input type="text" wire:model="phone" placeholder="4123456789" maxlength="11"
                class="block w-full p-2.5 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 rounded-r-lg sm:rounded-r-xl md:rounded-r-2xl transform hover:scale-[1.01]"
                @if($mode == 'show') readonly @endif />
            </div>
          </div>

          <!-- Email -->
          <div class="relative group">
            <label class="flex items-center gap-1.5 sm:gap-2 mb-1 text-xs sm:text-sm md:text-base font-semibold text-gray-700 dark:text-gray-300">
              <i class="fas fa-envelope w-3.5 sm:w-4 md:w-4 h-3.5 sm:h-4 md:h-4 flex-shrink-0"></i> Email *
            </label>
            <input type="email" wire:model="email" placeholder="correo@dominio.com"
              class="block w-full p-2.5 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl md:rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 transform hover:scale-[1.01]"
              @if($mode == 'show') readonly @endif />
          </div>

          <!-- Rol -->
          <div class="relative group">
            <label class="flex items-center gap-1.5 sm:gap-2 mb-1 text-xs sm:text-sm md:text-base font-semibold text-gray-700 dark:text-gray-300">
              <x-icon name="shield-check" class="w-3.5 sm:w-4 md:w-4 h-3.5 sm:h-4 md:h-4 flex-shrink-0" /> Rol de usuario *
            </label>
            <select wire:model="selectedRole"
              class="block w-full p-2.5 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl md:rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 transform hover:scale-[1.01]"
              @if($mode == 'show') disabled @endif>
              <option value="">Selecciona un Rol</option>
              @foreach ($roles as $role)
                <option value="{{ $role->name }}">{{ $role->name }}</option>
              @endforeach
            </select>
          </div>

        </div>
      </section>

      @if (($showPassword ?? true) && $mode != 'show')
      <section>
        <h3 class="text-base sm:text-lg md:text-xl font-bold mb-3 sm:mb-4">Ingrese contraseña</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 md:gap-6">

          <!-- Contraseña -->
          <div x-data="{ show: false }" class="relative group">
            <label class="flex items-center gap-1.5 sm:gap-2 mb-1 text-xs sm:text-sm md:text-base font-semibold text-gray-700 dark:text-gray-300">
              Contraseña *
            </label>
            <input :type="show ? 'text' : 'password'" wire:model="password" placeholder="********"
              class="block w-full p-2.5 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl md:rounded-2xl pr-10 sm:pr-12 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 transform hover:scale-[1.01] group-hover:shadow-lg" />

            <button type="button" @click="show = !show"
              class="absolute inset-y-0 right-3 sm:right-4 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
      
       <template x-if="!show">
          <!-- Ojito abierto -->
          <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-12" fill="none" viewBox="0 0 24 9" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
          </svg>
        </template>
        <template x-if="show">
          <!-- Ojito cerrado -->
          <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-12" fill="none" viewBox="0 0 24 9" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.986 9.986 0 012.158-3.364m3.326-2.352A9.953 9.953 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.056 10.056 0 01-1.768 3.133M3 3l18 18" />
          </svg>
        </template>

            </button>
          </div>

          <!-- Confirmar Contraseña -->
          <div x-data="{ show: false }" class="relative group">
            <label class="flex items-center gap-1.5 sm:gap-2 mb-1 text-xs sm:text-sm md:text-base font-semibold text-gray-700 dark:text-gray-300">
              Confirmar Contraseña *
            </label>
            <input :type="show ? 'text' : 'password'" wire:model="password_confirmation" placeholder="********"
              class="block w-full p-2.5 sm:p-3 md:p-4 text-xs sm:text-sm md:text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl md:rounded-2xl pr-10 sm:pr-12 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 transform hover:scale-[1.01] group-hover:shadow-lg" />

            <button type="button" @click="show = !show"
              class="absolute inset-y-0 right-3 sm:right-4 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
          
      <template x-if="!show">
          <!-- Ojito abierto -->
          <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-12" fill="none" viewBox="0 0 24 9" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
          </svg>
        </template>
        <template x-if="show">
          <!-- Ojito cerrado -->
          <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-12" fill="none" viewBox="0 0 24 9" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.986 9.986 0 012.158-3.364m3.326-2.352A9.953 9.953 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.056 10.056 0 01-1.768 3.133M3 3l18 18" />
          </svg>
        </template>
            </button>
          </div>

        </div>
      </section>
      @endif

      @if($mode == 'show')
      <x-card>
        <x-slot name="footer">
          <div class="flex justify-center">
            <x-button slate wire:click="cancel" spinner="cancel" label="Volver" icon="arrow-left"
              class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 md:px-6 py-1.5 sm:py-2 md:py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-lg sm:rounded-xl shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-2xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 text-xs sm:text-sm md:text-base" />
          </div>
        </x-slot>
      </x-card>
      @endif

      <!-- Botones -->
      @if($mode != 'show')
      <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 md:gap-8 pt-4 sm:pt-5 md:pt-6 border-t border-gray-200 dark:border-gray-700">
        <x-button slate wire:click="limpiar" spinner="limpiar" label="Limpiar" icon="trash"
          class="inline-flex items-center gap-1.5 sm:gap-2 px-4 sm:px-5 md:px-6 py-2 sm:py-2.5 md:py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-lg sm:rounded-xl shadow-md sm:shadow-lg hover:scale-105 hover:shadow-2xl text-xs sm:text-sm md:text-base transition-all duration-300 w-full sm:w-auto" />
        <x-button info type="submit" spinner="save" label="Guardar Usuario" icon="check"
          class="inline-flex items-center gap-1.5 sm:gap-2 px-4 sm:px-5 md:px-6 py-2 sm:py-2.5 md:py-3 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-lg sm:rounded-xl shadow-md sm:shadow-lg hover:scale-105 hover:shadow-2xl text-xs sm:text-sm md:text-base transition-all duration-300 w-full sm:w-auto" />
      </div>
     @endif
    </form>
  </div>
</div>

