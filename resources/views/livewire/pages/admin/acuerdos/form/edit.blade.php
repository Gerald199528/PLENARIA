<div class="min-h-screen flex justify-center items-start p-6 bg-gray-100 dark:bg-gray-900"> <!-- contenedor centrador -->

  <div class="w-full max-w-7xl bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-500">

    <!-- Encabezado con gradiente oscuro -->
    <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-700 to-indigo-900 text-white">
      <div class="flex items-center gap-3">
        <x-icon name="document-plus" class="w-8 h-8 text-white animate-bounce" />
        <h2 class="text-3xl md:text-4xl font-extrabold tracking-wide">Editar Acuerdo</h2>
      </div>

      <!-- Botón Volver -->
      <x-button slate wire:click="cancel" spinner="cancel" label="Volver" icon="arrow-left"
          class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-gray-400 hover:to-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400" />
    </div>

    <!-- Mensaje informativo -->
    <div class="p-6 bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 dark:border-blue-400">
      <p class="text-blue-700 dark:text-blue-200 text-base font-medium flex items-center gap-2 animate-fadeIn">
        <x-icon name="information-circle" class="w-5 h-5 text-blue-500 dark:text-blue-300" />
        Aquí podrás <strong>editar solo la fecha de aprobación y observación</strong> de tus acuerdos.
      </p>
    </div>

  <!-- Formulario -->
  <form wire:submit.prevent="save" class="p-8 space-y-8">

    <!-- Nombre y Documento -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <!-- Nombre -->
      <div class="relative group">
        <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
          <x-icon name="document-text" /> Nombre
        </label>
        <input type="text" class="w-full p-4 rounded-2xl bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100"
               value="{{ $acuerdo->nombre }}" readonly>
        <p class="text-yellow-500 text-sm flex items-center gap-1 mt-1">
          <x-icon name="lock-closed" class="w-4 h-4" /> Campo bloqueado
        </p>
      </div>

      <!-- Documento PDF -->
      <div class="relative group">
        <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
          <x-icon name="eye" /> Documento PDF
        </label>
        <button type="button" onclick="window.open('{{ asset('storage/' . $acuerdo->ruta) }}', '_blank')"
            class="w-full p-4 rounded-xl bg-gradient-to-r from-blue-600 to-blue-500 text-white font-semibold flex items-center justify-center gap-2 shadow-lg transform transition-all duration-300 hover:-translate-y-1 hover:scale-105 hover:shadow-2xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400">
          <x-icon name="eye" class="w-5 h-5 animate-pulse" /> Ver PDF
        </button>
      </div>
    </div>

   <!-- Fechas -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <!-- Fecha de Importación (solo lectura) -->
      <div class="relative group">
        <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
          <x-icon name="calendar" /> Fecha de Importación
        </label>
        <input type="text" class="w-full p-4 rounded-2xl bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100"
               value="{{ $acuerdo->fecha_importacion ? $acuerdo->fecha_importacion->format('d/m/Y h:i A') : 'No registrada' }}" readonly>
        <p class="text-yellow-500 text-sm flex items-center gap-1 mt-1">
          <x-icon name="lock-closed" class="w-4 h-4" /> Campo bloqueado
        </p>
      </div>

<!-- Fecha y Hora de Aprobación -->
<div class="relative group">
    <label for="fecha_aprobacion" class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
        <x-icon name="calendar" /> Fecha de Aprobación
    </label>

    <input 
        type="datetime-local" 
        id="fecha_aprobacion" 
        wire:model.defer="fecha_aprobacion" 
        required
        class="w-full p-4 rounded-2xl bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 transition-all duration-300
               focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 group-hover:scale-[1.01] group-hover:shadow-lg"
    >

    @error('fecha_aprobacion') 
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>

</div>



    <!-- Categoría -->
    <div class="relative group">
      <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
        <x-icon name="tag" /> Categoría
      </label>
      <input type="text" class="w-full p-4 rounded-2xl bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100" value="{{ $acuerdo->categoria ? $acuerdo->categoria->nombre : 'Sin categoría' }}" readonly>
      <p class="text-yellow-500 text-sm flex items-center gap-1 mt-1">
        <x-icon name="lock-closed" class="w-4 h-4" /> Campo bloqueado
      </p>
    </div>

    <!-- Observación -->
    <div x-data="{ max: 1000, texto: @entangle('observacion') }" class="relative group">
      <label for="observacion" class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
        <x-icon name="chat-bubble-left-right" /> Observación
      </label>
      <textarea id="observacion" x-model="texto" maxlength="1000" rows="6"
                class="w-full p-5 rounded-2xl bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 resize-none
                       placeholder-yellow-500 dark:placeholder-yellow-400 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg"
                placeholder="Agrega cualquier detalle o nota relevante sobre el acuerdo..."></textarea>

      <div class="flex justify-between mt-2 text-sm text-gray-500 dark:text-gray-400">
        <p>Máximo 1000 caracteres</p>
        <p class="font-semibold text-yellow-500 dark:text-yellow-400"><span x-text="max - texto.length"></span> restantes</p>
      </div>

      @error('observacion')
        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
          <x-icon name="exclamation-circle" class="w-4 h-4" /> {{ $message }}
        </p>
      @enderror
    </div>

    <!-- Botón Guardar Cambios -->
    <div class="flex justify-center gap-8 pt-6 border-t border-gray-200 dark:border-gray-700">
      <x-button info type="submit" spinner="save" label="Guardar Cambios" icon="check" interaction="positive"
                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400" />
    </div>

  </form>
</div>

<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('fadeIn', () => ({
    show: false,
    init() {
      setTimeout(() => this.show = true, 200)
    }
  }))
})
</script>
