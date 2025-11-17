<!-- Formulario sugerencias -->
<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-xl flex flex-col
            transform transition-transform duration-500 hover:scale-[1.03] hover:-translate-y-1 hover:shadow-2xl cursor-pointer">
    <h2 class="text-xl font-bold mb-2 text-gray-700 dark:text-white">Sugerencias / Problemas</h2>
    <p class="text-gray-500 dark:text-gray-200 mb-3">
        Este formulario sirve para enviar tus sugerencias o reportes directamente al soporte técnico.
    </p>
    <textarea wire:model="suggestion" rows="5"
    class="w-full p-4 rounded-lg border border-gray-300 dark:border-gray-700 
           bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white 
           resize-none focus:ring-2 focus:ring-blue-500 transition-all"
    placeholder="Describe un problema o sugerencia..."></textarea>

    <div class="mt-4 flex justify-end">
        <button wire:click="submitSuggestion"
            class="px-4 py-2 bg-blue-500 dark:bg-blue-600 text-white rounded-lg hover:bg-blue-600 dark:hover:bg-blue-700 transition-all shadow-lg">
            Enviar
        </button>
    </div>
    @if(session()->has('message'))
        <p class="mt-3 text-green-500 dark:text-green-400 font-medium">{{ session('message') }}</p>
    @endif
</div>
