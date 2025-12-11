<div class="p-4 sm:p-6 md:p-10 bg-white dark:bg-gray-900 rounded-3xl shadow-2xl max-w-6xl mx-auto border border-gray-200 dark:border-gray-700">

    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 sm:mb-12 border-b pb-4 border-gray-300 dark:border-gray-700 gap-4">
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-gray-800 dark:text-gray-100 tracking-tight">
            {{ $cronista ? 'Cronista del Consejo' : 'Registrar Cronista' }}
        </h2>

        @if($cronista)
            @can('update-cronista')
                <a href="{{ route('admin.cronistas.edit', ['cronista' => $cronista->id]) }}"
                   wire:navigate
                   class="group inline-flex items-center gap-2 sm:gap-3 px-4 sm:px-6 py-2 sm:py-3 text-sm sm:text-base md:text-lg bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl sm:rounded-2xl shadow-lg transition-all hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 w-full sm:w-auto justify-center sm:justify-start">
                    <i class="fa-solid fa-pen-nib animate-bounce text-sm sm:text-base"></i>
                    <span class="hidden sm:inline">Editar</span>
                    <span class="sm:hidden">Editar</span>
                </a>
            @endcan
        @endif
    </div>

    <!-- Card Información del Cronista Existente -->
    @if($cronista)  
        <div class="flex flex-col md:flex-row items-center md:items-start gap-6 sm:gap-8 md:gap-14">
            <!-- Foto -->
            <div class="flex-shrink-0 relative w-full sm:w-auto flex justify-center">
                <div class="relative">
                    <img src="{{ asset('storage/' . $cronista->imagen_url) }}"
                         alt="Imagen Cronista"
                         class="w-48 h-48 sm:w-60 sm:h-60 md:w-72 md:h-72 rounded-3xl object-cover border-4 border-gray-100 dark:border-gray-700 shadow-2xl transition-transform hover:scale-105">
                    <span class="absolute -bottom-3 -right-3 px-3 sm:px-4 py-1 text-xs sm:text-sm bg-indigo-600 text-white rounded-full shadow-lg">Activo</span>
                </div>
            </div>

            <!-- Datos -->
            <div class="flex-1 space-y-3 sm:space-y-5 text-gray-700 dark:text-gray-200 w-full">
                <p class="text-base sm:text-lg md:text-2xl">➡ <span class="font-semibold">Cédula:</span> {{ $cronista->cedula }}</p>
                <p class="text-base sm:text-lg md:text-2xl">➡ <span class="font-semibold">Nombre Completo:</span> {{ $cronista->nombre_completo }}</p>            
                <p class="text-base sm:text-lg md:text-2xl">➡ <span class="font-semibold">Apellido Completo:</span> {{ $cronista->apellido_completo }}</p>            
                <p class="text-base sm:text-lg md:text-2xl">➡ <span class="font-semibold">Teléfono:</span> {{ $cronista->telefono }}</p>
                <p class="text-base sm:text-lg md:text-2xl">➡ <span class="font-semibold">Email:</span> {{ $cronista->email }}</p>

                <div class="text-base sm:text-lg md:text-2xl flex flex-wrap items-center gap-1 sm:gap-2 md:gap-4">                                
                    <span class="font-semibold">➡ Estado / Municipio / Parroquia:</span>
                    
                    <span class="px-2 py-1 bg-blue-100 dark:bg-blue-700 text-blue-800 dark:text-white rounded-lg shadow-sm text-xs sm:text-sm">
                        {{ $cronista->estado->estado ?? '' }}
                    </span>
                    
                    <span class="text-gray-400 font-bold hidden sm:inline">➤</span>
                    
                    <span class="px-2 py-1 bg-green-100 dark:bg-green-700 text-green-800 dark:text-white rounded-lg shadow-sm text-xs sm:text-sm">
                        {{ $cronista->municipio->municipio ?? '' }}
                    </span>
                    
                    <span class="text-gray-400 font-bold hidden sm:inline">➤</span>
                    
                    <span class="px-2 py-1 bg-purple-100 dark:bg-purple-700 text-purple-800 dark:text-white rounded-lg shadow-sm text-xs sm:text-sm">
                        {{ $cronista->parroquia->parroquia ?? '' }}
                    </span>
                </div>

                <p class="text-base sm:text-lg md:text-2xl">➡ <span class="font-semibold">Fecha de inicio:</span> {{ $cronista->fecha_ingreso?->format('d/m/Y') ?? '' }}</p>
                <p class="text-base sm:text-lg md:text-2xl">➡ <span class="font-semibold">Cargo:</span> {{ $cronista->cargo }}</p>
                <p class="text-base sm:text-lg md:text-2xl leading-relaxed">➡ <span class="font-semibold">Descripción de Perfil:</span> {{ $cronista->perfil }}</p>
            </div>
        </div>

        <!-- Separador -->
        <div class="border-t border-gray-300 dark:border-gray-700 mt-8 sm:mt-14 mb-6 sm:mb-10"></div>

        <!-- Botones -->
        <div class="flex flex-col sm:flex-row flex-wrap gap-4 sm:gap-6 md:gap-8 justify-center">
            @can('view-cronica')
                <a href="{{ route('admin.cronicas.index') }}"
                   class="group inline-flex items-center justify-center gap-2 sm:gap-4 px-6 sm:px-8 md:px-10 py-3 sm:py-4 md:py-5 text-base sm:text-lg md:text-2xl bg-gradient-to-r from-green-600 to-teal-500 text-white font-bold rounded-2xl md:rounded-3xl shadow-lg backdrop-blur-sm transition-all hover:scale-105 md:hover:scale-110 hover:shadow-2xl hover:from-green-500 hover:to-teal-600 w-full sm:w-auto">
                    <i class="fa-solid fa-scroll animate-bounce text-sm sm:text-base md:text-lg"></i>
                    <span class="hidden sm:inline">Crear Crónicas</span>
                    <span class="sm:hidden">Crónicas</span>
                </a>
            @endcan
        </div>

    {{-- Sino existe cronista sale la tabla informativa con la dirección de crear --}}
    @else
        <!-- Mensaje cuando no hay cronista y formulario de registro -->
        <div class="mb-6 sm:mb-8 p-4 sm:p-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl">
            <div class="flex items-start gap-2 sm:gap-3">
                <i class="fa-solid fa-info-circle text-blue-600 dark:text-blue-400 text-lg sm:text-xl flex-shrink-0 mt-1"></i>
                <p class="text-sm sm:text-base text-blue-800 dark:text-blue-200 font-medium">
                    No hay ningún cronista registrado. Utiliza el botón "Nuevo Cronista" para registrar uno.
                </p>
            </div>
        </div>

        <!-- Formulario de registro básico o mensaje informativo -->
        <div class="text-center py-8 sm:py-12">
            <i class="fa-solid fa-user-plus text-4xl sm:text-5xl md:text-6xl text-gray-400 dark:text-gray-600 mb-4 sm:mb-6"></i>
            <h3 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-700 dark:text-gray-300 mb-3 sm:mb-4">
                Registra el primer cronista
            </h3>
            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mb-6 sm:mb-8 max-w-2xl mx-auto px-4">
                Para comenzar a gestionar las crónicas del consejo, necesitas registrar al menos un cronista. 
                Haz clic en el botón "Nuevo Cronista" para comenzar el proceso de registro.
            </p>
            @can('create-cronista')
                <a href="{{ route('admin.cronistas.create') }}"
                   wire:navigate
                   class="inline-flex items-center justify-center gap-2 sm:gap-3 px-6 sm:px-8 py-3 sm:py-4 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold text-sm sm:text-base rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400 w-full sm:w-auto">
                    <i class="fa-solid fa-user-plus animate-bounce text-sm sm:text-base"></i>
                    Nuevo Cronista
                </a>
            @endcan
        </div>
    @endif
</div>