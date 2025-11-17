    <!-- Mapa -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-xl flex flex-col
                        transform transition-transform duration-500 hover:scale-[1.03] hover:-translate-y-1 hover:shadow-2xl cursor-pointer">
                <h2 class="text-xl font-bold mb-4 text-gray-700 dark:text-gray-200">Ubicación Actual</h2>
                <div id="map" class="h-64 w-full rounded-lg mb-4"></div>
                <p class="text-gray-700 dark:text-gray-200">Latitud: <span x-text="lat"></span>, Longitud: <span x-text="lng"></span></p>
                <p class="text-gray-700 dark:text-gray-200">Hora actual (Venezuela): <span x-text="currentTimeVZ"></span></p>
                <p class="text-gray-700 dark:text-gray-200">Última conexión (Venezuela): <span x-text="lastConnectionVZ"></span></p>
            </div>

        </div>