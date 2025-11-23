<!-- Modal con wire:ignore -->
<div id="default-modal" wire:ignore tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-screen md:h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-3 sm:p-4 w-full max-w-2xl sm:max-w-4xl lg:max-w-6xl max-h-[90vh] md:max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700 h-full flex flex-col">
            <!-- Header -->
            <div class="flex items-center justify-between p-3 sm:p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200 flex-shrink-0">
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white">Selecciona la ubicación</h3>
                <button type="button" onclick="closeMapModal()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-7 h-7 sm:w-8 sm:h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white transition-colors">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>

            <!-- Body -->
            <div class="flex-1 p-2 sm:p-3 md:p-5 space-y-2 md:space-y-4 flex flex-col overflow-hidden">
                <!-- Búsqueda -->
                <div class="flex flex-col sm:flex-row gap-2 flex-shrink-0">
                    <input type="text" id="searchInput" placeholder="Busca una dirección..." class="flex-1 p-2 sm:p-2.5 md:p-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm md:text-base focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="button" onclick="searchAddress(); return false;" class="px-3 sm:px-4 md:px-6 py-2 md:py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors text-sm md:text-base whitespace-nowrap flex items-center justify-center gap-2">
                        <i class="fa-solid fa-search"></i> <span class="hidden sm:inline">Buscar</span>
                    </button>
                </div>

                <!-- Controles de capa y ubicación -->
                <div class="flex flex-wrap gap-2 justify-between flex-shrink-0">
                    <div class="flex gap-2 flex-wrap">
                        <button type="button" onclick="switchMapLayer('osm'); return false;" class="px-2.5 sm:px-3 md:px-4 py-2 bg-gray-600 text-white rounded-lg font-semibold hover:bg-gray-700 transition-colors text-xs sm:text-sm md:text-base flex items-center gap-1.5">
                            <i class="fa-solid fa-map"></i> <span class="hidden sm:inline">Mapa</span>
                        </button>
                        <button type="button" onclick="switchMapLayer('satellite'); return false;" class="px-2.5 sm:px-3 md:px-4 py-2 bg-gray-600 text-white rounded-lg font-semibold hover:bg-gray-700 transition-colors text-xs sm:text-sm md:text-base flex items-center gap-1.5">
                            <i class="fa-solid fa-satellite"></i> <span class="hidden sm:inline">Satélite</span>
                        </button>
                    </div>
                    <button type="button" onclick="centerOnUserLocation(); return false;" class="px-2.5 sm:px-3 md:px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors text-xs sm:text-sm md:text-base flex items-center gap-1.5">
                        <i class="fa-solid fa-location-arrow"></i> <span class="hidden sm:inline">Mi Ubicación</span>
                    </button>
                </div>

                <!-- Mapa -->
                <div id="map" class="w-full flex-1 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-200 dark:bg-gray-600 min-h-[250px] sm:min-h-[300px] md:min-h-[400px] relative">
                    <div id="map-loading" class="absolute inset-0 flex items-center justify-center bg-gray-200 dark:bg-gray-600 z-10 rounded-lg">
                        <span class="text-sm md:text-base">Cargando mapa...</span>
                    </div>
                </div>

                <!-- Coordenadas -->
                <div class="p-2 sm:p-3 md:p-4 bg-blue-50 dark:bg-blue-900/30 rounded-lg border border-blue-200 dark:border-blue-800 flex-shrink-0">
                    <p class="text-xs sm:text-sm md:text-base text-gray-700 dark:text-gray-300 flex items-start gap-2">
                        <i class="fa-solid fa-location-dot text-red-500 mt-0.5 flex-shrink-0"></i>
                        <span id="coordsInfo">Haz clic en el mapa para seleccionar ubicación</span>
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex flex-col-reverse sm:flex-row items-center justify-center gap-2 sm:gap-3 p-3 sm:p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600 flex-shrink-0">
                <button type="button" onclick="closeMapModal(); return false;" 
                    class="inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2 md:py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-gray-400 hover:to-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 text-sm md:text-base w-full sm:w-auto">
                    <i class="fas fa-times"></i>
                    <span>Cancelar</span>
                </button>
                <button type="button" onclick="confirmLocation(); return false;" 
                    class="inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2 md:py-3 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400 text-sm md:text-base w-full sm:w-auto">
                    <i class="fas fa-check"></i>
                    <span>Confirmar</span>
                </button>
            </div>
        </div>
    </div>
</div>
@include('livewire.pages.admin.empresa.js.js')