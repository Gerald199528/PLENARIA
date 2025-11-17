


   
   <div class="bg-gray-100 dark:bg-gray-900 min-h-screen flex items-center justify-center py-10 px-4">
      <div class="w-full max-w-7xl bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-500">


  <!-- Encabezado -->
  <div class="flex flex-col md:flex-row items-center justify-between p-4 md:p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-700 to-indigo-900 text-white">
    <div class="flex items-center gap-3 mb-4 md:mb-0">
      <x-icon name="user" class="w-10 h-10 text-white animate-bounce" />
      <h2 class="text-2xl md:text-3xl font-extrabold tracking-wide">
        @if($mode == 'create') Registrar Usuario 
        @elseif($mode == 'edit') Editar Usuario 
        @else Ver Usuario 
        @endif
      </h2>
    </div>
    <x-button slate wire:click="cancel" spinner="cancel" label="Volver" icon="arrow-left"
      class="inline-flex items-center gap-2 px-4 py-2 md:px-6 md:py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-xl shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-2xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400" />
  </div>

  <!-- Mensaje informativo -->
  <div class="p-4 md:p-6 bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 dark:border-blue-400">
    <p class="text-blue-700 dark:text-blue-200 text-base font-medium flex items-center gap-2 animate-fadeIn">
      <x-icon name="information-circle" class="w-5 h-5 text-blue-500 dark:text-blue-300" />
      Completa la información del <strong>usuario</strong>. Los campos con * son obligatorios.
    </p>
  </div>
<form wire:submit.prevent="{{ $mode != 'show' ? 'save' : '' }}" enctype="multipart/form-data" class="p-6 md:p-8 space-y-10">

  <!-- Imagen de Perfil -->
  <div class="relative group bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 p-6 rounded-3xl border border-blue-200 dark:border-blue-700">
      <label class="flex items-center gap-3 mb-6 text-lg font-bold text-gray-800 dark:text-gray-200">
          <x-icon name="photo" class="w-6 h-6 text-blue-600" />
          <span>Imagen de Perfil del Usuario</span>
      </label>

      <div class="space-y-6">
          @if($mode != 'show')
          <input type="file" id="image" wire:model="image" accept="image/*"
              class="block w-full p-4 text-base text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 border-2 border-dashed border-blue-300 dark:border-yellow-600 rounded-2xl cursor-pointer hover:border-yellow-500 dark:hover:border-yellow-400 focus:ring-2 focus:ring-blue-500 focus:border-yellow-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
          <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
              <x-icon name="information-circle" class="w-4 h-4 text-blue-500" />
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
    <h3 class="text-lg md:text-2xl font-bold mb-4">Datos Personales</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

      <!-- Documento -->
<!-- Documento de Identidad -->
<div class="relative group flex items-center gap-0">
  <!-- Select tipo de documento -->
  <select wire:model="document_type"
      class="p-4 rounded-l-2xl border border-r-0 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg"
      @if($mode != 'create') disabled @endif>
      <option value="V">V</option>
      <option value="E">E</option>
  </select>

  <!-- Input número de documento -->
  <input type="text" wire:model="document_number" placeholder="12345678" maxlength="8"
      class="block w-full p-4 text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-r-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 transform hover:scale-[1.01] group-hover:shadow-lg"
      @if($mode == 'show') readonly @endif />
</div>


      <!-- Nombres -->
      <div class="relative group">
        <label class="flex items-center gap-2 mb-1 text-base font-semibold text-gray-700 dark:text-gray-300">
          <x-icon name="user" /> Nombres*
        </label>
        <input type="text" wire:model="name" placeholder="Ej: Juan"
          class="block w-full p-4 text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 transform hover:scale-[1.01]"
          @if($mode == 'show') readonly @endif />  
      </div>

      <!-- Apellidos -->
      <div class="relative group">
        <label class="flex items-center gap-2 mb-1 text-base font-semibold text-gray-700 dark:text-gray-300">
          <x-icon name="user" /> Apellidos *
        </label>
        <input type="text" wire:model="last_name" placeholder="Ej: Pérez"
          class="block w-full p-4 text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 transform hover:scale-[1.01]"
          @if($mode == 'show') readonly @endif />   
      </div>

      <!-- Teléfono -->
      <div class="relative group">
        <label class="flex items-center gap-2 mb-1 text-base font-semibold text-gray-700 dark:text-gray-300">
          <x-icon name="phone" /> Teléfono *
        </label>
        <div class="flex transform transition-all duration-300 group-hover:scale-[1.02] focus-within:scale-[1.03] hover:shadow-lg rounded-2xl overflow-hidden">
          <span class="inline-flex items-center px-3 rounded-l-2xl border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold text-base transition-all duration-300 group-hover:bg-gray-300 dark:group-hover:bg-gray-600">
            +58
          </span>
          <input type="text" wire:model="phone" placeholder="4123456789" maxlength="11"
              class="block w-full p-4 text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 rounded-r-2xl transform hover:scale-[1.01]"
              @if($mode == 'show') readonly @endif />
        </div>  
      </div>

      <!-- Email -->
      <div class="relative group">
        <label class="flex items-center gap-2 mb-1 text-base font-semibold text-gray-700 dark:text-gray-300">
          <i class="fas fa-envelope w-4 h-4"></i> Email *
        </label>
        <input type="email" wire:model="email" placeholder="correo@dominio.com"
          class="block w-full p-4 text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 transform hover:scale-[1.01]"
          @if($mode == 'show') readonly @endif />  
      </div>

      <!-- Rol -->
      <div class="relative group">
        <label class="flex items-center gap-2 mb-1 text-base font-semibold text-gray-700 dark:text-gray-300">
          <x-icon name="shield-check" class="w-4 h-4" /> Rol de usuario *
        </label>
        <select wire:model="selectedRole"
          class="block w-full p-4 text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border
           border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 transform hover:scale-[1.01]"
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
  <h3 class="text-lg md:text-xl font-bold mb-4">Ingrese contraseña</h3>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- Contraseña -->
    <div x-data="{ show: false }" class="relative group">
      <label class="flex items-center gap-2 mb-1 font-semibold text-gray-700 dark:text-gray-300">
        Contraseña *
      </label>
      <input :type="show ? 'text' : 'password'" wire:model="password" placeholder="********"
        class="block w-full p-4 text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl pr-12 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 transform hover:scale-[1.01] group-hover:shadow-lg" />

      <!-- Botón ojito centrado y más grande -->
      <button type="button" @click="show = !show"
        class="absolute inset-y-0 right-4 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
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
      <label class="flex items-center gap-2 mb-1 font-semibold text-gray-700 dark:text-gray-300">
        Confirmar Contraseña *
      </label>
      <input :type="show ? 'text' : 'password'" wire:model="password_confirmation" placeholder="********"
        class="block w-full p-4 text-base text-gray-900 dark:text-gray-100 bg-gray-50
         dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl pr-12 focus:ring-2
          focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 transform hover:scale-[1.01]
           group-hover:shadow-lg" />

      <!-- Botón ojito centrado y más grande -->
      <button type="button" @click="show = !show"
        class="absolute inset-y-0 right-4 flex items-center justify-center text-gray-400 hover:text-gray-600
         dark:hover:text-gray-200">
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
        <div class="flex justify-center space-x-2">
            <x-button slate wire:click="cancel" spinner="cancel" label="Volver" icon="arrow-left"
                class="inline-flex items-center gap-2 px-4 py-2 md:px-6 md:py-3 
                bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-xl shadow-lg 
                transition-all duration-300 hover:scale-105 hover:shadow-2xl 
                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400" />
        </div>
    </x-slot>
</x-card>
@endif



    <!-- Botones -->
    @if($mode != 'show')
    <div class="flex flex-col md:flex-row justify-center gap-4 md:gap-8 pt-6 border-t border-gray-200 dark:border-gray-700">
      <x-button slate wire:click="limpiar" spinner="limpiar" label="Limpiar" icon="trash"
        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-xl shadow-lg hover:scale-105 hover:shadow-2xl" />
       <x-button info type="submit" spinner="save" label="Guardar Usuario" icon="check"
        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl shadow-lg hover:scale-105 hover:shadow-2xl" />
    </div>
    @endif

  </form>
</div>
